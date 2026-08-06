<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gate_logs', function (Blueprint $table) {
            $table->date('entry_date')->nullable()->after('type');
            $table->time('entry_time')->nullable()->after('entry_date');
            $table->date('exit_date')->nullable()->after('entry_time');
            $table->time('exit_time')->nullable()->after('exit_date');
            $table->text('signature')->nullable()->after('photo');
            $table->json('checklist')->nullable()->after('signature');
        });
    }

    public function down(): void
    {
        Schema::table('gate_logs', function (Blueprint $table) {
            $table->dropColumn(['entry_date', 'entry_time', 'exit_date', 'exit_time', 'signature', 'checklist']);
        });
    }
};
