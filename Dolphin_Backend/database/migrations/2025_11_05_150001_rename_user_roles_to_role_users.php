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
     * Rename user_roles to role_users and add id column
     */
    public function up(): void
    {
        // Check if user_roles table exists
        if (Schema::hasTable('user_roles')) {
            // First, drop foreign key constraints if they exist
            Schema::table('user_roles', function (Blueprint $table) {
                // Drop foreign keys
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $foreignKeys = $sm->listTableForeignKeys('user_roles');

                foreach ($foreignKeys as $foreignKey) {
                    $table->dropForeign($foreignKey->getName());
                }
            });

            // Rename the table
            Schema::rename('user_roles', 'role_users');

            // Modify the table structure
            Schema::table('role_users', function (Blueprint $table) {
                // Drop the composite primary key
                $table->dropPrimary(['user_id', 'role_id']);
            });

            // Add new columns in a separate statement
            Schema::table('role_users', function (Blueprint $table) {
                // Add id as primary key at the beginning
                DB::statement('ALTER TABLE role_users ADD id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST');

                // Drop soft deletes if exists (as it's not in the new structure)
                if (Schema::hasColumn('role_users', 'deleted_at')) {
                    $table->dropColumn('deleted_at');
                }
            });

            // Re-add foreign keys in final statement
            Schema::table('role_users', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('role_users')) {
            // Remove id column and restore composite primary key
            Schema::table('role_users', function (Blueprint $table) {
                $table->dropColumn('id');
                $table->primary(['user_id', 'role_id']);
                $table->softDeletes();
            });

            // Rename back to user_roles
            Schema::rename('role_users', 'user_roles');
        }
    }
};
