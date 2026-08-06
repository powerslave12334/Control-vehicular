<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE gate_logs ALTER COLUMN fuel_level DROP NOT NULL');
            DB::statement('ALTER TABLE gate_logs ALTER COLUMN fuel_level DROP DEFAULT');
            DB::statement('ALTER TABLE gate_logs ALTER COLUMN fuel_level TYPE INTEGER USING (fuel_level::integer)');
            Schema::table('gate_logs', function (Blueprint $table) {
                $table->integer('fuel_level')->nullable()->change();
            });
            return;
        }

        Schema::table('gate_logs', function (Blueprint $table) {
            $table->integer('fuel_level')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE gate_logs ALTER COLUMN fuel_level DROP NOT NULL');
            DB::statement('ALTER TABLE gate_logs ALTER COLUMN fuel_level TYPE VARCHAR(20)');
            return;
        }

        Schema::table('gate_logs', function (Blueprint $table) {
            $table->string('fuel_level', 20)->nullable()->change();
        });
    }
};
