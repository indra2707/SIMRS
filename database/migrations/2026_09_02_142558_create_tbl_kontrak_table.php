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
        Schema::create('tbl_kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('id_pegawai');
            $table->string('nomor_kontrak');
            $table->enum('status', ['PWTT', 'PWT', 'Mitra Pegawai', 'Mitra Dokter', 'Outsourcing','Internship']);
            $table->date('tanggal_mulai');
            $table->enum('masa_berlaku', ['0', '1'])->nullable()->default('0');
            $table->date('tanggal_berakhir')->nullable();
            $table->text('lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_kontrak');
    }
};
