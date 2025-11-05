<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    /**
     * Helper to check if a foreign key exists before dropping it
     */
    private function dropForeignKeyIfExists(string $table, string $foreignKey): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $foreignKeys = DB::select(
            "SELECT CONSTRAINT_NAME 
             FROM information_schema.TABLE_CONSTRAINTS 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = ? 
             AND CONSTRAINT_NAME = ?
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$table, $foreignKey]
        );

        if (!empty($foreignKeys)) {
            Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey);
            });
        }
    }

    public function up(): void
    {
        // First, migrate data from group_member to a new group_user table
        // We need to get user_id from members table before we drop it

        if (Schema::hasTable('group_member') && !Schema::hasTable('group_user')) {
            // Create the new group_user table
            Schema::create('group_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('role')->nullable();
                $table->timestamps();
                $table->unique(['group_id', 'user_id']);
            });

            // Migrate data: join group_member with members to get user_id
            if (Schema::hasTable('members')) {
                DB::statement("
                    INSERT INTO group_user (group_id, user_id, role, created_at, updated_at)
                    SELECT 
                        gm.group_id, 
                        m.user_id,
                        'member' as role,
                        gm.created_at,
                        gm.updated_at
                    FROM group_member gm
                    INNER JOIN members m ON gm.member_id = m.id
                    WHERE m.user_id IS NOT NULL
                    ON DUPLICATE KEY UPDATE
                        role = VALUES(role),
                        updated_at = VALUES(updated_at)
                ");
            }

            // Drop old group_member table
            Schema::dropIfExists('group_member');
        }

        // Drop foreign key constraints before dropping member-related tables
        // Check if foreign key exists before attempting to drop
        $this->dropForeignKeyIfExists('scheduled_emails', 'scheduled_emails_member_id_foreign');
        $this->dropForeignKeyIfExists('assessment_answer_links', 'assessment_answer_links_member_id_foreign');
        $this->dropForeignKeyIfExists('assessment_answer_tokens', 'assessment_answer_tokens_member_id_foreign');

        // Drop member-related tables
        Schema::dropIfExists('member_member_role');
        Schema::dropIfExists('member_roles');
        Schema::dropIfExists('members');
    }
    public function down(): void
    {
        // Recreate members table
        if (!Schema::hasTable('members')) {
            Schema::create('members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
                $table->foreignId('user_id')->nullable();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->unique(['email', 'deleted_at']);
            });
        }

        // Recreate member_roles table
        if (!Schema::hasTable('member_roles')) {
            Schema::create('member_roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        // Recreate member_member_role pivot table
        if (!Schema::hasTable('member_member_role')) {
            Schema::create('member_member_role', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('member_id');
                $table->unsignedBigInteger('member_role_id');
                $table->timestamps();
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->foreign('member_role_id')->references('id')->on('member_roles')->onDelete('cascade');
                $table->unique(['member_id', 'member_role_id']);
            });
        }

        // Recreate group_member table
        if (!Schema::hasTable('group_member')) {
            Schema::create('group_member', function (Blueprint $table) {
                $table->id();
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
                $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Drop group_user table
        Schema::dropIfExists('group_user');
    }
};
