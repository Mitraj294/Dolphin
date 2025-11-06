<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use App\Mail\LeadAssessmentRegistrationMail;


// LeadController
// Controller for managing Lead operations:
//  - Create, update, show, list, delete leads
//  - Generate registration and agreement email templates
//  - Prefill registration form data
//  - Get distinct 'find_us' options for leads
// Structure:
// 1. Validation Rules (LeadValidationRules)
// 2. Message Constants (Message)
// 3. Controller Methods



// 1. Validation Rules for Lead Model Fields

class LeadValidationRules
{
    public const REQUIRED_INTEGER = 'required|integer';
    public const REQUIRED_STRING = 'required|string';
    public const REQUIRED_EMAIL = 'required|email';
    public const OPTIONAL_INTEGER = 'nullable|integer';
    public const OPTIONAL_STRING = 'nullable|string';
    public const REQUIRED_BOOLEAN = 'required|boolean';
    public const REQUIRED_DATE = 'required|date';
}


// 2. Common Messages Used by LeadController

class Message
{
    public const MESSAGE = 'Lead Not Found';
}


// 3. LeadController

class LeadController extends Controller
{

    // Update an existing lead.
    // Handles PATCH for notes-only update.
    // @param Request $request
    // @param int $id
    // @return \Illuminate\Http\JsonResponse

    public function update(Request $request, $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json(['message' => Message::MESSAGE], 404);
        }

        // Full update validation rules
        $data = $request->validate([
            'first_name'        => LeadValidationRules::REQUIRED_STRING,
            'last_name'         => LeadValidationRules::REQUIRED_STRING,
            'email'             => LeadValidationRules::REQUIRED_EMAIL,
            'phone'             => 'required|regex:/^[6-9]\d{9}$/',
            'status'            => LeadValidationRules::OPTIONAL_STRING,
        ]);

