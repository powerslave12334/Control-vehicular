<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('maintenances', 'scheduled_date')) {
            Schema::table('maintenances', function (Blueprint $table) {
                $table->string('category')->nullable()->after('type');
                $table->date('scheduled_date')->nullable()->after('category');
                $table->date('start_date')->nullable()->after('scheduled_date');
                $table->date('end_date')->nullable()->after('start_date');
                $table->string('status')->default('scheduled')->after('end_date');
                $table->foreignId('requested_by')->nullable()->constrained('users')->after('status');
                $table->foreignId('approved_by')->nullable()->constrained('users')->after('requested_by');
                $table->text('rejection_reason')->nullable()->after('approved_by');

                $table->dropColumn('date');
            });

            Schema::table('maintenances', function (Blueprint $table) {
                $table->date('date')->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['category', 'scheduled_date', 'start_date', 'end_date', 'status', 'requested_by', 'approved_by', 'rejection_reason']);
        });
    }
};
