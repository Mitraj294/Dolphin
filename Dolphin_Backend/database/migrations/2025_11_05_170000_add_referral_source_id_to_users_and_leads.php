<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add referral_source_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('referral_source_id')->nullable()->after('find_us');
            $table->foreign('referral_source_id')->references('id')->on('referral_sources')->onDelete('set null');
        });

        // Migrate existing find_us data to referral_source_id for users
        DB::statement("
            UPDATE users u
            LEFT JOIN referral_sources rs ON LOWER(TRIM(u.find_us)) = LOWER(TRIM(rs.name))
            SET u.referral_source_id = rs.id
            WHERE u.find_us IS NOT NULL AND u.find_us != ''
        ");

        // Add referral_source_id to leads table
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('referral_source_id')->nullable()->after('find_us');
            $table->foreign('referral_source_id')->references('id')->on('referral_sources')->onDelete('set null');
        });

        // Migrate existing find_us data to referral_source_id for leads
        DB::statement("
            UPDATE leads l
            LEFT JOIN referral_sources rs ON LOWER(TRIM(l.find_us)) = LOWER(TRIM(rs.name))
            SET l.referral_source_id = rs.id
            WHERE l.find_us IS NOT NULL AND l.find_us != ''
        ");

        // Drop find_us columns after migration
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('find_us');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('find_us');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore find_us columns
        Schema::table('users', function (Blueprint $table) {
            $table->string('find_us')->nullable()->after('phone');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->string('find_us')->nullable()->after('phone');
        });

        // Migrate referral_source_id back to find_us for users
        DB::statement("
            UPDATE users u
            LEFT JOIN referral_sources rs ON u.referral_source_id = rs.id
            SET u.find_us = rs.name
            WHERE u.referral_source_id IS NOT NULL
        ");

        // Migrate referral_source_id back to find_us for leads
        DB::statement("
            UPDATE leads l
            LEFT JOIN referral_sources rs ON l.referral_source_id = rs.id
            SET l.find_us = rs.name
            WHERE l.referral_source_id IS NOT NULL
        ");

        // Drop referral_source_id columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referral_source_id']);
            $table->dropColumn('referral_source_id');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['referral_source_id']);
            $table->dropColumn('referral_source_id');
        });
    }
};
