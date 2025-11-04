<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Rename 'question' column to 'text' in questions table
     * to match the Question model expectations.
     */
    public function up(): void
    {
        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'question')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->renameColumn('question', 'text');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'text')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->renameColumn('text', 'question');
            });
        }
    }
};
