<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentResult extends Model
{
    protected $fillable = [
        'organization_assessment_id',
        'user_id',
        'type',
        'self_a',
        'conc_a',
        'task_a',
        'adj_a',
        'self_b',
        'conc_b',
        'task_b',
        'adj_b',
        'self_c',
        'conc_c',
        'task_c',
        'adj_c',
        'self_d',
        'conc_d',
        'task_d',
        'adj_d',
        'self_avg',
        'conc_avg',
        'adj_avg',
        'task_avg',
        'dec_approach',
        'self_total_count',
        'conc_total_count',
        'adj_total_count',
        'self_total_words',
        'conc_total_words',
        'adj_total_words',
    ];

    protected $casts = [
        'self_a' => 'double',
        'conc_a' => 'double',
        'task_a' => 'double',
        'adj_a' => 'double',
        'self_b' => 'double',
        'conc_b' => 'double',
        'task_b' => 'double',
        'adj_b' => 'double',
        'self_c' => 'double',
        'conc_c' => 'double',
        'task_c' => 'double',
        'adj_c' => 'double',
        'self_d' => 'double',
        'conc_d' => 'double',
        'task_d' => 'double',
        'adj_d' => 'double',
        'self_avg' => 'double',
        'conc_avg' => 'double',
        'adj_avg' => 'double',
        'task_avg' => 'double',
        'dec_approach' => 'double',
        'self_total_count' => 'integer',
        'conc_total_count' => 'integer',
        'adj_total_count' => 'integer',
        'self_total_words' => 'array',
        'conc_total_words' => 'array',
        'adj_total_words' => 'array',
    ];

    /**
     * Get the organization assessment that owns the result.
     */
    public function organizationAssessment(): BelongsTo
    {
        return $this->belongsTo(OrganizationAssessment::class);
    }

    /**
     * Get the user that owns the result.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
