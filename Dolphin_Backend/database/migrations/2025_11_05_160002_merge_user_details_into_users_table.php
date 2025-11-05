<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Merge user_details into users table for better data organization
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Add columns from user_details if they don't exist
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'find_us')) {
                    $table->string('find_us')->nullable()->after('phone');
                }
                if (!Schema::hasColumn('users', 'address')) {
                    $table->string('address')->nullable()->after('find_us');
                }
                if (!Schema::hasColumn('users', 'country_id')) {
                    $table->unsignedBigInteger('country_id')->nullable()->after('address');
                }
                if (!Schema::hasColumn('users', 'state_id')) {
                    $table->unsignedBigInteger('state_id')->nullable()->after('country_id');
                }
                if (!Schema::hasColumn('users', 'city_id')) {
                    $table->unsignedBigInteger('city_id')->nullable()->after('state_id');
                }
                if (!Schema::hasColumn('users', 'zip')) {
                    $table->string('zip')->nullable()->after('city_id');
                }
            });

            // Migrate data from user_details to users
            if (Schema::hasTable('user_details')) {
                DB::statement('
                    UPDATE users u
                    LEFT JOIN user_details ud ON u.id = ud.user_id
                    SET 
                        u.phone = ud.phone,
                        u.find_us = ud.find_us,
                        u.address = ud.address,
                        u.country_id = ud.country_id,
                        u.state_id = ud.state_id,
                        u.city_id = ud.city_id,
                        u.zip = ud.zip
                    WHERE ud.id IS NOT NULL
                ');
            }

            // Add foreign keys
            Schema::table('users', function (Blueprint $table) {
                if (!$this->foreignKeyExists('users', 'users_country_id_foreign')) {
                    $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
                }
                if (!$this->foreignKeyExists('users', 'users_state_id_foreign')) {
                    $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
                }
                if (!$this->foreignKeyExists('users', 'users_city_id_foreign')) {
                    $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
                }
            });
        }

        // Drop user_details table after migration
        if (Schema::hasTable('user_details')) {
            Schema::dropIfExists('user_details');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate user_details table
        if (!Schema::hasTable('user_details')) {
            Schema::create('user_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('phone')->nullable();
                $table->string('find_us')->nullable();
                $table->string('address')->nullable();
                $table->unsignedBigInteger('country_id')->nullable();
                $table->unsignedBigInteger('state_id')->nullable();
                $table->unsignedBigInteger('city_id')->nullable();
                $table->string('zip')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
                $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
                $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            });

            // Migrate data back
            DB::statement('
                INSERT INTO user_details (user_id, phone, find_us, address, country_id, state_id, city_id, zip, created_at, updated_at)
                SELECT id, phone, find_us, address, country_id, state_id, city_id, zip, created_at, updated_at
                FROM users
                WHERE phone IS NOT NULL OR address IS NOT NULL
            ');
        }

        // Remove columns from users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columnsToRemove = ['phone', 'find_us', 'address', 'country_id', 'state_id', 'city_id', 'zip'];
                foreach ($columnsToRemove as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        // Drop foreign keys first
                        if (in_array($column, ['country_id', 'state_id', 'city_id'])) {
                            try {
                                $table->dropForeign(['users_' . $column . '_foreign']);
                            } catch (\Exception $e) {
                                // Foreign key might not exist
                            }
                        }
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    /**
     * Check if a foreign key exists
     */
    private function foreignKeyExists(string $table, string $foreignKey): bool
    {
        $result = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_NAME = ?
        ", [$table, $foreignKey]);

        return count($result) > 0;
    }
};
