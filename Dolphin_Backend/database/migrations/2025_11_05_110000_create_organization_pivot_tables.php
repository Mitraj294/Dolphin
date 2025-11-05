<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates pivot tables for:
     * - organization_users: Links users to organizations with status tracking
     * - organization_member: Tracks organization membership (simpler than organization_users)
     * - organization_assessment_member: Links assessments to individual users with submission status
     * - organization_assessment_group: Links assessments to groups
     */
    public function up(): void
    {
        // 1. organization_users - Enhanced user-organization relationship with status
        if (!Schema::hasTable('organization_users')) {
            Schema::create('organization_users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();

                // Ensure unique organization-user combination
                $table->unique(['organization_id', 'user_id'], 'org_user_unique');
            });
        }

        // 2. organization_member - Simple organization membership tracking
        if (!Schema::hasTable('organization_member')) {
            Schema::create('organization_member', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();

                // Ensure unique organization-user combination
                $table->unique(['organization_id', 'user_id'], 'org_member_unique');
            });
        }

        // 3. organization_assessment_member - Links assessments to users with submission status
        if (!Schema::hasTable('organization_assessment_member')) {
            Schema::create('organization_assessment_member', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organization_assessment_id');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['Pending', 'Submitted'])->default('Pending');
                $table->timestamps();

                // Custom foreign key name to avoid length issues
                $table->foreign('organization_assessment_id', 'org_assess_member_fk')
                    ->references('id')->on('organization_assessments')->onDelete('cascade');

                // Index for faster lookups
                $table->index(['organization_assessment_id', 'user_id'], 'org_assess_member_idx');
            });
        }

        // 4. organization_assessment_group - Links assessments to groups
        if (!Schema::hasTable('organization_assessment_group')) {
            Schema::create('organization_assessment_group', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organization_assessment_id');
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
                $table->timestamps();

                // Custom foreign key name to avoid length issues
                $table->foreign('organization_assessment_id', 'org_assess_group_fk')
                    ->references('id')->on('organization_assessments')->onDelete('cascade');

                // Ensure unique assessment-group combination
                $table->unique(['organization_assessment_id', 'group_id'], 'org_assess_group_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_assessment_group');
        Schema::dropIfExists('organization_assessment_member');
        Schema::dropIfExists('organization_member');
        Schema::dropIfExists('organization_users');
    }
};
