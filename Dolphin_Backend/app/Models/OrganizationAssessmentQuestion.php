<?php

namespace App\Models;

// DEPRECATED: The 'organization_assessment_questions' table does not exist in the database.
// Questions are now part of the assessment form_definition JSON field in the 'assessment' table.
// Use Assessment model instead for new implementations.
// This model is kept only for backwards compatibility reference.

/*
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationAssessmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
    ];
}
*/
