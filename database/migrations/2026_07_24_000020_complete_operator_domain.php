<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('operators', 'document_type')) {
            Schema::table('operators', function (Blueprint $table) {
                $table->string('document_type')->nullable()->after('name');
                $table->string('document_number')->nullable()->after('document_type');
                $table->string('email')->nullable()->after('phone');
                $table->text('address')->nullable()->after('email');
                $table->string('blood_type', 5)->nullable()->after('address');
                $table->string('emergency_contact')->nullable()->after('blood_type');
                $table->string('emergency_phone', 20)->nullable()->after('emergency_contact');
                $table->string('avatar')->nullable()->after('emergency_phone');
            });
        }

        if (!Schema::hasTable('licenses')) {
            Schema::create('licenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('operator_id')->constrained()->cascadeOnDelete();
                $table->string('license_number');
                $table->string('category');
                $table->date('expedition_date');
                $table->date('expiration_date');
                $table->string('status')->default('active');
                $table->softDeletes();
                $table->timestamps();

                $table->unique(['operator_id', 'license_number']);
            });
        }

        if (!Schema::hasTable('operator_documents')) {
            Schema::create('operator_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('operator_id')->constrained()->cascadeOnDelete();
                $table->string('type');
                $table->string('file_path');
                $table->string('file_name');
                $table->date('expiration_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('operator_documents');
        Schema::dropIfExists('licenses');
        Schema::table('operators', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'document_number', 'email', 'address', 'blood_type', 'emergency_contact', 'emergency_phone', 'avatar']);
        });
    }
};
