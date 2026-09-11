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

            // Informasi surat
            $table->date('tanggal');
            $table->string('no_surat', 100)->unique();
            $table->unsignedBigInteger('approval_id')->nullable();

            // Lampiran
            $table->json('lampiran')->nullable();
            $table->string('jumlah_lampiran');

            // Isi surat
            $table->string('perihal', 255);
            $table->longText('isi_surat');

            // Status utama surat
            // Draft     = masih dibuat
            // Approve   = sedang dalam proses approval
            // Revisi    = ditolak dan dikembalikan ke pembuat
            // Selesai   = seluruh approval selesai
            $table->enum('status', [
                'Draft',
                'Approve',
                'Revisi',
                'Selesai',
            ])->default('Draft');

            // Pemilik / pembuat surat
            $table->string('id_unit');
            $table->string('id_pegawai');

            $table->timestamps();

            // Index untuk mempercepat pencarian surat milik user
            $table->index(['id_unit', 'id_pegawai']);

            // Index untuk pencarian berdasarkan status
            $table->index(['id_unit', 'status']);
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
