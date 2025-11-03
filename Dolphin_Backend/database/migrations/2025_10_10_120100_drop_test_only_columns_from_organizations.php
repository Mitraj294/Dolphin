<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * List of organization columns that were used for tests and may be removed.
     */
    private const ORG_COLUMNS = [
        'source',
        'address1',
        'address2',
        'country_id',
        'state_id',
        'city_id',
        'zip',
        'main_contact',
        'admin_email',
        'admin_phone'
    ];

    /**
     * Return true when the DB connection is MySQL.
     */
    private function isMySql(): bool
    {
        return DB::connection()->getDriverName() === 'mysql';
    }

    private function existingColumns(string $table, array $cols): array
    {
        $found = [];
        foreach ($cols as $c) {
            if (Schema::hasColumn($table, $c)) {
                $found[] = $c;
            }
        }
        return $found;
    }

    private function dropForeignsIfPresent(string $table, array $columns): void
    {
        try {
            Schema::table($table, function (Blueprint $t) use ($columns) {
                foreach ($columns as $col) {
                    try {
                        $t->dropForeign([$col]);
                    } catch (\Exception $e) {
                        // ignore missing foreign keys per-column
                    }
                }
            });
        } catch (\Exception $e) {
            Log::warning('Error dropping foreign keys on ' . $table . ': ' . $e->getMessage());
        }
    }

    public function up(): void
    {
        // This migration is MySQL-specific for aligning with local dolphin_db
        if (! $this->isMySql()) {
            return; // Skip for non-MySQL databases
        }

        if (! Schema::hasTable('organizations')) {
            return;
        }

        $columnsToDrop = $this->existingColumns('organizations', self::ORG_COLUMNS);
        if (empty($columnsToDrop)) {
            return;
        }

        // drop foreign keys related to location columns safely
        $locationCols = array_intersect($columnsToDrop, ['country_id', 'state_id', 'city_id']);
        if (! empty($locationCols)) {
            $this->dropForeignsIfPresent('organizations', $locationCols);
        }

        // Drop columns in one operation
        try {
            Schema::table('organizations', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        } catch (\Exception $e) {
            Log::warning('Failed to drop organization columns: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        // This migration is MySQL-specific
        if (! $this->isMySql()) {
            return;
        }

        if (! Schema::hasTable('organizations')) {
            return;
        }

        // Map column => closure that adds the column when invoked
        $definitions = [
            'source' => function (Blueprint $t) {
                $t->string('source')->nullable()->after('organization_size');
            },
            'address1' => function (Blueprint $t) {
                $t->string('address1')->nullable()->after('source');
            },
            'address2' => function (Blueprint $t) {
                $t->string('address2')->nullable()->after('address1');
            },
            'country_id' => function (Blueprint $t) {
                $t->unsignedBigInteger('country_id')->nullable()->after('address2');
            },
            'state_id' => function (Blueprint $t) {
                $t->unsignedBigInteger('state_id')->nullable()->after('country_id');
            },
            'city_id' => function (Blueprint $t) {
                $t->unsignedBigInteger('city_id')->nullable()->after('state_id');
            },
            'zip' => function (Blueprint $t) {
                $t->string('zip')->nullable()->after('city_id');
            },
            'main_contact' => function (Blueprint $t) {
                $t->string('main_contact')->nullable()->after('sales_person_id');
            },
            'admin_email' => function (Blueprint $t) {
                $t->string('admin_email')->nullable()->after('main_contact');
            },
            'admin_phone' => function (Blueprint $t) {
                $t->string('admin_phone')->nullable()->after('admin_email');
            },
        ];

        try {
            Schema::table('organizations', function (Blueprint $table) use ($definitions) {
                foreach ($definitions as $col => $fn) {
                    if (! Schema::hasColumn('organizations', $col)) {
                        $fn($table);
                    }
                }
            });
        } catch (\Exception $e) {
            Log::warning('Failed to restore organization columns: ' . $e->getMessage());
        }
    }
};
