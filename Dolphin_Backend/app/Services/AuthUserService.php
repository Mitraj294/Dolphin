<?php

namespace App\Services;

use App\Models\User;
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
                'phone' => $data['phone'],
                'referral_source_id' => $data['referral_source_id'] ?? $data['find_us'] ?? null,
                'address' => $data['address'],
                'country_id' => $data['country'],
                'state_id' => $data['state'],
                'city_id' => $data['city'],
                'zip' => $data['zip'],
            ]);

            // Persist organization using DB columns `name` and `size`.
            Organization::create([
                'user_id' => $user->id,
                'name' => $data['organization_name'] ?? null,
                'size' => $data['organization_size'] ?? null,
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
        $user->loadMissing(['country', 'roles']);
        $org = Organization::where('user_id', $user->id)->first();

        return [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->roles->first()->name ?? 'user',
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone ?? null,
            'country' => $user->country->name ?? null,
            'country_id' => $user->country_id ?? null,
            'organization_id' => $org?->id,
            'organization_name' => $org?->name,
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

        $user->phone = $detailsData['phone'] ?? $user->phone;

        if (isset($detailsData['country'])) {
            $user->country_id = $this->resolveCountryId($detailsData['country']);
        }

        if (isset($detailsData['referral_source_id'])) {
            $user->referral_source_id = $detailsData['referral_source_id'];
        } elseif (isset($detailsData['find_us'])) {
            $user->referral_source_id = $detailsData['find_us'];
        }

        if ($user->isDirty()) {
            $user->save();
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
