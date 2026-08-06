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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('assigned_operator_id');
            $table->string('document_type')->nullable()->after('phone');
            $table->string('document_number')->nullable()->after('document_type');
            $table->string('license_type')->nullable()->after('document_number');
            $table->string('emergency_contact')->nullable()->after('license_type');
            $table->string('emergency_phone')->nullable()->after('emergency_contact');
            $table->text('address')->nullable()->after('emergency_phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'document_number', 'license_type', 'emergency_contact', 'emergency_phone', 'address']);
            $table->string('assigned_operator_id')->nullable()->after('phone');
        });
    }
};
