<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('expense_card_number', 50)->nullable()->after('authorized_fuel');
            $table->decimal('authorized_expense', 12, 2)->default(0)->after('expense_card_number');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['expense_card_number', 'authorized_expense']);
        });
    }
};
