<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inspections')) {
            Schema::create('inspections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained();
                $table->foreignId('operator_id')->constrained('operators');
                $table->string('type'); // pre_trip, post_trip, periodic
                $table->timestamp('performed_at');
                $table->decimal('mileage', 10, 2)->nullable();
                $table->string('result'); // passed, failed, conditional
                $table->text('notes')->nullable();
                $table->json('checklist_results')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
