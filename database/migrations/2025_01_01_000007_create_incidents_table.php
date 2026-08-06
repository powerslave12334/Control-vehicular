<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('time');
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('driver_id')->constrained('operators');
            $table->string('driver_name');
            $table->text('description');
            $table->string('severity');
            $table->string('status')->default('Reportada');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
