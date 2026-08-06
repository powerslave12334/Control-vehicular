<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── vehicles ──────────────────────────────────────────────
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->constrained('vehicle_brands')->after('brand');
            $table->foreignId('model_id')->nullable()->constrained('vehicle_models')->after('model');
            $table->foreignId('fuel_type_id')->nullable()->constrained('fuel_types')->after('fuel_type');
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        DB::statement('UPDATE vehicles
            SET brand_id = (SELECT id FROM vehicle_brands WHERE name = vehicles.brand),
                model_id = (SELECT id FROM vehicle_models WHERE vehicle_brand_id = (SELECT id FROM vehicle_brands WHERE name = vehicles.brand) AND name = vehicles.model),
                fuel_type_id = (SELECT id FROM fuel_types WHERE name = vehicles.fuel_type)');

        // ── operators ─────────────────────────────────────────────
        Schema::table('operators', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        // ── routes ────────────────────────────────────────────────
        Schema::table('routes', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        // ── refuels ───────────────────────────────────────────────
        Schema::table('refuels', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        // ── maintenances ──────────────────────────────────────────
        Schema::table('maintenances', function (Blueprint $table) {
            $table->foreignId('workshop_id')->nullable()->constrained('workshops')->after('workshop');
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        DB::statement('UPDATE maintenances
            SET workshop_id = (SELECT id FROM workshops WHERE name = maintenances.workshop)');

        // ── incidents ─────────────────────────────────────────────
        Schema::table('incidents', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        // ── route_steps ───────────────────────────────────────────
        Schema::table('route_steps', function (Blueprint $table) {
            $table->softDeletes();
        });

        // ── extraordinary_movements ──────────────────────────────
        Schema::table('extraordinary_movements', function (Blueprint $table) {
            $table->softDeletes();
        });

    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['model_id']);
            $table->dropForeign(['fuel_type_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['brand_id', 'model_id', 'fuel_type_id', 'deleted_at', 'created_by', 'updated_by']);
        });

        Schema::table('operators', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['deleted_at', 'created_by', 'updated_by']);
        });

        Schema::table('routes', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['deleted_at', 'created_by', 'updated_by']);
        });

        Schema::table('refuels', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['deleted_at', 'created_by', 'updated_by']);
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropForeign(['workshop_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['workshop_id', 'deleted_at', 'created_by', 'updated_by']);
        });

        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['deleted_at', 'created_by', 'updated_by']);
        });

        Schema::table('route_steps', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('extraordinary_movements', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

    }
};
