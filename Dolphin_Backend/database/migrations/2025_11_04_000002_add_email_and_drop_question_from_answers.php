<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('answers')) {
            Schema::table('answers', function (Blueprint $table) {
                if (! Schema::hasColumn('answers', 'email')) {
                    $table->string('email')->nullable()->after('user_id');
                }
            });

            // Populate email from users table
            DB::statement(
                <<<SQL
UPDATE answers
LEFT JOIN users ON answers.user_id = users.id
SET answers.email = users.email
WHERE users.email IS NOT NULL;
SQL
            );

            // Now remove the redundant 'question' column if it exists
            if (Schema::hasColumn('answers', 'question')) {
                Schema::table('answers', function (Blueprint $table) {
                    $table->dropColumn('question');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('answers')) {
            // Add the 'question' column back (nullable) and try to repopulate from questions
            Schema::table('answers', function (Blueprint $table) {
                if (! Schema::hasColumn('answers', 'question')) {
                    $table->string('question')->nullable()->after('user_id');
                }
            });

            // Populate question text from questions table via question_id
            DB::statement(
                <<<SQL
UPDATE answers
LEFT JOIN questions ON answers.question_id = questions.id
SET answers.question = questions.question
WHERE answers.question IS NULL AND questions.question IS NOT NULL;
SQL
            );

            // Drop email column
            if (Schema::hasColumn('answers', 'email')) {
                Schema::table('answers', function (Blueprint $table) {
                    $table->dropColumn('email');
                });
            }
        }
    }
};
