<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('organization_assessments')) {
            Schema::create('organization_assessments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('organization_id')->nullable();
                $table->string('name');
                $table->date('date')->nullable();
                $table->time('time')->nullable();
                $table->timestamps();
                $table->softDeletes();
                // add foreign keys if users/organizations tables exist at runtime
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_assessments');
    }
};
