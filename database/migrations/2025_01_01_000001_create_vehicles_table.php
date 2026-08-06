<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->string('plate')->unique();
            $table->string('fuel_type')->nullable();
            $table->string('color')->nullable();
            $table->string('engine')->nullable();
            $table->string('cargo_capacity')->nullable();
            $table->decimal('tank_capacity', 8, 2)->nullable();
            $table->boolean('gps_installed')->nullable()->default(false);
            $table->string('vin')->nullable();
            $table->decimal('acquisition_cost', 10, 2)->nullable();
            $table->date('acquisition_date')->nullable();
            $table->integer('current_odometer')->nullable()->default(0);
            $table->string('status')->nullable()->default('Activa');
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->integer('incidents_count')->default(0);
            $table->decimal('authorized_fuel', 10, 2)->default(100);
            $table->text('notes')->nullable();
            $table->string('responsible_user')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
