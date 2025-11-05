<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'assessment';

    protected $fillable = [
        'title',
        'description',
        'form_definition',
    ];

    protected $casts = [
        'form_definition' => 'array', // Cast JSON to array
    ];

    /**
     * Get all responses for this assessment
     */
    public function responses(): HasMany
    {
        return $this->hasMany(AssessmentResponse::class, 'assessment_id');
    }
}
