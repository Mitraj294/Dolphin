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
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            // rename phone -> phone_number
            if (Schema::hasColumn('users', 'phone') && !Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('email');
            }

            // add new fields
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'force_password_change')) {
                $table->boolean('force_password_change')->default(false)->after('deleted_at');
            }
            if (!Schema::hasColumn('users', 'stripe_id')) {
                $table->string('stripe_id')->nullable()->after('force_password_change');
            }
            if (!Schema::hasColumn('users', 'pm_type')) {
                $table->string('pm_type')->nullable()->after('stripe_id');
            }
            if (!Schema::hasColumn('users', 'pm_last_four')) {
                $table->string('pm_last_four', 4)->nullable()->after('pm_type');
            }
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('pm_last_four');
            }
        });

        try {
            if (Schema::hasColumn('users', 'phone') && Schema::hasColumn('users', 'phone_number')) {
                DB::statement("UPDATE users SET phone_number = phone WHERE (phone_number IS NULL OR phone_number = '');");
            }
        } catch (\Exception $e) {
            // ignore
        }

        // Drop foreign keys first (best-effort), then drop legacy columns
        try {
            Schema::table('users', function (Blueprint $table) {
                try {
                    $table->dropForeign(['organization_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign users.organization_id: ' . $e->getMessage());
                }
                try {
                    $table->dropForeign(['country_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign users.country_id: ' . $e->getMessage());
                }
                try {
                    $table->dropForeign(['state_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign users.state_id: ' . $e->getMessage());
                }
                try {
                    $table->dropForeign(['city_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign users.city_id: ' . $e->getMessage());
                }
                try {
                    $table->dropForeign(['referral_source_id']);
                } catch (\Exception $e) {
                    Log::warning('dropForeign users.referral_source_id: ' . $e->getMessage());
                }
            });

            $possibleFks = [
                'users_organization_id_foreign',
                'users_country_id_foreign',
                'users_state_id_foreign',
                'users_city_id_foreign',
                'users_referral_source_id_foreign',
            ];
            foreach ($possibleFks as $fk) {
                try {
                    DB::statement("ALTER TABLE `users` DROP FOREIGN KEY `$fk`");
                } catch (\Exception $e) { /* ignore */
                }
            }
        } catch (\Exception $e) {
            // ignore
        }

        $legacy = ['organization_id', 'referral_source_id', 'address', 'country_id', 'state_id', 'city_id', 'zip', 'phone'];
        foreach ($legacy as $col) {
            if (Schema::hasColumn('users', $col)) {
                try {
                    Schema::table('users', function (Blueprint $table) use ($col) {
                        $table->dropColumn($col);
                    });
                } catch (\Exception $e) {
                    // ignore
                }
            }
        }
    }

    public function down(): void
    {
        // intentionally minimal reverse
    }
};