        $lead->update($data);
        return response()->json(['message' => 'Lead updated successfully', 'lead' => $lead]);
    }


    // Store a new lead.
    // @param Request $request
    // @return \Illuminate\Http\JsonResponse

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'        => LeadValidationRules::REQUIRED_STRING,
            'last_name'         => LeadValidationRules::REQUIRED_STRING,
            // Allow creating leads even if a user already exists with this email.
            'email'             => 'required|string|email|max:255',
            'phone'      => 'required|regex:/^[6-9]\d{9}$/',
            'status'            => LeadValidationRules::OPTIONAL_STRING,
        ]);

        // Record creator if authenticated
        if ($request->user()) {
            $data['created_by'] = $request->user()->id;
        }

        $lead = Lead::create($data);

        // If a user already exists with this email, mark the lead as Registered.
        try {
            $userModel = '\App\\Models\\User';
            $matchedUser = $userModel::where('email', $lead->email)->first();
            if ($matchedUser) {
                $lead->status = 'Registered';
                $lead->save();
                Log::info('LeadController: Created lead matched existing user; marked Registered', [
                    'lead_id' => $lead->id,
                    'user_id' => $matchedUser->id
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('LeadController: Failed to check users table after lead create: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Lead saved successfully', 'lead' => $lead], 201);
    }


    // List all leads for authenticated user.
    // Superadmin sees all leads.
    // @return \Illuminate\Http\JsonResponse

    public function index()
    {
        $user = request()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $with = ['notes.creator:id,first_name,last_name,email', 'organization.user:id,first_name,last_name,email,phone_number', 'organization:id,name,size,contract_start,contract_end'];

        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            $leads = Lead::with($with)->get();
        } elseif (Schema::hasColumn('leads', 'created_by')) {
            $leads = Lead::with($with)->where('created_by', $user->id)->get();
        } else {
            $orgId = null;
            try {
                $orgId = $user->belongsToOrganization()->getQuery()->value('id');
            } catch (\Exception $e) {
                Log::warning('LeadController@index failed to determine user organization: ' . $e->getMessage());
            }
            $leads = Lead::with($with)->where('organization_id', $orgId)->get();
        }

        $payload = $leads->map(function ($lead) {
            return [
                'id' => $lead->id,
                'organization_id' => $lead->organization_id,
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'contact' => trim(($lead->first_name ?? '') . ' ' . ($lead->last_name ?? '')),
                'email' => $lead->email,
                'phone_number' => $lead->phone_number ?? optional($lead->organization->user)->phone_number,
                'status' => $lead->status,
                'created_at' => $lead->created_at,
                'updated_at' => $lead->updated_at,
                'deleted_at' => $lead->deleted_at,
                'organization' => $lead->organization ? [
                    'id' => $lead->organization->id,
                    'name' => $lead->organization->name ?? null,
                    'size' => $lead->organization->size ?? null,
                    'contract_start' => $lead->organization->contract_start ?? null,
                    'contract_end' => $lead->organization->contract_end ?? null,
                ] : null,
                'notes_count' => $lead->notes ? $lead->notes->count() : 0,
            ];
        });

        return response()->json($payload);
    }


    // Show details for a single lead.
    // Includes registration link, organization, and sales person info if available.
    // @param int $id
    // @return \Illuminate\Http\JsonResponse

    public function show($id)
    {
        $lead = Lead::with([
            'notes.creator:id,first_name,last_name,email',
            'organization.user:id,first_name,last_name,email,phone_number',
            'organization',
        ])->find($id);
        if (!$lead) {
            return response()->json(['message' => Message::MESSAGE], 404);
        }
        $registration_link = $this->buildRegistrationLink($lead);
        Log::info('LeadController: prepared registration_link', [
            'registration_link' => $registration_link,
            'lead_id' => $lead->id,
        ]);

        $defaultTemplate = $this->buildDefaultTemplate($lead, $registration_link);

        // Prefer relationship-based organization if available
        $org = $lead->organization;
        $orgUser = $org && $org->user ? $org->user : null;

        // Build address display if organization has address relation
        $address = null;
        try {
            if ($org && property_exists($org, 'address')) {
                $addr = $org->address;
                if ($addr) {
                    $parts = [];
                    if (!empty($addr->address_line_1)) {
                        $parts[] = $addr->address_line_1;
                    }
                    if (!empty($addr->address_line_2)) {
                        $parts[] = $addr->address_line_2;
                    }
                    if (!empty($addr->city)) {
                        $parts[] = is_object($addr->city) ? ($addr->city->name ?? null) : $addr->city;
                    }
                    if (!empty($addr->state)) {
                        $parts[] = is_object($addr->state) ? ($addr->state->name ?? null) : $addr->state;
                    }
                    if (!empty($addr->zip_code)) {
                        $parts[] = $addr->zip_code;
                    }
                    if (!empty($addr->country)) {
                        $parts[] = is_object($addr->country) ? ($addr->country->name ?? null) : $addr->country;
                    }
                    $address = implode(', ', array_filter($parts));
                }
            }
        } catch (\Exception $e) {
            Log::warning('LeadController@show building address failed: ' . $e->getMessage());
        }

        $resp = [
            'lead' => $lead,
            'defaultTemplate' => $defaultTemplate,
        ];

        if ($org) {
            $resp['organization'] = [
                'id' => $org->id,
                'name' => $org->name ?? null,
                'size' => $org->size ?? null,
                'contract_start' => $org->contract_start ?? null,
                'contract_end' => $org->contract_end ?? null,
                'address' => $address ?? 'N/A',
            ];
        }

        if ($orgUser) {
            $resp['orgUser'] = $orgUser;
        }

        return response()->json($resp);
    }

    /**
     * Build a registration link for a lead.
     */
    private function buildRegistrationLink(Lead $lead): string
    {
        $frontendBase = env('FRONTEND_URL', env('APP_URL', 'http://127.0.0.1:8080'));
        $queryParams = [
            'email' => $lead->email,
            'first_name' => $lead->first_name ?? '',
            'last_name' => $lead->last_name ?? '',
            'phone' => $lead->phone ?? '',
            'lead_id' => $lead->id,
        ];

        return rtrim($frontendBase, '/') . '/register?' . http_build_query($queryParams);
    }

    /**
     * Build default registration email HTML template for a lead.
     */
    private function buildDefaultTemplate(Lead $lead, string $registrationLink): string
    {
        $safeLink = htmlspecialchars($registrationLink, ENT_QUOTES, 'UTF-8');
        $safeName = htmlspecialchars((string)($lead->first_name ?? $lead->email), ENT_QUOTES, 'UTF-8');

        return <<<HTML
            <h2>Hello {$safeName},</h2>
            <p>You've been invited to complete your signup. Please click the button below to enter your details and activate your account.</p>
            <p style="text-align: center;">
                <a href="{$safeLink}" style="display: inline-block; padding: 12px 24px; background-color: #0164A5; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;">Complete Signup</a>
            </p>
            <p style="font-size: 13px; color: #888888; text-align: center;">If you did not request this, you can safely ignore this email.</p>
        HTML;
    }


    // sales person resolution removed — application schema no longer guarantees sales_person_id on leads/orgs


    // Soft-delete a lead by id.
    // @param Request $request
    // @param int $id
    // @return \Illuminate\Http\JsonResponse

    public function destroy(Request $request, $id)
    {
        $lead = Lead::find($id);
        if (!$lead) {
            return response()->json(['message' => Message::MESSAGE], 404);
        }

        try {
            $lead->delete();
            Log::info('LeadController@destroy soft-deleted lead', [
                'lead_id'   => $id,
                'deleted_by' => $request->user()->id ?? null
            ]);
            return response()->json(['message' => 'Lead soft-deleted', 'id' => $id]);
        } catch (\Exception $e) {
            Log::error('LeadController@destroy failed to delete lead: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete lead'], 500);
        }
    }


    // Generates registration invite email HTML.
    // @param Request $request
    // @return \Illuminate\Http\Response

    public function leadRegistration(Request $request)
    {
        $registration_link = $request->query('registration_link', rtrim(env('FRONTEND_URL', env('APP_URL', 'http://127.0.0.1:8080')), '/') . '/register');
        $name = $request->query('name', '');

        $safeLink = htmlspecialchars($registration_link, ENT_QUOTES, 'UTF-8');
        $safeName = htmlspecialchars($name ?: 'User', ENT_QUOTES, 'UTF-8');

        $html = <<<HTML
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width,initial-scale=1" />
            <title>Registration Invite</title>
        </head>
        <body>
            <div class="email-container">
                <div style="width:100%; padding:40px 0; background-color:#f6f9fc; font-family: Arial, sans-serif;">
                    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:6px; padding:30px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                        <div style="font-size:20px; font-weight:bold; color:#333333; margin-bottom:15px;">Hello {$safeName},</div>
                        <div style="font-size:16px; color:#555555; line-height:1.5; margin-bottom:25px;">You’ve been invited to complete your signup. Please click the button below to enter your details and activate your account.</div>
                        <div style="text-align:center;">
                            <a href="{$safeLink}" style="display:inline-block; padding:10px 20px; background-color:#0164A5; color:#ffffff; text-decoration:none; border-radius:50px; font-weight:bold;">Complete Signup</a>
                        </div>
                        <div style="font-size:13px; color:#888888; text-align:center; margin-top:30px;">If you did not request this, you can safely ignore this email.</div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        HTML;

        return response($html, 200)->header('Content-Type', 'text/html');
    }


    // Generates lead agreement email HTML.
    // @param Request $request
    // @return \Illuminate\Http\Response

    public function leadAgreement(Request $request)
    {
        $checkout_url = $request->query('checkout_url', rtrim(env('FRONTEND_URL', env('APP_URL', 'http://127.0.0.1:8080')), '/') . '/subscriptions/plans');
        $name = $request->query('name', '');

        $safeLink = htmlspecialchars($checkout_url, ENT_QUOTES, 'UTF-8');
        $safeName = htmlspecialchars($name ?: 'User', ENT_QUOTES, 'UTF-8');

        Log::info('LeadController: prepared leadAgreement checkout_url', ['checkout_url' => $checkout_url, 'name' => $name]);

        $html = <<<HTML
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width,initial-scale=1" />
            <title>Agreement and Payment</title>
        </head>
        <body>
            <div class="email-container">
                <div style="width:100%; padding:40px 0; background-color:#f6f9fc; font-family: Arial, sans-serif;">
                    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:6px; padding:30px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                        <div style="font-size:20px; font-weight:bold; color:#333333; margin-bottom:15px;">Hello {$safeName},</div>
                        <div style="font-size:16px; color:#555555; line-height:1.5; margin-bottom:25px;">Please find your agreement and payment link below. Click the button to proceed with the subscription.</div>
                        <div style="text-align:center;">
                            <a href="{$safeLink}" style="display:inline-block; padding:10px 20px; background-color:#0164A5; color:#ffffff; text-decoration:none; border-radius:50px; font-weight:bold;">Proceed to Payment</a>
                        </div>
                        <div style="font-size:13px; color:#888888; text-align:center; margin-top:30px;">If you did not request this, you can safely ignore this email.</div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        HTML;

        return response($html, 200)->header('Content-Type', 'text/html');
    }


    // Prefill lead info for registration form.
    // @param Request $request
    // @return \Illuminate\Http\JsonResponse

    public function prefill(Request $request)
    {
        $lead = null;
        if ($request->has('lead_id')) {
            $lead = Lead::find($request->input('lead_id'));
        } elseif ($request->has('email')) {
            $lead = Lead::where('email', $request->input('email'))->first();
        }
        if (!$lead) {
            return response()->json(['message' => Message::MESSAGE], 404);
        }
        return response()->json(['lead' => [
            'organization_id'        => $lead->organization_id ?? null,
            'first_name'             => $lead->first_name,
            'last_name'              => $lead->last_name,
            'email'                  => $lead->email,
            'phone'           => $lead->phone ?? null,
            'status'                 => $lead->status ?? null,
        ]]);
    }


    // Get referral sources from referral_sources table.
    // @return \Illuminate\Http\JsonResponse

    public function findUsOptions()
    {
        $sources = \App\Models\ReferralSource::orderBy('name')->get(['id', 'name']);

        if ($sources->isEmpty()) {
            Log::info('Referral sources: none found');
        } else {
            Log::info('Referral sources fetched: ' . $sources->count());
        }

        return response()->json(['options' => $sources]);
    }
}
