<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained();
                $table->foreignId('operator_id')->nullable()->constrained('operators');
                $table->string('type');
                $table->text('description');
                $table->decimal('amount', 12, 2);
                $table->date('date');
                $table->string('folio', 50)->nullable();
                $table->string('provider_name')->nullable();
                $table->string('status')->default('pending');
                $table->string('evidence')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->foreignId('approved_by')->nullable()->constrained('users');
                $table->softDeletes();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
