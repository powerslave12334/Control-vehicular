<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gate_logs', function (Blueprint $table) {
            $table->string('driver_photo')->nullable()->after('photo');
            $table->boolean('confirmed')->default(false)->after('driver_photo');
        });
    }

    public function down(): void
    {
        Schema::table('gate_logs', function (Blueprint $table) {
            $table->dropColumn(['driver_photo', 'confirmed']);
        });
    }
};
