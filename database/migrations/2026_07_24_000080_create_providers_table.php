<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('providers')) {
            Schema::create('providers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type'); // workshop, parts_store, gas_station, etc.
                $table->string('contact')->nullable();
                $table->string('phone', 20)->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('rfc', 13)->nullable();
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
        Schema::dropIfExists('providers');
    }
};
