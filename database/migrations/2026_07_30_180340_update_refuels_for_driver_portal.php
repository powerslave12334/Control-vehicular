<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE refuels DROP FOREIGN KEY refuels_driver_id_foreign');
            DB::statement('ALTER TABLE refuels MODIFY driver_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE refuels ADD CONSTRAINT refuels_driver_id_foreign FOREIGN KEY (driver_id) REFERENCES users(id)');
            DB::statement('ALTER TABLE refuels MODIFY odometer INT NULL');
            DB::statement('ALTER TABLE refuels ADD fuel_level VARCHAR(50) NULL AFTER amount');
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE refuels DROP CONSTRAINT IF EXISTS refuels_driver_id_foreign');
            DB::statement('ALTER TABLE refuels ALTER COLUMN driver_id TYPE BIGINT');
            DB::statement('ALTER TABLE refuels ALTER COLUMN driver_id DROP NOT NULL');
            DB::statement('ALTER TABLE refuels ADD CONSTRAINT refuels_driver_id_foreign FOREIGN KEY (driver_id) REFERENCES users(id)');
            DB::statement('ALTER TABLE refuels ALTER COLUMN odometer TYPE INTEGER');
            DB::statement('ALTER TABLE refuels ALTER COLUMN odometer DROP NOT NULL');
            DB::statement('ALTER TABLE refuels ADD COLUMN IF NOT EXISTS fuel_level VARCHAR(50) NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE refuels DROP FOREIGN KEY refuels_driver_id_foreign');
            DB::statement('ALTER TABLE refuels DROP COLUMN fuel_level');
            DB::statement('ALTER TABLE refuels MODIFY odometer INT NOT NULL');
            DB::statement('ALTER TABLE refuels MODIFY driver_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE refuels ADD CONSTRAINT refuels_driver_id_foreign FOREIGN KEY (driver_id) REFERENCES operators(id)');
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE refuels DROP CONSTRAINT IF EXISTS refuels_driver_id_foreign');
            DB::statement('ALTER TABLE refuels DROP COLUMN IF EXISTS fuel_level');
            DB::statement('ALTER TABLE refuels ALTER COLUMN odometer TYPE INTEGER');
            DB::statement('ALTER TABLE refuels ALTER COLUMN odometer SET NOT NULL');
            DB::statement('ALTER TABLE refuels ALTER COLUMN driver_id TYPE BIGINT');
            DB::statement('ALTER TABLE refuels ALTER COLUMN driver_id SET NOT NULL');
            DB::statement('ALTER TABLE refuels ADD CONSTRAINT refuels_driver_id_foreign FOREIGN KEY (driver_id) REFERENCES operators(id)');
        }
    }
};
