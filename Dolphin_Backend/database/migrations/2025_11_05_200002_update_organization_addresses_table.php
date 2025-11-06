<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('organization_addresses')) {
            return;
        }

        Schema::table('organization_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('organization_addresses', 'address_line_1')) {
                $table->string('address_line_1')->nullable()->after('organization_id');
            }
            if (!Schema::hasColumn('organization_addresses', 'address_line_2')) {
                $table->string('address_line_2')->nullable()->after('address_line_1');
            }
            if (!Schema::hasColumn('organization_addresses', 'zip_code')) {
                $table->string('zip_code')->nullable()->after('country_id');
            }
            if (!Schema::hasColumn('organization_addresses', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        try {
            if (Schema::hasColumn('organization_addresses', 'address')) {
                DB::statement("UPDATE organization_addresses SET address_line_1 = address WHERE (address_line_1 IS NULL OR address_line_1 = '');");
            }
            if (Schema::hasColumn('organization_addresses', 'zip')) {
                DB::statement("UPDATE organization_addresses SET zip_code = zip WHERE (zip_code IS NULL OR zip_code = '');");
            }
        } catch (\Exception $e) {
            // ignore
        }

        Schema::table('organization_addresses', function (Blueprint $table) {
            if (Schema::hasColumn('organization_addresses', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('organization_addresses', 'zip')) {
                $table->dropColumn('zip');
            }
        });
    }

    public function down(): void
    {
        // No-op reverse to avoid accidental data loss
    }
};
