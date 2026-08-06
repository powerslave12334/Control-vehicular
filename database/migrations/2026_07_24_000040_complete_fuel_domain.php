<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('fuel_stations')) {
            Schema::create('fuel_stations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('address')->nullable();
                $table->string('phone', 20)->nullable();
                $table->string('rfc', 50)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fuel_cards')) {
            Schema::create('fuel_cards', function (Blueprint $table) {
                $table->id();
                $table->string('card_number');
                $table->string('holder_name');
                $table->string('type')->default('empresarial');
                $table->decimal('monthly_limit', 12, 2)->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('refuels', 'folio')) {
            Schema::table('refuels', function (Blueprint $table) {
                $table->string('folio')->nullable()->after('id');
                $table->foreignId('fuel_station_id')->nullable()->constrained('fuel_stations')->after('driver_name');
                $table->foreignId('fuel_card_id')->nullable()->constrained('fuel_cards')->after('fuel_station_id');
                $table->string('status')->default('pending')->after('odometer');
                $table->foreignId('approved_by')->nullable()->constrained('users')->after('status');
                $table->text('rejection_reason')->nullable()->after('approved_by');
            });
        }
    }

    public function down(): void
    {
        Schema::table('refuels', function (Blueprint $table) {
            $table->dropForeign(['fuel_station_id']);
            $table->dropForeign(['fuel_card_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['folio', 'fuel_station_id', 'fuel_card_id', 'status', 'approved_by', 'rejection_reason']);
        });
        Schema::dropIfExists('fuel_cards');
        Schema::dropIfExists('fuel_stations');
    }
};
