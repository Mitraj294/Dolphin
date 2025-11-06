<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('organizations')) {
            // nothing to do — original create migration will handle if needed
            return;
        }

        // Add new columns if missing, copy data from legacy columns, then drop legacy
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('organizations', 'size')) {
                $table->string('size')->nullable()->after('name');
            }
            if (!Schema::hasColumn('organizations', 'referral_source_id')) {
                $table->unsignedBigInteger('referral_source_id')->nullable()->after('size');
            }
            if (!Schema::hasColumn('organizations', 'referral_other_text')) {
                $table->text('referral_other_text')->nullable()->after('referral_source_id');
            }
            if (!Schema::hasColumn('organizations', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Copy data from legacy columns if present
        try {
            if (Schema::hasColumn('organizations', 'organization_name')) {
                DB::statement("UPDATE organizations SET name = organization_name WHERE name IS NULL OR name = '';");
            }
            if (Schema::hasColumn('organizations', 'organization_size')) {
                DB::statement("UPDATE organizations SET size = organization_size WHERE size IS NULL OR size = '';");
            }
        } catch (\Exception $e) {
            // best-effort copy; if this fails, leave data intact
        }

        // Drop legacy columns if they exist (non-destructive check)
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'organization_name')) {
                $table->dropColumn('organization_name');
            }
            if (Schema::hasColumn('organizations', 'organization_size')) {
                $table->dropColumn('organization_size');
            }
            // keep other columns if present (sales_person_id, user_id) — not dropped here
        });
    }

    public function down(): void
    {
        // Intentionally left light-weight: do not attempt to recreate legacy columns with data.
    }
};
