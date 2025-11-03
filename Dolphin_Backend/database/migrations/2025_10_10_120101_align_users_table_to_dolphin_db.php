<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        // This migration is MySQL-specific for aligning with local dolphin_db
        // PostgreSQL doesn't need these changes as it was created from scratch
        $driver = DB::connection()->getDriverName();
        if ($driver !== 'mysql') {
            return; // Skip for non-MySQL databases
        }

        if (!Schema::hasTable('users')) {
            return;
        }

        // Step 1: Drop the 'role' column if it exists
        $this->dropColumnIfExists('users', 'role');

        // Step 2: Convert timestamp columns to datetime to match dolphin_db
        $cols = ['created_at', 'updated_at', 'deleted_at'];
        foreach ($cols as $col) {
            $this->convertTimestampToDatetime('users', $col);
        }
    }

    public function down(): void
    {
        // This migration is MySQL-specific
        $driver = DB::connection()->getDriverName();
        if ($driver !== 'mysql') {
            return; // Skip for non-MySQL databases
        }

        if (!Schema::hasTable('users')) {
            return;
        }

        // Reverse: Add role column back
        $this->addRoleIfMissing();

        // Convert datetime columns back to timestamp
        $cols = ['created_at', 'updated_at', 'deleted_at'];
        foreach ($cols as $col) {
            $this->convertDatetimeToTimestamp('users', $col);
        }
    }

    /**
     * Drop a column if it exists (best-effort, logs on failure).
     */
    private function dropColumnIfExists(string $table, string $column): void
    {
        try {
            if (Schema::hasColumn($table, $column)) {
                DB::statement("ALTER TABLE `{$table}` DROP COLUMN `{$column}`");
            }
        } catch (\Exception $e) {
            Log::warning("Failed to drop column {$table}.{$column}: " . $e->getMessage());
        }
    }

    /**
     * Convert a TIMESTAMP column to DATETIME by creating a temp column, copying data and renaming.
     */
    private function convertTimestampToDatetime(string $table, string $column): void
    {
        try {
            if (! Schema::hasColumn($table, $column)) {
                return;
            }

            $columnType = DB::select("SELECT DATA_TYPE FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                AND COLUMN_NAME = ?", [$table, $column]);

            if (!empty($columnType) && strtolower($columnType[0]->DATA_TYPE) === 'timestamp') {
                DB::statement("ALTER TABLE `{$table}` ADD COLUMN `{$column}_temp` DATETIME NULL");
                DB::statement("UPDATE `{$table}` SET `{$column}_temp` = `{$column}`");
                DB::statement("ALTER TABLE `{$table}` DROP COLUMN `{$column}`");
                DB::statement("ALTER TABLE `{$table}` CHANGE COLUMN `{$column}_temp` `{$column}` DATETIME NULL");
            }
        } catch (\Exception $e) {
            Log::warning("convertTimestampToDatetime failed for {$table}.{$column}: " . $e->getMessage());
        }
    }

    /**
     * Convert a DATETIME column back to TIMESTAMP when appropriate.
     */
    private function convertDatetimeToTimestamp(string $table, string $column): void
    {
        try {
            if (! Schema::hasColumn($table, $column)) {
                return;
            }

            $columnType = DB::select("SELECT DATA_TYPE FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                AND COLUMN_NAME = ?", [$table, $column]);

            if (!empty($columnType) && strtolower($columnType[0]->DATA_TYPE) === 'datetime') {
                DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `{$column}` TIMESTAMP NULL");
            }
        } catch (\Exception $e) {
            Log::warning("convertDatetimeToTimestamp failed for {$table}.{$column}: " . $e->getMessage());
        }
    }

    private function addRoleIfMissing(): void
    {
        try {
            if (! Schema::hasColumn('users', 'role')) {
                DB::statement("ALTER TABLE `users` ADD COLUMN `role` VARCHAR(255) NOT NULL DEFAULT 'user' AFTER `password`");
            }
        } catch (\Exception $e) {
            Log::warning('Failed to add role column: ' . $e->getMessage());
        }
    }
};
