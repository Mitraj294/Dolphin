<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create organization_addresses table to separate address concerns
     */
    public function up(): void
    {
        // Create the organization_addresses table
        if (!Schema::hasTable('organization_addresses')) {
            Schema::create('organization_addresses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organization_id')->unique();
                $table->string('address')->nullable();
                $table->unsignedBigInteger('country_id')->nullable();
                $table->unsignedBigInteger('state_id')->nullable();
                $table->unsignedBigInteger('city_id')->nullable();
                $table->string('zip')->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
                $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
                $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');

                // Indexes
                $table->index('organization_id');
                $table->index('country_id');
                $table->index('state_id');
                $table->index('city_id');
            });
        }

        // Note: Address fields will remain in organizations table for now
        // They can be removed in a separate migration after data migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_addresses');
    }
};
