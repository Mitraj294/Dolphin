<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\AssessmentQuestion;
use App\Models\User;
use App\Models\Organization;

class OrganizationAssessment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'organization_assessments';

    protected $fillable = [
        'user_id',
        'organization_id',
        'name',
        'date',
        'time',
    ];

    // Relationship to the creator user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to organization
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // Relationship to assessment questions (pivot entries)
    public function assessmentQuestions(): HasMany
    {
        return $this->hasMany(AssessmentQuestion::class, 'assessment_id');
    }

    // Many-to-many convenience relationship to actual question records
    public function questions()
    {
        return $this->belongsToMany(OrganizationAssessmentQuestion::class, 'assessment_question', 'assessment_id', 'question_id');
    }
}
