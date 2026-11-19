<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * `rate` was an INT column, so values like 20.5 were rounded to 21 on save.
 * Change it to DOUBLE(10,2) (same type used for the amount columns).
 * Raw SQL is used so doctrine/dbal is not required.
 */
return new class extends Migration
{
    private array $tables = ['orders', 'master_orders', 'party_orders'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `rate` DOUBLE(10,2) NULL");
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `rate` INT NULL");
        }
    }
};
