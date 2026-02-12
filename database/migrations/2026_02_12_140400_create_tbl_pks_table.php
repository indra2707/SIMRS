<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_pks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nomor_kontrak');
            $table->string('id_jenis_kontrak');
            $table->string('pihak');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('file')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pks');
    }
};
