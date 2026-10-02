<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_disposisi_master', function (Blueprint $table) {
            $table->id();

            // Nama lembar, mis. "DIREKTUR", "VICE DIRECTOR HCGA", "MANAGER FINANCE", "VD MEDICAL & NURSING"
            $table->string('nama_master');

            // Pegawai yang saat ini memegang jabatan ini / pemilik lembar ini.
            // Kalau pegawai berganti (mutasi/promosi), tinggal update kolom ini lewat menu admin,
            // tanpa perlu ubah struktur data lain.
            $table->unsignedBigInteger('id_pegawai_pemilik')->nullable();
            $table->foreign('id_pegawai_pemilik')
                ->references('id')->on('pegawai')
                ->nullOnDelete();

            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_disposisi_master');
    }
};
