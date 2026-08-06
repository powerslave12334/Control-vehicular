<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->cascadeOnDelete();
            $table->string('step_type');
            $table->integer('odometer')->nullable();
            $table->integer('fuel_level')->nullable();
            $table->timestamp('timestamp');
            $table->string('photo')->nullable();
            $table->decimal('liters', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('ticket_photo')->nullable();
            $table->text('observations')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_steps');
    }
};
