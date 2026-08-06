<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gate_logs', function (Blueprint $table) {
            $table->integer('fuel_level')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('gate_logs', function (Blueprint $table) {
            $table->string('fuel_level', 20)->nullable()->change();
        });
    }
};
