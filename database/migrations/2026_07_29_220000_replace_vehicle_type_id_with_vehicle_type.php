<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'vehicle_type_id')) {
                $table->dropForeign(['vehicle_type_id']);
                $table->dropColumn('vehicle_type_id');
            }
        });

        Schema::dropIfExists('vehicle_types');

        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'vehicle_type')) {
                $table->string('vehicle_type')->nullable()->after('assigned_operator_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'vehicle_type')) {
                $table->dropColumn('vehicle_type');
            }
        });

        if (!Schema::hasTable('vehicle_types')) {
            Schema::create('vehicle_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'vehicle_type_id')) {
                $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types')->after('assigned_operator_id');
            }
        });
    }
};
