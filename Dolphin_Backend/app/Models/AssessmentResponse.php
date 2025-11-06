<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssessmentResponse extends Model
{
    use HasFactory;

    protected $table = 'assessment_responses';

    protected $fillable = [
        'user_id',
        'attempt_id',
        'assessment_id',
        'selected_options',
    ];

    protected $casts = [
        'selected_options' => 'array', // Cast JSON to array
    ];

    /**
     * Get the user who submitted this response
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assessment this response belongs to
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the timing data for this response
     */
    public function assessmentTime(): HasOne
    {
        return $this->hasOne(AssessmentTime::class);
    }
}
