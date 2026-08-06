<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('insurances')) {
            Schema::create('insurances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained();
                $table->string('policy_number', 50);
                $table->string('insurer');
                $table->string('coverage_type', 100);
                $table->date('start_date');
                $table->date('end_date');
                $table->decimal('premium', 12, 2);
                $table->decimal('deductible', 12, 2);
                $table->string('status')->default('active');
                $table->softDeletes();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
