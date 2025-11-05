<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop answer and question pivot tables if they exist. These are being removed
        // because assessments will be standalone and will not reference organization-level questions.
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('assessment_question_answers');
        Schema::dropIfExists('assessment_question');
        Schema::dropIfExists('organization_assessment_questions');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Recreate minimal organization_assessment_questions table to allow rollback.
        if (! Schema::hasTable('organization_assessment_questions')) {
            Schema::create('organization_assessment_questions', function (Blueprint $table) {
                $table->id();
                $table->string('text');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('assessment_question')) {
            Schema::create('assessment_question', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('assessment_id');
                $table->unsignedBigInteger('question_id');
                $table->timestamps();
                $table->foreign('assessment_id')->references('id')->on('organization_assessments')->onDelete('cascade');
                $table->foreign('question_id')->references('id')->on('organization_assessment_questions')->onDelete('cascade');
                $table->unique(['assessment_id', 'question_id']);
            });
        }

        if (! Schema::hasTable('assessment_question_answers')) {
            Schema::create('assessment_question_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assessment_id')->constrained('organization_assessments')->onDelete('cascade');
                $table->unsignedBigInteger('organization_assessment_question_id');
                $table->foreign('organization_assessment_question_id', 'aqa_org_q_id_fk')
                    ->references('id')->on('organization_assessment_questions')->onDelete('cascade');
                $table->foreignId('assessment_question_id')->constrained('assessment_question')->onDelete('cascade');
                $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
                $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
                $table->text('answer');
                $table->timestamps();
                $table->unique(['assessment_id', 'organization_assessment_question_id', 'member_id', 'group_id'], 'aqa_unique');
            });
        }
    }
};
