<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Country;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthUserService
{
    /**
     * Create a user and their associated details in a transaction.
     */
    public function createUserAndDetails(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            UserDetail::create([
                'user_id' => $user->id,
                'phone' => $data['phone'],
                'find_us' => $data['find_us'],
                'address' => $data['address'],
                'country_id' => $data['country'],
                'state_id' => $data['state'],
                'city_id' => $data['city'],
                'zip' => $data['zip'],
            ]);

            Organization::create([
                'user_id' => $user->id,
                'organization_name' => $data['organization_name'],
                'organization_size' => $data['organization_size'],
            ]);

            // Set the organization_id on the user to reference the created organization
            $org = Organization::where('user_id', $user->id)->first();
            if ($org) {
                $user->organization_id = $org->id;
                $user->save();
            }

            $user->roles()->attach(Role::where('name', 'user')->first());

            return $user;
        });
    }

    public function updateLeadStatus(string $email): void
    {
        try {
            $lead = Lead::where('email', $email)->first();
            if ($lead) {
                $lead->update(['status' => 'Registered', 'registered_at' => now()]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to update lead status after registration', ['email' => $email, 'error' => $e->getMessage()]);
        }
    }

    public function buildUserPayload(User $user): array
    {
        $user->loadMissing(['userDetails.country', 'roles']);
        $org = Organization::where('user_id', $user->id)->first();

        return [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->roles->first()->name ?? 'user',
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->userDetails->phone ?? null,
            'country' => $user->userDetails->country->name ?? null,
            'country_id' => $user->userDetails->country_id ?? null,
            'organization_id' => $org?->id,
            'organization_name' => $org?->organization_name,
        ];
    }

    public function updateUserRecord(User $user, array $userData, array $detailsData): void
    {
        $user->fill([
            'email' => $userData['email'] ?? $detailsData['email'] ?? $user->email,
            'first_name' => $detailsData['first_name'] ?? $user->first_name,
            'last_name' => $detailsData['last_name'] ?? $user->last_name,
        ]);

        if ($user->isDirty()) {
            $user->save();
        }
    }

    public function updateUserDetailsRecord(User $user, array $detailsData): void
    {
        if (empty($detailsData)) {
            return;
        }

        $userDetail = UserDetail::firstOrNew(['user_id' => $user->id]);
        $userDetail->phone = $detailsData['phone'] ?? $userDetail->phone;

        if (isset($detailsData['country'])) {
            $userDetail->country_id = $this->resolveCountryId($detailsData['country']);
        }

        if ($userDetail->isDirty()) {
            $userDetail->save();
        }
    }

    private function resolveCountryId($countryInput): ?int
    {
        if (is_numeric($countryInput)) {
            return (int) $countryInput;
        }

        if (is_string($countryInput) && !empty(trim($countryInput))) {
            $country = Country::where('name', trim($countryInput))->orWhere('code', trim($countryInput))->first();
            return $country?->id;
        }

        return null;
    }
}
