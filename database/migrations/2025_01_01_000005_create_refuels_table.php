<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refuels', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('route_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('driver_id')->constrained('operators');
            $table->string('driver_name');
            $table->decimal('liters', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->decimal('price_per_liter', 10, 2);
            $table->string('payment_method');
            $table->string('ticket_photo')->nullable();
            $table->integer('odometer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refuels');
    }
};
