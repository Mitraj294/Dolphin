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
     * Add indexes for frequently queried columns to improve performance.
     */
    public function up(): void
    {
        $this->addAnnouncementIndexes();
        $this->addLeadIndexes();
        $this->addMemberIndexes();
        $this->addSubscriptionIndexes();
        $this->addAssessmentTokenIndexes();
    }

    private function addAnnouncementIndexes(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (!$this->indexExists('announcements', 'announcements_sender_id_index')) {
                $table->index('sender_id');
            }
            if (!$this->indexExists('announcements', 'announcements_scheduled_at_index')) {
                $table->index('scheduled_at');
            }
        });
    }

    private function addLeadIndexes(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!$this->indexExists('leads', 'leads_status_index')) {
                $table->index('status');
            }
            if (!$this->indexExists('leads', 'leads_created_by_index')) {
                $table->index('created_by');
            }
        });
    }

    private function addMemberIndexes(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (!$this->indexExists('members', 'members_organization_id_index')) {
                $table->index('organization_id');
            }
            if (!$this->indexExists('members', 'members_user_id_index')) {
                $table->index('user_id');
            }
        });
    }

    private function addSubscriptionIndexes(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!$this->indexExists('subscriptions', 'subscriptions_user_id_index')) {
                $table->index('user_id');
            }
            if (!$this->indexExists('subscriptions', 'subscriptions_status_index')) {
                $table->index('status');
            }
            if (!$this->indexExists('subscriptions', 'subscriptions_stripe_subscription_id_index')) {
                $table->index('stripe_subscription_id');
            }
        });
    }

    private function addAssessmentTokenIndexes(): void
    {
        if (Schema::hasTable('assessment_answer_tokens')) {
            Schema::table('assessment_answer_tokens', function (Blueprint $table) {
                if (!$this->indexExists('assessment_answer_tokens', 'assessment_answer_tokens_assessment_id_index')) {
                    $table->index('assessment_id');
                }
                if (!$this->indexExists('assessment_answer_tokens', 'assessment_answer_tokens_member_id_index')) {
                    $table->index('member_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex('announcements_sender_id_index');
            $table->dropIndex('announcements_scheduled_at_index');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_status_index');
            $table->dropIndex('leads_created_by_index');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('members_organization_id_index');
            $table->dropIndex('members_user_id_index');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_user_id_index');
            $table->dropIndex('subscriptions_status_index');
            $table->dropIndex('subscriptions_stripe_subscription_id_index');
        });

        if (Schema::hasTable('assessment_answer_tokens')) {
            Schema::table('assessment_answer_tokens', function (Blueprint $table) {
                $table->dropIndex('assessment_answer_tokens_assessment_id_index');
                $table->dropIndex('assessment_answer_tokens_member_id_index');
            });
        }
    }

    /**
     * Check if an index exists on a table.
     */
    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();

        $result = DB::select(
            "SELECT COUNT(*) as count 
             FROM information_schema.statistics 
             WHERE table_schema = ? 
             AND table_name = ? 
             AND index_name = ?",
            [$database, $table, $index]
        );

        return $result[0]->count > 0;
    }
};
