<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssessmentScheduleRequest;
use App\Models\OrganizationAssessment;
use App\Models\OrganizationAssessmentMember;
use App\Models\OrganizationAssessmentGroup;
use App\Models\User;
use App\Models\Group;
use App\Notifications\AssessmentInvitation;
use App\Services\AssessmentLinkService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\AssessmentSchedule;
use Illuminate\Support\Facades\DB;

class AssessmentScheduleController extends Controller
{
    public function store(StoreAssessmentScheduleRequest $request, AssessmentLinkService $linkService)
    {
        try {
            $validated = $request->validated();
            $assessment = OrganizationAssessment::findOrFail($validated['assessment_id']);

            // Resolve user IDs (supports both user_ids and member_ids for backwards compatibility)
            $userIds = $this->resolveUserIds($validated);
            $recipients = User::whereIn('id', $userIds)->get();

            // Store assignments in pivot tables
            $this->storeAssessmentAssignments($assessment, $validated, $userIds);

            // Persist the assessment schedule so the frontend can query schedule details
            // Support both user_ids and member_ids for backwards compatibility
            $memberIdsForSchedule = null;
            if (!empty($validated['user_ids'])) {
                $memberIdsForSchedule = $validated['user_ids'];
            } elseif (!empty($validated['member_ids'])) {
                $memberIdsForSchedule = $validated['member_ids'];
            }

            $scheduleData = [
                'assessment_id' => $assessment->id,
                'date' => $validated['date'] ?? null,
                'time' => $validated['time'] ?? null,
                'group_ids' => !empty($validated['group_ids']) ? $validated['group_ids'] : null,
                'member_ids' => $memberIdsForSchedule,
            ];

            $timezone = $validated['timezone'] ?? null;
            if (! empty($validated['send_at'])) {
                try {
                    $dt = Carbon::parse($validated['send_at'])->setTimezone($timezone ?: 'UTC');
                    $scheduleData['date'] = $dt->toDateString();
                    $scheduleData['time'] = $dt->toTimeString();
                } catch (\Exception $e) {
                    // ignore parse failure; fallback to provided date/time
                }
            }

            $assessmentSchedule = AssessmentSchedule::create($scheduleData);

            $sendAt = $this->determineSendAt($validated);

            $recipientEmails = $recipients->pluck('email')->filter()->unique()->values()->all();

            // Log scheduling summary
            Log::info('Scheduling assessment', [
                'assessment_id' => $assessment->id,
                'assessment_name' => $assessment->name,
                'schedule' => [
                    'date' => $assessmentSchedule->date,
                    'time' => $assessmentSchedule->time,
                    'timezone' => $timezone,
                    'send_at' => $validated['send_at'] ?? null,
                ],
                'recipients_count' => $recipients->count(),
                'recipient_emails' => $recipientEmails,
            ]);

            $this->notifyRecipients($recipients, $linkService, $assessment, $sendAt);

            // Build the response payload expected by frontend
            $emails = DB::table('scheduled_emails')->where('assessment_id', $assessment->id)->get();

            $groupsWithMembers = $this->buildGroupsWithMembers($assessmentSchedule);
            $membersWithDetails = $this->buildMembersWithDetails($assessmentSchedule);

            return response()->json([
                'message' => 'Assessment has been scheduled successfully.',
                'scheduled' => true,
                'schedule' => $assessmentSchedule,
                'assessment' => $assessment,
                'emails' => $emails,
                'groups_with_members' => $groupsWithMembers,
                'members_with_details' => $membersWithDetails,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error scheduling assessment:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'An unexpected error occurred while scheduling the assessment.'], 500);
        }
    }

