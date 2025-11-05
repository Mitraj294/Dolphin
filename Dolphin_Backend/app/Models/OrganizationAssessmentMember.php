<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Pivot model for organization_assessment_member table
 * Links assessments to individual users with submission status tracking
 */
class OrganizationAssessmentMember extends Model
{
    protected $table = 'organization_assessment_member';

    protected $fillable = [
        'organization_assessment_id',
        'user_id',
        'status',
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
     * Get the user assigned to this assessment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
