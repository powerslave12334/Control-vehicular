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
        if (!Schema::hasColumn('vehicles', 'assigned_operator_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->foreignId('assigned_operator_id')->nullable()->constrained('operators')->after('responsible_user');
            });
        }
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['assigned_operator_id']);
            $table->dropColumn('assigned_operator_id');
        });
    }
};
