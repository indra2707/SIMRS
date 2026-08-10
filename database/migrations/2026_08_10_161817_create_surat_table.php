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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('no_surat', 100)->unique();
            $table->foreignId('approval_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('lampiran')->nullable();
            $table->string('perihal');
            $table->longText('isi_surat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
