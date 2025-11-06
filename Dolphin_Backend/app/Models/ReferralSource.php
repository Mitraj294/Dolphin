<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralSource extends Model
{
    use HasFactory;

    protected $table = 'referral_sources';

    protected $fillable = [
        'name',
    ];

    /**
     * Get all organizations with this referral source.
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }

    /**
     * Get all leads with this referral source.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get all users with this referral source.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
