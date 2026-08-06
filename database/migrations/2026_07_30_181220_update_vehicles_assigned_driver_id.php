<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE vehicles DROP FOREIGN KEY vehicles_assigned_operator_id_foreign');
            DB::statement('ALTER TABLE vehicles CHANGE assigned_operator_id assigned_driver_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE vehicles ADD CONSTRAINT vehicles_assigned_driver_id_foreign FOREIGN KEY (assigned_driver_id) REFERENCES users(id)');
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE vehicles DROP CONSTRAINT IF EXISTS vehicles_assigned_operator_id_foreign');
            DB::statement('ALTER TABLE vehicles RENAME COLUMN assigned_operator_id TO assigned_driver_id');
            DB::statement('ALTER TABLE vehicles ALTER COLUMN assigned_driver_id TYPE BIGINT');
            DB::statement('ALTER TABLE vehicles ALTER COLUMN assigned_driver_id DROP NOT NULL');
            DB::statement('ALTER TABLE vehicles ADD CONSTRAINT vehicles_assigned_driver_id_foreign FOREIGN KEY (assigned_driver_id) REFERENCES users(id)');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE vehicles DROP FOREIGN KEY vehicles_assigned_driver_id_foreign');
            DB::statement('ALTER TABLE vehicles CHANGE assigned_driver_id assigned_operator_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE vehicles ADD CONSTRAINT vehicles_assigned_operator_id_foreign FOREIGN KEY (assigned_operator_id) REFERENCES operators(id)');
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE vehicles DROP CONSTRAINT IF EXISTS vehicles_assigned_driver_id_foreign');
            DB::statement('ALTER TABLE vehicles RENAME COLUMN assigned_driver_id TO assigned_operator_id');
            DB::statement('ALTER TABLE vehicles ALTER COLUMN assigned_operator_id TYPE BIGINT');
            DB::statement('ALTER TABLE vehicles ALTER COLUMN assigned_operator_id DROP NOT NULL');
            DB::statement('ALTER TABLE vehicles ADD CONSTRAINT vehicles_assigned_operator_id_foreign FOREIGN KEY (assigned_operator_id) REFERENCES operators(id)');
        }
    }
};
