<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Drop legacy assessment-related tables that are no longer used.
 * 
 * Tables being dropped:
 * - assessment_answer_links: Used for token-based assessment access (deprecated)
 * - assessment_answer_tokens: Token storage for assessment answers (deprecated)
 * - guest_links: Guest access codes for assessment/agreement links (deprecated)
 * 
 * These tables were part of an older authentication/access system that has been
 * replaced by standard user authentication via OAuth/Passport.
 * 
 * Migration created: 2025-11-05
 * Related cleanup: See CLEANUP_SUMMARY.md for full details
 */
class DropLegacyAssessmentTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Simply drop the tables - MySQL will handle foreign key constraints
        // If foreign keys exist, they'll be dropped automatically when parent table is dropped
        Schema::dropIfExists('assessment_answer_links');
        Schema::dropIfExists('assessment_answer_tokens');
        Schema::dropIfExists('guest_links');
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate assessment_answer_tokens table
        Schema::create('assessment_answer_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('member_id'); // Note: was never migrated to user_id
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('assessment_id');
            $table->index('member_id');
        });

        // Recreate assessment_answer_links table
        Schema::create('assessment_answer_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->string('token')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Recreate guest_links table
        Schema::create('guest_links', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('email');
            $table->string('type')->default('agreement'); // 'agreement', 'assessment', etc.
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->json('metadata')->nullable(); // Store additional context
            $table->timestamp('expires_at')->nullable();
            $table->boolean('used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index('code');
            $table->index('email');
            $table->index('type');
        });
    }
}
