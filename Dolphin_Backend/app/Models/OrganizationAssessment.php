<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\AssessmentQuestion;
use App\Models\User;
use App\Models\Organization;

class OrganizationAssessment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'organization_assessments';

    protected $fillable = [
        'user_id',
        'organization_id',
        'name',
        'date',
        'time',
    ];

    // Relationship to the creator user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to organization
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Get users assigned to this assessment via organization_assessment_member pivot
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'organization_assessment_member', 'organization_assessment_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get groups assigned to this assessment via organization_assessment_group pivot
     */
    public function assignedGroups()
    {
        return $this->belongsToMany(Group::class, 'organization_assessment_group', 'organization_assessment_id', 'group_id')
            ->withTimestamps();
    }

    /**
     * Get all users in assigned groups (via groups)
     */
    public function allParticipants()
    {
        // Get users directly assigned
        $directUsers = $this->assignedUsers()->get();

        // Get users from assigned groups
        $groupUsers = collect();
        foreach ($this->assignedGroups as $group) {
            $groupUsers = $groupUsers->merge($group->users);
        }

        // Merge and remove duplicates
        return $directUsers->merge($groupUsers)->unique('id');
    }

    // NOTE: Question-related relationships removed: assessments are standalone records now.
}
