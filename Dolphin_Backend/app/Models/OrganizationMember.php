<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Pivot model for organization_member table
 * Simple organization membership tracking
 */
class OrganizationMember extends Model
{
    protected $table = 'organization_member';

    protected $fillable = [
        'organization_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the organization for this membership
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user for this membership
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
