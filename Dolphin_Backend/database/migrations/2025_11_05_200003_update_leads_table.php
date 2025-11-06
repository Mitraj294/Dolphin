<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('leads')) {
            return;
        }

        Schema::table('leads', function (Blueprint $table) {
            // add organization_id if missing
            if (!Schema::hasColumn('leads', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable()->after('id');
            }
            // normalize phone column
            if (Schema::hasColumn('leads', 'phone') && !Schema::hasColumn('leads', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('email');
            }
            if (!Schema::hasColumn('leads', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Try to populate organization_id from organization_name where possible
        try {
            if (Schema::hasColumn('leads', 'organization_name') && Schema::hasTable('organizations')) {
                DB::statement("UPDATE leads l JOIN organizations o ON o.name = l.organization_name SET l.organization_id = o.id WHERE (l.organization_id IS NULL OR l.organization_id = 0);");
            }
            if (Schema::hasColumn('leads', 'phone') && Schema::hasColumn('leads', 'phone_number')) {
                DB::statement("UPDATE leads SET phone_number = phone WHERE (phone_number IS NULL OR phone_number = '');");
            }
        } catch (\Exception $e) {
            // best-effort
        }

        // Drop foreign keys referencing legacy columns first (best-effort), then drop columns.
        try {
            // Attempt to drop known foreign keys using Schema dropForeign
            Schema::table('leads', function (Blueprint $table) {
                try {
                    $table->dropForeign(['referral_source_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign referral_source_id: ' . $e->getMessage());
                }
                try {
                    $table->dropForeign(['created_by']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign created_by: ' . $e->getMessage());
                }
                try {
                    $table->dropForeign(['sales_person_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign sales_person_id: ' . $e->getMessage());
                }
                try {
                    $table->dropForeign(['country_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign country_id: ' . $e->getMessage());
                }
                try {
                    $table->dropForeign(['state_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign state_id: ' . $e->getMessage());
                }
                try {
                    $table->dropForeign(['city_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign city_id: ' . $e->getMessage());
                }
            });

            // Some MySQL setups name foreign keys differently; attempt raw drops by conventional names
            $possibleFks = [
                'leads_referral_source_id_foreign',
                'leads_created_by_foreign',
                'leads_sales_person_id_foreign',
                'leads_country_id_foreign',
                'leads_state_id_foreign',
                'leads_city_id_foreign',
            ];
            foreach ($possibleFks as $fk) {
                try {
                    DB::statement("ALTER TABLE `leads` DROP FOREIGN KEY `$fk`");
                } catch (\Exception $e) {
                    // ignore if FK name not present
                }
            }
        } catch (\Exception $e) {
            // ignore any failures when attempting to drop FKs; we'll still try to drop columns below
        }

        // Now drop legacy columns that are no longer needed (wrapped in try/catch per column)
        $drops = ['referral_source_id', 'organization_name', 'organization_size', 'address', 'country_id', 'state_id', 'city_id', 'zip', 'assessment_sent_at', 'registered_at', 'created_by', 'sales_person_id', 'phone'];
        foreach ($drops as $col) {
            if (Schema::hasColumn('leads', $col)) {
                try {
                    Schema::table('leads', function (Blueprint $table) use ($col) {
                        $table->dropColumn($col);
                    });
                } catch (\Exception $e) {
                    // ignore drop failures
                }
            }
        }
    }

    public function down(): void
    {
        // No-op reverse to avoid accidental data loss
    }
};
