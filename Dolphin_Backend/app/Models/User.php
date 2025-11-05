<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\HasRoles;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, SoftDeletes, Notifiable, HasRoles;


    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'organization_id',
        'email',
        'password',
        'phone',
        'referral_source_id',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'zip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Eloquent relationships
    /**
     * Get the country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the state.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the city.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the referral source.
     */
    public function referralSource()
    {
        return $this->belongsTo(ReferralSource::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    // Organization owned by this user (owner)
    public function organization()
    {
        return $this->hasOne(Organization::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Convenience: is the user a superadmin?
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Get all groups this user belongs to
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    /**
     * Get organizations this user is linked to via organization_users (with status)
     */
    public function organizationUsers()
    {
        return $this->belongsToMany(Organization::class, 'organization_users', 'user_id', 'organization_id')
            ->withPivot('status')
            ->withTimestamps()
            ->using(OrganizationUser::class);
    }

    /**
     * Get organizations this user is a member of via organization_member
     */
    public function organizationMemberships()
    {
        return $this->belongsToMany(Organization::class, 'organization_member', 'user_id', 'organization_id')
            ->withTimestamps();
    }

    /**
     * Get the organization this user belongs to via organization_id foreign key
     */
    public function belongsToOrganization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Get assessments assigned to this user
     */
    public function assignedAssessments()
    {
        return $this->belongsToMany(OrganizationAssessment::class, 'organization_assessment_member', 'user_id', 'organization_assessment_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}
