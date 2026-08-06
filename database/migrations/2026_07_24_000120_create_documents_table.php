<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->morphs('documentable');
                $table->string('type');
                $table->string('name');
                $table->string('file_path');
                $table->date('issuance_date')->nullable();
                $table->date('expiry_date')->nullable();
                $table->string('status')->default('active');
                $table->softDeletes();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
