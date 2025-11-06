<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_users';
    protected $fillable = ['user_id', 'role_id'];
    public $timestamps = true;

    /**
     * Get the user that owns the role assignment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
