<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('vehicle_id')->constrained();
            $table->string('type');
            $table->text('description');
            $table->decimal('cost', 10, 2);
            $table->string('workshop');
            $table->string('evidence')->nullable();
            $table->integer('odometer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
