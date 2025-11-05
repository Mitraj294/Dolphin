<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentQuestion extends Model
{
    use HasFactory;

    protected $table = 'assessment_question';
    protected $fillable = ['assessment_id', 'question_id'];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    /* DEPRECATED: OrganizationAssessmentQuestion model no longer exists.
     * Questions are now stored in assessment.form_definition JSON field.
     * This relationship has been removed. Access questions via Assessment model.
     */
}
