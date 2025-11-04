<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory, SoftDeletes;
    // Normalized schema: store question_id and user's email. Keep 'question' was
    // used historically but we remove it from fillable now that DB uses question_id.
    protected $fillable = ['user_id', 'question_id', 'answer', 'email'];
}
