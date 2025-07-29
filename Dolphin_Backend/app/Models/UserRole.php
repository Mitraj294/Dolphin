<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';
    protected $fillable = ['user_id', 'role_id'];
    public $incrementing = false;
    protected $primaryKey = null;
    public $timestamps = true;
}
