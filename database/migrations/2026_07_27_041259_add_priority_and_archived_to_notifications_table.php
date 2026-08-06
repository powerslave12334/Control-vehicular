<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('priority')->default('normal')->after('type');
            $table->timestamp('read_at')->nullable()->after('read');
            $table->boolean('archived')->default(false)->after('read_at');
            $table->timestamp('archived_at')->nullable()->after('archived');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['priority', 'read_at', 'archived', 'archived_at']);
        });
    }
};
