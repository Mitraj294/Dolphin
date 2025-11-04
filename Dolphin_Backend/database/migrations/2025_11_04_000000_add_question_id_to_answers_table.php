<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('answers') && ! Schema::hasColumn('answers', 'question_id')) {
            Schema::table('answers', function (Blueprint $table) {
                $table->unsignedBigInteger('question_id')->nullable()->after('question');
                $table->foreign('question_id')->references('id')->on('questions')->onDelete('set null');
            });

            // Populate question_id for existing rows by matching the question text
            DB::statement(
                <<<SQL
UPDATE answers
JOIN questions ON answers.question = questions.question
SET answers.question_id = questions.id;
SQL
            );

            // Optionally, if all rows now have question_id you could make the column not nullable.
            // We'll leave it nullable for safety.
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('answers') && Schema::hasColumn('answers', 'question_id')) {
            Schema::table('answers', function (Blueprint $table) {
                $table->dropForeign(['question_id']);
                $table->dropColumn('question_id');
            });
        }
    }
};
