<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Pivot model for organization_assessment_group table
 * Links assessments to groups
 */
class OrganizationAssessmentGroup extends Model
{
    protected $table = 'organization_assessment_group';

    protected $fillable = [
        'organization_assessment_id',
        'group_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the assessment for this assignment
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(OrganizationAssessment::class, 'organization_assessment_id');
    }

    /**
     * Get the group assigned to this assessment
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
