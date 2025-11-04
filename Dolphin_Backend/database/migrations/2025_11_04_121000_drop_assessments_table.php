<?php

// Deprecated drop migration stub: the operation to drop the old `assessments`
// table has already been applied and we keep this file as a no-op to avoid
// accidental re-execution. The new `organization_assessments` migration is the
// authoritative source for assessment-related schema.

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // deprecated - no-op
    }

    public function down(): void
    {
        // deprecated - no-op
    }
};
