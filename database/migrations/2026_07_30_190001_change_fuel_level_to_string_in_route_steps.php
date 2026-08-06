<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE route_steps MODIFY COLUMN fuel_level VARCHAR(50) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE route_steps MODIFY COLUMN fuel_level INTEGER NULL');
    }
};
