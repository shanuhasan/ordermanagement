<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Indexes for the columns every list/total query filters on.
 * Skips columns that don't exist and indexes that are already present,
 * so it is safe to run on any copy of the database.
 */
return new class extends Migration
{
    private array $indexes = [
        'orders'               => ['company_id', 'employee_id', 'created_at'],
        'order_items'          => ['company_id', 'employee_id', 'order_id'],
        'received_items'       => ['company_id', 'employee_id', 'order_id'],
        'master_orders'        => ['company_id', 'employee_id'],
        'master_order_details' => ['company_id', 'employee_id', 'master_order_id'],
        'party_orders'         => ['company_id', 'party_id'],
        'employees'            => ['company_id', 'guid'],
        'parties'              => ['company_id', 'guid'],
        'items'                => ['company_id'],
        'sizes'                => ['company_id'],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => $columns) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                $name = "{$table}_{$column}_perf_index";

                if (!Schema::hasColumn($table, $column) || $this->indexExists($table, $name)) {
                    continue;
                }

                Schema::table($table, fn (Blueprint $t) => $t->index($column, $name));
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => $columns) {
            foreach ($columns as $column) {
                $name = "{$table}_{$column}_perf_index";

                if (Schema::hasTable($table) && $this->indexExists($table, $name)) {
                    Schema::table($table, fn (Blueprint $t) => $t->dropIndex($name));
                }
            }
        }
    }

    private function indexExists(string $table, string $name): bool
    {
        return !empty(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$name]));
    }
};
