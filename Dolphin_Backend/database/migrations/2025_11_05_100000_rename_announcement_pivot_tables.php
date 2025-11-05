<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Rename announcement_admin to announcement_dolphin_admins
        if (Schema::hasTable('announcement_admin') && !Schema::hasTable('announcement_dolphin_admins')) {
            Schema::rename('announcement_admin', 'announcement_dolphin_admins');
        }

        // Add timestamps to announcement_dolphin_admins if not exists
        if (Schema::hasTable('announcement_dolphin_admins')) {
            Schema::table('announcement_dolphin_admins', function (Blueprint $table) {
                if (!Schema::hasColumn('announcement_dolphin_admins', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        // Rename announcement_group to announcement_groups
        if (Schema::hasTable('announcement_group') && !Schema::hasTable('announcement_groups')) {
            Schema::rename('announcement_group', 'announcement_groups');
        }

        // Add member_ids and timestamps to announcement_groups
        if (Schema::hasTable('announcement_groups')) {
            Schema::table('announcement_groups', function (Blueprint $table) {
                if (!Schema::hasColumn('announcement_groups', 'member_ids')) {
                    $table->json('member_ids')->nullable()->after('group_id');
                }
                if (!Schema::hasColumn('announcement_groups', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        // Rename announcement_organization to announcement_organizations
        if (Schema::hasTable('announcement_organization') && !Schema::hasTable('announcement_organizations')) {
            Schema::rename('announcement_organization', 'announcement_organizations');
        }

        // Add timestamps to announcement_organizations
        if (Schema::hasTable('announcement_organizations')) {
            Schema::table('announcement_organizations', function (Blueprint $table) {
                if (!Schema::hasColumn('announcement_organizations', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        // Remove added columns and rename back
        if (Schema::hasTable('announcement_organizations')) {
            Schema::table('announcement_organizations', function (Blueprint $table) {
                if (Schema::hasColumn('announcement_organizations', 'created_at')) {
                    $table->dropTimestamps();
                }
            });
            Schema::rename('announcement_organizations', 'announcement_organization');
        }

        if (Schema::hasTable('announcement_groups')) {
            Schema::table('announcement_groups', function (Blueprint $table) {
                if (Schema::hasColumn('announcement_groups', 'member_ids')) {
                    $table->dropColumn('member_ids');
                }
                if (Schema::hasColumn('announcement_groups', 'created_at')) {
                    $table->dropTimestamps();
                }
            });
            Schema::rename('announcement_groups', 'announcement_group');
        }

        if (Schema::hasTable('announcement_dolphin_admins')) {
            Schema::table('announcement_dolphin_admins', function (Blueprint $table) {
                if (Schema::hasColumn('announcement_dolphin_admins', 'created_at')) {
                    $table->dropTimestamps();
                }
            });
            Schema::rename('announcement_dolphin_admins', 'announcement_admin');
        }
    }
};
