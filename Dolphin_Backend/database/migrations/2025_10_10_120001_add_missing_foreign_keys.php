<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public const ON_DELETE_SET_NULL = 'set null';

    public function up(): void
    {
        // Consolidated helper calls to keep cognitive complexity low
        $this->addFkIfMissing('announcement_admin', 'announcement_id', 'announcements', 'cascade');
        $this->addFkIfMissing('announcement_group', 'announcement_id', 'announcements', 'cascade');
        $this->addFkIfMissing('announcement_organization', 'announcement_id', 'announcements', 'cascade');

        $this->addFkIfMissing('user_details', 'city_id', 'cities', self::ON_DELETE_SET_NULL);
        $this->addFkIfMissing('user_details', 'country_id', 'countries', self::ON_DELETE_SET_NULL);
        $this->addFkIfMissing('user_details', 'state_id', 'states', self::ON_DELETE_SET_NULL);
    }

    public function down(): void
    {
        // Remove FKs we may have added; log any errors rather than silently swallowing them.
        try {
            Schema::table('announcement_admin', function (Blueprint $table) {
                $table->dropForeign(['announcement_id']);
            });

            Schema::table('announcement_group', function (Blueprint $table) {
                $table->dropForeign(['announcement_id']);
            });

            Schema::table('announcement_organization', function (Blueprint $table) {
                $table->dropForeign(['announcement_id']);
            });

            Schema::table('user_details', function (Blueprint $table) {
                if (Schema::hasColumn('user_details', 'city_id')) {
                    $table->dropForeign(['city_id']);
                }
                if (Schema::hasColumn('user_details', 'country_id')) {
                    $table->dropForeign(['country_id']);
                }
                if (Schema::hasColumn('user_details', 'state_id')) {
                    $table->dropForeign(['state_id']);
                }
            });
        } catch (\Exception $e) {
            Log::warning('Error removing foreign keys during migration down: ' . $e->getMessage());
        }
    }

    /**
     * Add a foreign key if it does not already exist.
     * Uses information_schema to detect presence of existing constraints.
     */
    private function addFkIfMissing(string $tableName, string $columnName, string $referencedTable, string $onDelete = 'cascade'): void
    {
        try {
            if (! Schema::hasTable($tableName) || ! Schema::hasTable($referencedTable)) {
                return;
            }

            if (! Schema::hasColumn($tableName, $columnName)) {
                return;
            }

            $exists = DB::select("SELECT COUNT(*) as cnt FROM information_schema.key_column_usage 
                WHERE table_schema = DATABASE() 
                AND table_name = ? 
                AND column_name = ? 
                AND referenced_table_name = ?", [$tableName, $columnName, $referencedTable]);

            if (empty($exists) || $exists[0]->cnt != 0) {
                return;
            }

            Schema::table($tableName, function (Blueprint $table) use ($columnName, $referencedTable, $onDelete) {
                $table->foreign($columnName)->references('id')->on($referencedTable)->onDelete($onDelete);
            });
        } catch (\Exception $e) {
            Log::warning("Skipping FK for {$tableName}.{$columnName} -> {$referencedTable}: " . $e->getMessage());
        }
    }
};
