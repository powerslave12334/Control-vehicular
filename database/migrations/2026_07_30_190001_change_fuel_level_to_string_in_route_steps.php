<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE route_steps MODIFY COLUMN fuel_level VARCHAR(50) NULL');
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE route_steps ALTER COLUMN fuel_level TYPE VARCHAR(50)');
            DB::statement('ALTER TABLE route_steps ALTER COLUMN fuel_level DROP NOT NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE route_steps MODIFY COLUMN fuel_level INTEGER NULL');
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE route_steps ALTER COLUMN fuel_level TYPE INTEGER');
            DB::statement('ALTER TABLE route_steps ALTER COLUMN fuel_level DROP NOT NULL');
        }
    }
};
