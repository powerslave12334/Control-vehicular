<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('incidents', 'type')) {
            Schema::table('incidents', function (Blueprint $table) {
                $table->string('type')->nullable()->after('time');
                $table->string('location')->nullable()->after('driver_name');
                $table->decimal('cost', 12, 2)->nullable()->after('severity');
                $table->boolean('involves_third_party')->default(false)->after('cost');
                $table->json('third_party_data')->nullable()->after('involves_third_party');
                $table->timestamp('resolved_at')->nullable()->after('photo');
                $table->foreignId('resolved_by')->nullable()->constrained('users')->after('resolved_at');
            });

            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE incidents MODIFY status VARCHAR(50) NOT NULL DEFAULT 'reported'");
            } elseif (DB::getDriverName() === 'pgsql') {
                DB::statement("ALTER TABLE incidents ALTER COLUMN status TYPE VARCHAR(50)");
                DB::statement("ALTER TABLE incidents ALTER COLUMN status SET NOT NULL");
                DB::statement("ALTER TABLE incidents ALTER COLUMN status SET DEFAULT 'reported'");
            }
        }
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeign(['resolved_by']);
            $table->dropColumn(['type', 'location', 'cost', 'involves_third_party', 'third_party_data', 'resolved_at', 'resolved_by']);
        });
    }
};
