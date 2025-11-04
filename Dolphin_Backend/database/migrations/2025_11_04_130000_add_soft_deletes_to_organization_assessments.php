<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('organization_assessments') && ! Schema::hasColumn('organization_assessments', 'deleted_at')) {
            Schema::table('organization_assessments', function (Blueprint $table) {
                $table->softDeletes()->after('updated_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('organization_assessments') && Schema::hasColumn('organization_assessments', 'deleted_at')) {
            Schema::table('organization_assessments', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
