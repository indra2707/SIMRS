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
        Schema::create('icd10s', function (Blueprint $table) {
            $table->id();
            $table->char('kode', 50)->unique();
            $table->string('nama', 100);
            $table->enum('status', ['0', '1'])->default('1');
            $table->string('created_by', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('icd10s');
    }
};
