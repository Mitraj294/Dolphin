<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create referral_sources table for tracking how leads found us
     */
    public function up(): void
    {
        if (!Schema::hasTable('referral_sources')) {
            Schema::create('referral_sources', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();

                // Index for name searches
                $table->index('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_sources');
    }
};
