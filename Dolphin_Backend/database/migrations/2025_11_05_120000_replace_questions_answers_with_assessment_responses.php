<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Replace questions/answers tables with assessment/assessment_responses
     * matching the structure from dolphin_4 database
     */
    public function up(): void
    {
        // Drop old tables
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');

        // Create assessment table (replacing questions)
        if (! Schema::hasTable('assessment')) {
            Schema::create('assessment', function (Blueprint $table) {
                $table->id();
                $table->string('title')->index();
                $table->text('description')->nullable();
                $table->longText('form_definition')->nullable(); // JSON array of options
                $table->timestamps();
            });
        }

        // Create assessment_responses table (replacing answers)
        if (! Schema::hasTable('assessment_responses')) {
            Schema::create('assessment_responses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->unsignedBigInteger('attempt_id')->nullable()->index();
                $table->foreignId('assessment_id')->constrained('assessment')->onDelete('cascade');
                $table->longText('selected_options')->nullable(); // JSON array
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_responses');
        Schema::dropIfExists('assessment');

        // Recreate questions table
        if (! Schema::hasTable('questions')) {
            Schema::create('questions', function (Blueprint $table) {
                $table->id();
                $table->text('text');
                $table->json('options')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Recreate answers table
        if (! Schema::hasTable('answers')) {
            Schema::create('answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('email')->nullable();
                $table->unsignedBigInteger('question_id')->nullable();
                $table->text('answer');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('question_id')->references('id')->on('questions')->onDelete('set null');
            });
        }
    }
};
