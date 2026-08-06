<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->string('client_name')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->decimal('planned_km', 10, 2)->nullable()->change();
            $table->decimal('actual_km', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->string('client_name')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('state')->nullable(false)->change();
            $table->decimal('planned_km', 10, 2)->nullable(false)->change();
            $table->decimal('actual_km', 10, 2)->nullable(false)->change();
        });
    }
};
