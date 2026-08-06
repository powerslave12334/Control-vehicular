<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gate_logs', function (Blueprint $table) {
            $table->string('route_folio', 100)->nullable()->after('type');
            $table->decimal('initial_odometer', 10, 2)->nullable()->after('driver_name');
            $table->string('fuel_level', 20)->nullable()->after('initial_odometer');
            $table->boolean('has_spare_tire')->default(false)->after('fuel_level');
            $table->string('vehicle_condition', 20)->nullable()->after('has_spare_tire');
        });
    }

    public function down(): void
    {
        Schema::table('gate_logs', function (Blueprint $table) {
            $table->dropColumn(['route_folio', 'initial_odometer', 'fuel_level', 'has_spare_tire', 'vehicle_condition']);
        });
    }
};
