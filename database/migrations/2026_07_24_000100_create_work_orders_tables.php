<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('work_orders')) {
            Schema::create('work_orders', function (Blueprint $table) {
                $table->id();
                $table->string('code', 50)->unique();
                $table->foreignId('vehicle_id')->constrained();
                $table->foreignId('operator_id')->nullable()->constrained('operators');
                $table->foreignId('maintenance_id')->nullable()->constrained('maintenances');
                $table->foreignId('provider_id')->constrained('providers');
                $table->text('description');
                $table->text('diagnosis')->nullable();
                $table->string('priority')->default('medium');
                $table->string('status')->default('draft');
                $table->decimal('estimated_cost', 12, 2)->nullable();
                $table->decimal('labor_cost', 12, 2)->nullable();
                $table->decimal('parts_cost', 12, 2)->nullable();
                $table->decimal('total_cost', 12, 2)->nullable();
                $table->foreignId('requested_by')->constrained('users');
                $table->foreignId('approved_by')->nullable()->constrained('users');
                $table->foreignId('assigned_to')->nullable()->constrained('providers');
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->decimal('mileage_at_request', 10, 2)->nullable();
                $table->decimal('mileage_at_completion', 10, 2)->nullable();
                $table->text('notes')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
            });
        }

        if (!Schema::hasTable('work_order_parts')) {
            Schema::create('work_order_parts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('part_id')->nullable()->constrained('parts');
                $table->string('description');
                $table->integer('quantity');
                $table->decimal('unit_cost', 10, 2);
                $table->decimal('total_cost', 12, 2);
                $table->string('source')->default('inventory');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('work_order_timeline')) {
            Schema::create('work_order_timeline', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
                $table->string('event_type');
                $table->text('description');
                $table->foreignId('user_id')->constrained('users');
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('work_order_evaluations')) {
            Schema::create('work_order_evaluations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained()->cascadeOnDelete()->unique();
                $table->integer('quality_score');
                $table->integer('timeliness_score');
                $table->integer('cost_score');
                $table->text('comments')->nullable();
                $table->foreignId('evaluated_by')->constrained('users');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_evaluations');
        Schema::dropIfExists('work_order_timeline');
        Schema::dropIfExists('work_order_parts');
        Schema::dropIfExists('work_orders');
    }
};