    /**
     * Store assessment assignments in pivot tables
     */
    private function storeAssessmentAssignments(OrganizationAssessment $assessment, array $validated, $userIds): void
    {
        // Store individual user assignments in organization_assessment_member
        foreach ($userIds as $userId) {
            OrganizationAssessmentMember::updateOrCreate(
                [
                    'organization_assessment_id' => $assessment->id,
                    'user_id' => $userId,
                ],
                [
                    'status' => 'Pending',
                ]
            );
        }

        // Store group assignments in organization_assessment_group
        if (!empty($validated['group_ids'])) {
            foreach ($validated['group_ids'] as $groupId) {
                OrganizationAssessmentGroup::updateOrCreate(
                    [
                        'organization_assessment_id' => $assessment->id,
                        'group_id' => $groupId,
                    ]
                );
            }
        }
    }

    /**
     * Resolve user ids from provided user_ids/member_ids and group_ids in the validated payload.
     * Returns a collection of unique user ids.
     */
    private function resolveUserIds(array $validated)
    {
        // Support both user_ids and member_ids for backwards compatibility
        $userIds = collect($validated['user_ids'] ?? $validated['member_ids'] ?? []);

        if (! empty($validated['group_ids'])) {
            $groupIds = $validated['group_ids'];
            $usersFromGroups = User::whereHas('groups', function ($query) use ($groupIds) {
                $query->whereIn('groups.id', $groupIds);
            })->pluck('id');

            $userIds = $userIds->merge($usersFromGroups)->unique();
        }

        return $userIds;
    }

    /**
     * Determine the Carbon instance for when notifications should be sent.
     */
    private function determineSendAt(array $validated)
    {
        if (! empty($validated['send_at'])) {
            try {
                return Carbon::parse($validated['send_at'])->setTimezone('UTC');
            } catch (\Exception $e) {
                // fall through to parse date/time below
            }
        }

        return Carbon::parse(($validated['date'] ?? '') . ' ' . ($validated['time'] ?? ''));
    }

    /**
     * Notify recipients by generating a per-recipient link and queuing the notification.
     */
    private function notifyRecipients($recipients, AssessmentLinkService $linkService, OrganizationAssessment $assessment, $sendAt): void
    {
        foreach ($recipients as $recipient) {
            $token = $linkService->createAnswerToken($assessment->id, $recipient->id, null);
            $link = $linkService->generateFrontendLink($token, $recipient->id, null);

            try {
                $recipient->notify((new AssessmentInvitation($link, $assessment->name, $assessment->id))->delay($sendAt));
            } catch (\Exception $e) {
                Log::error('Failed to queue notification for recipient', ['recipient_id' => $recipient->id, 'error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Build groups with members payload used by the frontend.
     */
    private function buildGroupsWithMembers(AssessmentSchedule $assessmentSchedule): array
    {
        $groupsWithMembers = [];

        if (empty($assessmentSchedule->group_ids)) {
            return $groupsWithMembers;
        }

        $groups = Group::whereIn('id', $assessmentSchedule->group_ids)
            ->with(['users' => function ($query) {
                $query->with('roles');
            }])
            ->get();

        foreach ($groups as $group) {
            $groupsWithMembers[] = [
                'id' => $group->id,
                'name' => $group->name,
                'members' => $group->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Unknown',
                        'email' => $user->email,
                        'member_role' => $user->pivot->role ?? null,
                        'roles' => $user->roles->map(function ($role) {
                            return ['id' => $role->id, 'name' => $role->name];
                        })
                    ];
                })
            ];
        }

        return $groupsWithMembers;
    }

    /**
     * Build members with details payload used by the frontend.
     */
    private function buildMembersWithDetails(AssessmentSchedule $assessmentSchedule): array
    {
        $membersWithDetails = [];

        if (empty($assessmentSchedule->member_ids)) {
            return $membersWithDetails;
        }

        $users = User::whereIn('id', $assessmentSchedule->member_ids)
            ->with(['roles', 'groups'])
            ->get();

        foreach ($users as $user) {
            $membersWithDetails[] = [
                'id' => $user->id,
                'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Unknown',
                'email' => $user->email,
                'groups' => $user->groups->map(function ($g) {
                    return ['id' => $g->id, 'name' => $g->name];
                }),
                'roles' => $user->roles->map(function ($r) {
                    return ['id' => $r->id, 'name' => $r->name];
                })
            ];
        }

        return $membersWithDetails;
    }
}
