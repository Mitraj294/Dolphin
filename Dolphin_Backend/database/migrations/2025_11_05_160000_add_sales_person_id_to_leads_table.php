<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add sales_person_id to leads table for better tracking
     */
    public function up(): void
    {
        if (Schema::hasTable('leads') && !Schema::hasColumn('leads', 'sales_person_id')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->unsignedBigInteger('sales_person_id')->nullable()->after('created_by');
                $table->foreign('sales_person_id')->references('id')->on('users')->onDelete('set null');
                $table->index('sales_person_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'sales_person_id')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropForeign(['sales_person_id']);
                $table->dropColumn('sales_person_id');
            });
        }
    }
};
