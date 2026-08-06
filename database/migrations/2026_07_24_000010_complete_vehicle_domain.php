<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('vehicles', 'color')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('color')->nullable()->after('vin');
                $table->string('engine')->nullable()->after('color');
                $table->date('acquisition_date')->nullable()->after('engine');
                $table->decimal('acquisition_cost', 12, 2)->nullable()->after('acquisition_date');
                $table->foreignId('assigned_operator_id')->nullable()->constrained('operators')->after('responsible_user');
            });
        }

        if (!Schema::hasTable('vehicle_types')) {
            Schema::create('vehicle_types', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('vehicles', 'vehicle_type_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types')->after('assigned_operator_id');
            });
        }

        if (!Schema::hasTable('vehicle_logs')) {
            Schema::create('vehicle_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
                $table->string('event_type');
                $table->text('description');
                $table->foreignId('user_id')->nullable()->constrained();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index('event_type');
                $table->index('created_at');
            });
        }

        if (!Schema::hasTable('tires')) {
            Schema::create('tires', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
                $table->string('brand');
                $table->string('model');
                $table->string('size');
                $table->string('serial_number')->nullable();
                $table->enum('position', ['front_left', 'front_right', 'rear_left', 'rear_right', 'spare']);
                $table->enum('status', ['nueva', 'en_uso', 'desgastada', 'danada', 'desechada'])->default('nueva');
                $table->date('installation_date');
                $table->decimal('installation_odometer', 10, 2);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tire_changes')) {
            Schema::create('tire_changes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tire_id')->constrained()->cascadeOnDelete();
                $table->enum('from_position', ['front_left', 'front_right', 'rear_left', 'rear_right', 'spare']);
                $table->enum('to_position', ['front_left', 'front_right', 'rear_left', 'rear_right', 'spare']);
                $table->decimal('odometer', 10, 2);
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tire_changes');
        Schema::dropIfExists('tires');
        Schema::dropIfExists('vehicle_logs');
        Schema::dropIfExists('vehicle_types');

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['assigned_operator_id']);
            $table->dropForeign(['vehicle_type_id']);
            $table->dropColumn(['color', 'engine', 'acquisition_date', 'acquisition_cost', 'assigned_operator_id', 'vehicle_type_id']);
        });
    }
};
