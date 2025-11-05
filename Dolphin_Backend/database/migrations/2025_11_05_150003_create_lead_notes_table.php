<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create lead_notes table for storing notes about leads
     */
    public function up(): void
    {
        if (!Schema::hasTable('lead_notes')) {
            Schema::create('lead_notes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id');
                $table->text('note');
                $table->timestamp('note_date');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

                // Indexes
                $table->index('lead_id');
                $table->index('created_by');
                $table->index('note_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_notes');
    }
};
