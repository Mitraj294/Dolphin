<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledEmail extends Model
{
    use HasFactory;

    protected $table = 'scheduled_emails';

    protected $fillable = [
        'recipient_email',
        'subject',
        'body',
        'send_at',
        'sent',
        'assessment_id',
        'group_id',
        'member_id',
    ];

    protected $casts = [
        'send_at' => 'datetime',
        'sent' => 'boolean',
    ];

    /**
     * Get the assessment associated with this scheduled email.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(OrganizationAssessment::class, 'assessment_id');
    }

    /**
     * Get the group associated with this scheduled email.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the member (user) associated with this scheduled email.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    /**
     * Check if the email has been sent.
     */
    public function isSent(): bool
    {
        return $this->sent === true;
    }

    /**
     * Mark the email as sent.
     */
    public function markAsSent(): void
    {
        $this->update(['sent' => true]);
    }
}
