<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE refuels DROP FOREIGN KEY refuels_driver_id_foreign');
        DB::statement('ALTER TABLE refuels MODIFY driver_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE refuels ADD CONSTRAINT refuels_driver_id_foreign FOREIGN KEY (driver_id) REFERENCES users(id)');
        DB::statement('ALTER TABLE refuels MODIFY odometer INT NULL');
        DB::statement('ALTER TABLE refuels ADD fuel_level VARCHAR(50) NULL AFTER amount');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE refuels DROP FOREIGN KEY refuels_driver_id_foreign');
        DB::statement('ALTER TABLE refuels DROP COLUMN fuel_level');
        DB::statement('ALTER TABLE refuels MODIFY odometer INT NOT NULL');
        DB::statement('ALTER TABLE refuels MODIFY driver_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE refuels ADD CONSTRAINT refuels_driver_id_foreign FOREIGN KEY (driver_id) REFERENCES operators(id)');
    }
};
