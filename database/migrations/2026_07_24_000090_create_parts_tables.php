<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('part_categories')) {
            Schema::create('part_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('part_brands')) {
            Schema::create('part_brands', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('parts')) {
            Schema::create('parts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('part_category_id')->constrained('part_categories');
                $table->foreignId('part_brand_id')->nullable()->constrained('part_brands');
                $table->string('sku', 50)->unique();
                $table->text('description')->nullable();
                $table->decimal('unit_price', 12, 2);
                $table->integer('current_stock')->default(0);
                $table->integer('min_stock')->default(0);
                $table->integer('max_stock')->nullable();
                $table->string('unit_type', 50); // pieza, litro, metro, kg
                $table->string('location', 100)->nullable();
                $table->string('status')->default('active'); // active, inactive, discontinued
                $table->softDeletes();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
            });
        }

        if (!Schema::hasTable('part_inventory')) {
            Schema::create('part_inventory', function (Blueprint $table) {
                $table->id();
                $table->foreignId('part_id')->constrained('parts');
                $table->integer('quantity');
                $table->string('movement_type'); // in, out, adjustment
                $table->string('reference_type', 100)->nullable(); // work_order, maintenance, purchase, adjustment
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->decimal('unit_cost', 10, 2)->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('user_id')->constrained('users');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('part_suppliers')) {
            Schema::create('part_suppliers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('part_id')->constrained('parts');
                $table->foreignId('provider_id')->constrained('providers');
                $table->decimal('price', 12, 2);
                $table->integer('lead_time_days')->nullable();
                $table->boolean('is_preferred')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('part_suppliers');
        Schema::dropIfExists('part_inventory');
        Schema::dropIfExists('parts');
        Schema::dropIfExists('part_brands');
        Schema::dropIfExists('part_categories');
    }
};
