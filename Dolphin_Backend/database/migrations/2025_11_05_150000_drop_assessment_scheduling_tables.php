<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Remove assessment_schedules and scheduled_emails tables
     * as we will use Laravel's notification system instead
     */
    public function up(): void
    {
        Schema::dropIfExists('assessment_schedules');
        Schema::dropIfExists('scheduled_emails');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate scheduled_emails table
        Schema::create('scheduled_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id')->nullable()->index();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->string('recipient_email');
            $table->string('subject');
            $table->text('body');
            $table->dateTime('send_at');
            $table->boolean('sent')->default(false);
            $table->timestamps();
        });

        // Recreate assessment_schedules table
        Schema::create('assessment_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->date('date');
            $table->time('time');
            $table->json('group_ids')->nullable();
            $table->json('member_ids')->nullable();
            $table->timestamps();
        });
    }
};
