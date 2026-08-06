<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE vehicles DROP FOREIGN KEY vehicles_assigned_operator_id_foreign');
        DB::statement('ALTER TABLE vehicles CHANGE assigned_operator_id assigned_driver_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE vehicles ADD CONSTRAINT vehicles_assigned_driver_id_foreign FOREIGN KEY (assigned_driver_id) REFERENCES users(id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE vehicles DROP FOREIGN KEY vehicles_assigned_driver_id_foreign');
        DB::statement('ALTER TABLE vehicles CHANGE assigned_driver_id assigned_operator_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE vehicles ADD CONSTRAINT vehicles_assigned_operator_id_foreign FOREIGN KEY (assigned_operator_id) REFERENCES operators(id)');
    }
};
