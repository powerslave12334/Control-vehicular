<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_brand_id')->constrained('vehicle_brands');
            $table->string('name');
            $table->timestamps();
            $table->unique(['vehicle_brand_id', 'name']);
        });

        Schema::create('fuel_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        $brands = DB::table('vehicles')->select('brand')->distinct()->pluck('brand');
        foreach ($brands as $brand) {
            DB::table('vehicle_brands')->insert([
                'name' => $brand,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $models = DB::table('vehicles')->select('brand', 'model')->distinct()->get();
        foreach ($models as $item) {
            $brandId = DB::table('vehicle_brands')->where('name', $item->brand)->value('id');
            DB::table('vehicle_models')->insert([
                'vehicle_brand_id' => $brandId,
                'name' => $item->model,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $fuelTypes = DB::table('vehicles')->select('fuel_type')->distinct()->pluck('fuel_type');
        foreach ($fuelTypes as $fuelType) {
            DB::table('fuel_types')->insert([
                'name' => $fuelType,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $workshops = DB::table('maintenances')->select('workshop')->distinct()->whereNotNull('workshop')->pluck('workshop');
        foreach ($workshops as $workshop) {
            DB::table('workshops')->insert([
                'name' => $workshop,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
        Schema::dropIfExists('fuel_types');
        Schema::dropIfExists('vehicle_models');
        Schema::dropIfExists('vehicle_brands');
    }
};
