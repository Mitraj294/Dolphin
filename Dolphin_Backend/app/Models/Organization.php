<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organization extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'size',
        'referral_source_id',
        'referral_other_text',
        'contract_start',
        'contract_end',
        'sales_person_id',
        'last_contacted',
        'certified_staff',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'contract_start' => 'date',
        'contract_end' => 'date',
        'last_contacted' => 'datetime',
        'certified_staff' => 'integer',
    ];

    /**
     * Get the primary user (owner) associated with the organization.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sales person associated with the organization.
     */
    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    /**
     * Get the referral source associated with the organization.
     */
    public function referralSource(): BelongsTo
    {
        return $this->belongsTo(ReferralSource::class);
    }

    /**
     * Get all users belonging to the organization.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    /**
     * Get the active subscription for this organization through its owner.
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'user_id', 'user_id')
            ->where('status', 'active')
            ->orderBy('ends_at', 'desc');
    }

    /**
     * Get organization users (enhanced relationship with status) via organization_users pivot
     */
    public function organizationUsers()
    {
        return $this->belongsToMany(User::class, 'organization_users', 'organization_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps()
            ->using(OrganizationUser::class);
    }

    /**
     * Get organization members (simple membership) via organization_member pivot
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'organization_member', 'organization_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get all groups belonging to this organization
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'organization_id');
    }

    /**
     * Get all assessments for this organization
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(OrganizationAssessment::class, 'organization_id');
    }

    /**
     * Get the organization's address.
     */
    public function address(): HasOne
    {
        return $this->hasOne(OrganizationAddress::class);
    }
}
