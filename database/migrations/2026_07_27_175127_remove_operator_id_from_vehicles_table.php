<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('vehicles', 'operator_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropForeign(['operator_id']);
                $table->dropColumn('operator_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('vehicles', 'operator_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->foreignId('operator_id')->nullable()->constrained('operators')->after('responsible_user');
            });
        }
    }
};
