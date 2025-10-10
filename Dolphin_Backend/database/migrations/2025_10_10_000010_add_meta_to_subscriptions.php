<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subscriptions') && ! Schema::hasColumn('subscriptions', 'meta')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->json('meta')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subscriptions') && Schema::hasColumn('subscriptions', 'meta')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('meta');
            });
        }
    }
};
