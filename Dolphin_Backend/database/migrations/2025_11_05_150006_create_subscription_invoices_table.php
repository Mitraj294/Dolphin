<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create subscription_invoices table for tracking invoice details
     */
    public function up(): void
    {
        if (!Schema::hasTable('subscription_invoices')) {
            Schema::create('subscription_invoices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('subscription_id');
                $table->string('stripe_invoice_id');
                $table->string('amount_due');
                $table->string('amount_paid');
                $table->string('currency');
                $table->enum('status', ['paid', 'open', 'uncollectible']);
                $table->date('due_date')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->text('hosted_invoice_url')->nullable();
                $table->timestamps();

                // Foreign key
                $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');

                // Indexes
                $table->index('subscription_id');
                $table->index('stripe_invoice_id');
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_invoices');
    }
};
