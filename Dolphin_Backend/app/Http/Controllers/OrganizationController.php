<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the organizations.
     */
    public function index(Request $request)
    {
        $user = $request->user()->load('roles');
        $query = Organization::with([
            'user.roles',
            'user.country',
            'user.state',
            'user.city',
            'salesPerson',
            'activeSubscription',
            'address.country',
            'address.state',
            'address.city',
        ]);

        if ($user->hasRole('organizationadmin')) {
            $query->where('user_id', $user->id);
        } elseif (!$user->hasRole('superadmin')) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $organizationsCollection = $query->get();

        // Prefetch latest subscription per user_id to avoid N+1 queries.
        $userIds = $organizationsCollection->pluck('user_id')->filter()->unique()->values()->all();
        $latestSubscriptions = [];
        if (!empty($userIds)) {
            $subs = Subscription::whereIn('user_id', $userIds)
                // subscriptions table uses `ends_at` (not `subscription_end`)
                ->orderByDesc('ends_at')
                ->get()
                ->groupBy('user_id')
                ->map(fn($group) => $group->first())
                ->toArray();

            // normalize to user_id => subscription model (not array)
            foreach ($subs as $uid => $s) {
                // Re-fetch model instance for each id to keep typical model behavior
                $latestSubscriptions[$uid] = Subscription::find($s['id']);
            }
        }

        $organizations = $organizationsCollection->map(fn($org) => $this->formatOrganizationPayload($org, $latestSubscriptions[$org->user_id] ?? null));

        return response()->json($organizations);
    }

    /**
     * Display the specified organization.
     */
    public function show(Request $request, Organization $organization)
    {
        $user = $request->user();
        if (!($user->hasRole('superadmin') || ($user->hasRole('organizationadmin') && $organization->user_id === $user->id))) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $organization->load([
            'user.roles',
            'user.country',
            'user.state',
            'user.city',
            'salesPerson',
            'activeSubscription',
            'address.country',
            'address.state',
            'address.city',
        ]);

        return response()->json($this->formatOrganizationPayload($organization));
    }

    /**
     * Store a newly created organization in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // accept the client-facing keys but map to DB columns below
            'organization_name' => 'required|string|max:255',
            'organization_size' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'sales_person_id' => 'nullable|integer|exists:users,id',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date',
            'last_contacted' => 'nullable|date',
        ]);

        // Map incoming fields to the organizations table columns (name, size)
        $organizationData = [
            'user_id' => $validated['user_id'],
            'name' => $validated['organization_name'],
            'size' => $validated['organization_size'],
            'sales_person_id' => $validated['sales_person_id'] ?? null,
            'contract_start' => $validated['contract_start'] ?? null,
            'contract_end' => $validated['contract_end'] ?? null,
            'last_contacted' => $validated['last_contacted'] ?? null,
        ];

        $organization = Organization::create($organizationData);

        // create organization address if provided in request
        try {
            $addr = $request->only(['address', 'address_line_1', 'address_line_2', 'country_id', 'state_id', 'city_id', 'zip', 'zip_code']);
            $hasAddr = false;
            foreach (['address', 'address_line_1', 'address_line_2', 'country_id', 'state_id', 'city_id', 'zip', 'zip_code'] as $k) {
                if (isset($addr[$k]) && $addr[$k] !== '') {
                    $hasAddr = true;
                    break;
                }
            }
            if ($hasAddr) {
                \App\Models\OrganizationAddress::create([
                    'organization_id' => $organization->id,
                    'address_line_1' => $addr['address_line_1'] ?? $addr['address'] ?? null,
                    'address_line_2' => $addr['address_line_2'] ?? null,
                    'country_id' => $addr['country_id'] ?? null,
                    'state_id' => $addr['state_id'] ?? null,
                    'city_id' => $addr['city_id'] ?? null,
                    'zip_code' => $addr['zip_code'] ?? $addr['zip'] ?? null,
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to create organization address on store', ['error' => $e->getMessage()]);
        }

        return response()->json($this->formatOrganizationPayload($organization->fresh()), 201);
    }

    /**
     * Update the specified organization in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        // Using a policy for authorization is recommended here
        // $this->authorize('update', $organization);

        $validated = $request->validate([
            'organization_name' => 'sometimes|string|max:255',
            'organization_size' => 'sometimes|string',
            'sales_person_id' => 'nullable|integer|exists:users,id',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date',
            'last_contacted' => 'nullable|date',
            'admin_email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($organization->user_id)],
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'admin_phone' => 'sometimes|string|regex:/^[6-9]\d{9}$/',
            'address' => 'sometimes|string',
            'address_line_1' => 'sometimes|string',
            'address_line_2' => 'sometimes|string',
            'country_id' => 'sometimes|integer|exists:countries,id',
            'state_id' => 'sometimes|integer|exists:states,id',
            'city_id' => 'sometimes|integer|exists:cities,id',
            'zip' => 'sometimes|string|regex:/^[1-9][0-9]{5}$/',
            'zip_code' => 'sometimes|string|regex:/^[1-9][0-9]{5}$/',
        ]);

        try {
            DB::transaction(function () use ($organization, $validated) {
                // Map client keys to DB columns for update
                $organizationData = [];
                if (array_key_exists('organization_name', $validated)) {
                    $organizationData['name'] = $validated['organization_name'];
                }
                if (array_key_exists('organization_size', $validated)) {
                    $organizationData['size'] = $validated['organization_size'];
                }
                if (array_key_exists('sales_person_id', $validated)) {
                    $organizationData['sales_person_id'] = $validated['sales_person_id'];
                }
                if (array_key_exists('contract_start', $validated)) {
                    $organizationData['contract_start'] = $validated['contract_start'];
                }
                if (array_key_exists('contract_end', $validated)) {
                    $organizationData['contract_end'] = $validated['contract_end'];
                }
                if (array_key_exists('last_contacted', $validated)) {
                    $organizationData['last_contacted'] = $validated['last_contacted'];
                }

                $userData = array_filter($validated, function ($key) {
                    return in_array($key, ['admin_email', 'first_name', 'last_name']);
                }, ARRAY_FILTER_USE_KEY);

                $userDetailsData = array_filter($validated, function ($key) {
                    return in_array($key, ['admin_phone']);
                }, ARRAY_FILTER_USE_KEY);

                $orgAddressData = array_filter($validated, function ($key) {
                    return in_array($key, ['address', 'address_line_1', 'address_line_2', 'country_id', 'state_id', 'city_id', 'zip', 'zip_code']);
                }, ARRAY_FILTER_USE_KEY);

                if (!empty($organizationData)) {
                    $organization->update($organizationData);
                }

                if ($organization->user) {
                    if (!empty($userData)) {
                        $organization->user->update($userData);
                    }
                    if (!empty($userDetailsData)) {
                        $organization->user->userDetails()->updateOrCreate(['user_id' => $organization->user_id], $userDetailsData);
                    }

                    // update/create organization address from provided fields
                    if (!empty($orgAddressData)) {
                        $addrPayload = [];
                        if (array_key_exists('address_line_1', $orgAddressData)) {
                            $addrPayload['address_line_1'] = $orgAddressData['address_line_1'];
                        } elseif (array_key_exists('address', $orgAddressData)) {
                            $addrPayload['address_line_1'] = $orgAddressData['address'];
                        }
                        if (array_key_exists('address_line_2', $orgAddressData)) {
                            $addrPayload['address_line_2'] = $orgAddressData['address_line_2'];
                        }
                        if (array_key_exists('country_id', $orgAddressData)) {
                            $addrPayload['country_id'] = $orgAddressData['country_id'];
                        }
                        if (array_key_exists('state_id', $orgAddressData)) {
                            $addrPayload['state_id'] = $orgAddressData['state_id'];
                        }
                        if (array_key_exists('city_id', $orgAddressData)) {
                            $addrPayload['city_id'] = $orgAddressData['city_id'];
                        }
                        if (array_key_exists('zip_code', $orgAddressData)) {
                            $addrPayload['zip_code'] = $orgAddressData['zip_code'];
                        } elseif (array_key_exists('zip', $orgAddressData)) {
                            $addrPayload['zip_code'] = $orgAddressData['zip'];
                        }

                        if (!empty($addrPayload)) {
                            $addrPayload['organization_id'] = $organization->id;
                            \App\Models\OrganizationAddress::updateOrCreate(['organization_id' => $organization->id], $addrPayload);
                        }
                    }
                }
            });

            return response()->json($this->formatOrganizationPayload($organization->fresh()));
        } catch (\Exception $e) {
            Log::error('Failed to update organization', ['id' => $organization->id, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update organization.'], 500);
        }
    }

    /**
     * Remove the specified organization from storage.
     */
    public function destroy(Organization $organization)
    {
        // Using a policy for authorization is recommended here
        // $this->authorize('delete', $organization);

        $organization->delete();

        return response()->json(null, 204);
    }


    private function formatOrganizationPayload(Organization $org, ?Subscription $providedLatestSubscription = null): array
    {
        $user = $org->user;
        $details = $user?->userDetails;
        // activeSubscription is eager-loaded (if present) but we also need to
        // inspect the latest subscription record for this organization's user
        // to distinguish active / expired / no-subscription states.
        $subscription = $org->activeSubscription;
        // Use the provided latest subscription (prefetched) if available to avoid extra queries.
        $latestSubscription = $providedLatestSubscription;
        if (!$latestSubscription && $org->user_id) {
            $latestSubscription = Subscription::where('user_id', $org->user_id)
                // use ends_at which is the datetime column present on subscriptions
                ->orderByDesc('ends_at')
                ->first();
        }



        // If you don't have a full_name accessor, this is the direct fix:
        if ($org->salesPerson) {
            $salesPersonName = trim($org->salesPerson->first_name . ' ' . $org->salesPerson->last_name);
        } else {
            $salesPersonName = null;
        }

        // Determine primary role for the organization's user (if any)
        $userRole = null;
        if ($user && $user->roles && $user->roles->count() > 0) {
            $userRole = $user->roles->first()->name ?? null;
        }

        $address = $org->address; // OrganizationAddress model (preferred)

        return [
            // related user id and role (from pivot/roles)
            'user_id' => $org->user_id,
            'user_role' => $userRole,
            'id' => $org->id,
            // map DB columns back to client-facing keys
            'organization_name' => $org->name,
            'organization_size' => $org->size,
            'main_contact' => $user?->first_name . ' ' . $user?->last_name,
            'admin_email' => $user?->email,
            'admin_phone' => $details?->phone,
            'address' => $address?->address_line_1 ?? $details?->address,
            'city' => $address?->city?->name ?? $details?->city?->name,
            'state' => $address?->state?->name ?? $details?->state?->name,
            'country' => $address?->country?->name ?? $details?->country?->name,
            'zip' => $address?->zip_code ?? $details?->zip,
            'contract_start' => $subscription?->subscription_start?->toDateString() ?? $org->contract_start,
            // normalize subscription end -> uses `ends_at` on the subscriptions table
            'contract_end' => $subscription?->ends_at?->toDateString() ?? $org->contract_end,
            'source' => $details?->find_us,
            'last_contacted' => $org->last_contacted,
            'sales_person_id' => $org->sales_person_id,
            'sales_person' => $salesPersonName,
            'certified_staff' => $org->certified_staff,
            // Subscription status flags (1 or 0) based on latest subscription row
            'active_subscription' => ($latestSubscription && $latestSubscription->status === 'active') ? 1 : 0,
            'expired_subscription' => ($latestSubscription && $latestSubscription->status === 'expired') ? 1 : 0,
            'no_subscription' => $latestSubscription ? 0 : 1,
        ];
    }
}
