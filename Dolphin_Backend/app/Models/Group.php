<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'organization_id', 'user_id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Get users in this group through the group_user pivot table
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Backwards compatibility - redirect members() to users()
     * @deprecated Use users() instead
     */
    public function members()
    {
        return $this->users();
    }

    /**
     * Get assessments assigned to this group
     */
    public function assessments()
    {
        return $this->belongsToMany(OrganizationAssessment::class, 'organization_assessment_group', 'group_id', 'organization_assessment_id')
            ->withTimestamps();
    }
}
