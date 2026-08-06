<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('week');
            $table->foreignId('driver_id')->constrained('operators');
            $table->string('driver_name');
            $table->string('assistant_id')->nullable();
            $table->string('assistant_name')->nullable();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->string('vehicle_plate');
            $table->string('client_name');
            $table->string('city');
            $table->string('state');
            $table->decimal('planned_km', 10, 2);
            $table->decimal('actual_km', 10, 2)->default(0);
            $table->string('status')->default('Programada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
