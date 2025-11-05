<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Restructure subscriptions table to match new requirements
     */
    public function up(): void
    {
        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                // Drop columns that are no longer needed
                $columnsToCheck = [
                    'plan',
                    'payment_method',
                    'payment_date',
                    'subscription_start',
                    'subscription_end',
                    'amount',
                    'receipt_url',
                    'invoice_number',
                    'description',
                    'customer_name',
                    'customer_email',
                    'customer_country',
                    'default_payment_method_id',
                    'payment_method_type',
                    'payment_method_brand',
                    'payment_method_last4',
                    'meta'
                ];

                foreach ($columnsToCheck as $column) {
                    if (Schema::hasColumn('subscriptions', $column)) {
                        $table->dropColumn($column);
                    }
                }

                // Add new columns
                if (!Schema::hasColumn('subscriptions', 'plan_id')) {
                    $table->unsignedBigInteger('plan_id')->after('user_id');
                }

                // Modify status to be enum
                if (Schema::hasColumn('subscriptions', 'status')) {
                    $table->dropColumn('status');
                }
                $table->enum('status', ['active', 'canceled', 'incomplete', 'past_due'])
                    ->after('stripe_customer_id');

                // Add new timestamp columns
                if (!Schema::hasColumn('subscriptions', 'trial_ends_at')) {
                    $table->timestamp('trial_ends_at')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'ends_at')) {
                    $table->timestamp('ends_at')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'started_at')) {
                    $table->timestamp('started_at')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'current_period_end')) {
                    $table->timestamp('current_period_end')->nullable();
                }

                // Add boolean flags
                if (!Schema::hasColumn('subscriptions', 'cancel_at_period_end')) {
                    $table->boolean('cancel_at_period_end')->default(false);
                }
                if (!Schema::hasColumn('subscriptions', 'is_paused')) {
                    $table->boolean('is_paused')->default(false);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                // Remove new columns
                $columnsToCheck = [
                    'plan_id',
                    'trial_ends_at',
                    'ends_at',
                    'started_at',
                    'current_period_end',
                    'cancel_at_period_end',
                    'is_paused'
                ];

                foreach ($columnsToCheck as $column) {
                    if (Schema::hasColumn('subscriptions', $column)) {
                        $table->dropColumn($column);
                    }
                }

                // Restore old columns
                $table->string('plan')->nullable();
                $table->string('payment_method')->nullable();
                $table->dateTime('payment_date')->nullable();
                $table->dateTime('subscription_start')->nullable();
                $table->dateTime('subscription_end')->nullable();
                $table->decimal('amount', 10, 2)->nullable();
                $table->string('receipt_url')->nullable();
                $table->string('invoice_number')->nullable();
                $table->text('description')->nullable();
                $table->string('customer_name')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_country')->nullable();

                // Restore status as string
                if (Schema::hasColumn('subscriptions', 'status')) {
                    $table->dropColumn('status');
                }
                $table->string('status')->nullable();
            });
        }
    }
};
