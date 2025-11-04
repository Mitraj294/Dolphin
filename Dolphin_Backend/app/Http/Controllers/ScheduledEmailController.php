<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduledEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\AssessmentAnswerToken;
use App\Models\Member;
use App\Models\Group;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AssessmentAnswerLinkNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ScheduledEmailValidationRules
{
    public const REQUIRED_INTEGER = 'required|integer';
    public const REQUIRED_STRING = 'required|string';
    public const REQUIRED_EMAIL = 'required|email';
    public const OPTIONAL_INTEGER = 'nullable|integer';
    public const REQUIRED_BOOLEAN = 'required|boolean';
    public const REQUIRED_DATE = 'required|date';
}

class ScheduledEmailController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_email' => ScheduledEmailValidationRules::REQUIRED_EMAIL,
            'subject' => ScheduledEmailValidationRules::REQUIRED_STRING,
            'body' => ScheduledEmailValidationRules::REQUIRED_STRING,
            'send_at' => ScheduledEmailValidationRules::REQUIRED_DATE,
            'assessment_id' => ScheduledEmailValidationRules::REQUIRED_INTEGER,
            'group_id' => ScheduledEmailValidationRules::REQUIRED_INTEGER,
            'member_id' => ScheduledEmailValidationRules::REQUIRED_INTEGER
        ]);

        // Lookup member by email
        $member = Member::whereRaw('LOWER(TRIM(email)) = ?', [trim(strtolower($validated['recipient_email']))])->first();
        if (!$member) {
            return response()->json(['message' => 'No member found for recipient_email: ' . $validated['recipient_email']], 422);
        }
        $memberId = $member->id;

        // Parse send_at as UTC (frontend sends UTC ISO string)
        $sendAtUtc = Carbon::parse($validated['send_at'])->setTimezone('UTC');

        // Only schedule the email and dispatch the job. Token creation and email sending will be handled by the job.
        $emailBody = $validated['body'] . "\n\n" . 'To answer the assessment, click the link below:';

        $scheduledEmail = ScheduledEmail::create([
            'recipient_email' => $validated['recipient_email'],
            'subject' => $validated['subject'],
            'body' => $emailBody,
            'send_at' => $sendAtUtc,
            'assessment_id' => $validated['assessment_id'],
            'group_id' => $validated['group_id'],
            'member_id' => $memberId,
        ]);

        // Log the scheduled email for audit
        Log::info('Scheduled email created', [
            'scheduled_email_id' => $scheduledEmail->id,
            'recipient_email' => $scheduledEmail->recipient_email,
            'assessment_id' => $scheduledEmail->assessment_id,
            'group_id' => $scheduledEmail->group_id,
            'member_id' => $scheduledEmail->member_id,
            'send_at_utc' => $scheduledEmail->send_at,
        ]);

        // Queue the assessment email to be sent at the scheduled time
        try {
            // Dispatch job instance and delay to send_at
            dispatch(new \App\Jobs\SendScheduledAssessmentEmail($scheduledEmail->id))->delay($sendAtUtc);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to schedule email: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Email scheduled successfully',
            'data' => array_merge(
                $scheduledEmail->toArray(),
                ['member_id' => $memberId]
            )
        ], 201);
    }


    public function show(Request $request)
    {
        $assessmentId = $request->query('assessment_id');
        $result = [
            'scheduled' => false,
            'schedule' => null,
            'assessment' => null,
            'emails' => [],
            'groups_with_members' => [],
            'members_with_details' => [],
        ];

        if ($assessmentId) {
            // If scheduling tables have been removed (refactor/migration), avoid throwing SQL errors
            if (!Schema::hasTable('assessment_schedules')) {
                Log::warning('Attempted to fetch schedule but table assessment_schedules does not exist', ['assessment_id' => $assessmentId]);
                $result['assessment'] = DB::table('assessments')->where('id', $assessmentId)->first();
                return response()->json($result);
            }
            [$schedule, $assessment] = $this->fetchScheduleAndAssessment($assessmentId);

            if ($schedule) {
                $result['scheduled'] = true;
                $result['schedule'] = $schedule;
                $result['assessment'] = $assessment;

                $result['emails'] = $this->fetchScheduledEmails($assessmentId);
                $result['notifications'] = $this->fetchNotificationsForAssessment($assessmentId, $assessment);

                $result['groups_with_members'] = $this->buildGroupsWithMembersFromSchedule($schedule);
                $result['members_with_details'] = $this->buildMembersWithDetailsFromSchedule($schedule);
            }
        }
        // Fallback: check ScheduledEmail by recipient_email if provided
        $recipientEmail = $request->query('recipient_email');
        if ($recipientEmail) {
            $scheduled = ScheduledEmail::where('recipient_email', $recipientEmail)->first();
            if ($scheduled) {
                return response()->json(['scheduled' => true, 'data' => $scheduled]);
            }
        }

        return response()->json($result);
    }

    private function fetchScheduleAndAssessment($assessmentId)
    {
        $schedule = DB::table('assessment_schedules')->where('assessment_id', $assessmentId)->first();
        $assessment = DB::table('assessments')->where('id', $assessmentId)->first();
        return [$schedule, $assessment];
    }

    private function fetchScheduledEmails($assessmentId)
    {
        if (! Schema::hasTable('scheduled_emails')) {
            Log::warning('scheduled_emails table missing when fetching schedule', ['assessment_id' => $assessmentId]);
            return [];
        }

        return DB::table('scheduled_emails')
            ->where('assessment_id', $assessmentId)
            ->get();
    }

    private function fetchNotificationsForAssessment($assessmentId, $assessment)
    {
        $notifications = [];
        if (! Schema::hasTable('notifications') || ! $assessment) {
            return $notifications;
        }

        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                $notifications = DB::table('notifications')
                    ->where('type', 'App\\Notifications\\AssessmentInvitation')
                    ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.assessment_id')) = ?", [(string)$assessmentId])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $notifications = DB::table('notifications')
                    ->where('type', 'App\\Notifications\\AssessmentInvitation')
                    ->where('data', 'like', '%' . $assessmentId . '%')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } catch (\Exception $e) {
            Log::warning('Failed to query notifications for assessment schedule show', ['assessment_id' => $assessmentId, 'error' => $e->getMessage()]);
            $notifications = [];
        }

        return $notifications;
    }

    private function buildGroupsWithMembersFromSchedule($schedule): array
    {
        $groupsWithMembers = [];
        $groupIds = json_decode($schedule->group_ids, true) ?: [];

        if (empty($groupIds)) {
            return $groupsWithMembers;
        }

        $groups = Group::whereIn('id', $groupIds)
            ->with(['members' => function ($query) {
                $query->with('memberRoles');
            }])
            ->get();

        foreach ($groups as $group) {
            $groupsWithMembers[] = [
                'id' => $group->id,
                'name' => $group->name,
                'members' => $group->members->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'name' => trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: 'Unknown',
                        'email' => $member->email,
                        'member_roles' => $member->memberRoles->map(function ($role) {
                            return [
                                'id' => $role->id,
                                'name' => $role->name,
                            ];
                        }),
                    ];
                }),
            ];
        }

        return $groupsWithMembers;
    }

    private function buildMembersWithDetailsFromSchedule($schedule): array
    {
        $membersWithDetails = [];
        $memberIds = json_decode($schedule->member_ids, true) ?: [];

        if (empty($memberIds)) {
            return $membersWithDetails;
        }

        $members = Member::whereIn('id', $memberIds)
            ->with(['memberRoles', 'groups'])
            ->get();

        foreach ($members as $member) {
            $membersWithDetails[] = [
                'id' => $member->id,
                'name' => trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: 'Unknown',
                'email' => $member->email,
                'groups' => $member->groups->map(function ($group) {
                    return [
                        'id' => $group->id,
                        'name' => $group->name,
                    ];
                }),
                'member_roles' => $member->memberRoles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                    ];
                }),
            ];
        }

        return $membersWithDetails;
    }
}
