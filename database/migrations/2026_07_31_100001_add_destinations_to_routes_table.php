<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('routes', 'destinations')) {
            Schema::table('routes', function (Blueprint $table) {
                $table->json('destinations')->nullable()->after('destination');
            });
        }

        DB::table('routes')
            ->whereNotNull('destination')
            ->whereNull('destinations')
            ->orderBy('id')
            ->chunkById(200, function ($routes) {
                foreach ($routes as $route) {
                    DB::table('routes')
                        ->where('id', $route->id)
                        ->update(['destinations' => json_encode([$route->destination])]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('destinations');
        });
    }
};
