<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('routes', 'code')) {
            Schema::table('routes', function (Blueprint $table) {
                $table->string('code')->nullable()->after('id');
                $table->text('description')->nullable()->after('code');
                $table->string('origin')->nullable()->after('description');
                $table->string('destination')->nullable()->after('origin');
                $table->decimal('distance_km', 10, 2)->nullable()->after('destination');
                $table->integer('estimated_duration')->nullable()->comment('minutos')->after('distance_km');
                $table->decimal('start_odometer', 10, 2)->nullable()->after('estimated_duration');
                $table->decimal('end_odometer', 10, 2)->nullable()->after('start_odometer');
                $table->timestamp('started_at')->nullable()->after('end_odometer');
                $table->timestamp('finished_at')->nullable()->after('started_at');
                $table->timestamp('cancelled_at')->nullable()->after('finished_at');
                $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn([
                'code', 'description', 'origin', 'destination',
                'distance_km', 'estimated_duration',
                'start_odometer', 'end_odometer',
                'started_at', 'finished_at', 'cancelled_at', 'cancellation_reason',
            ]);
        });
    }
};
