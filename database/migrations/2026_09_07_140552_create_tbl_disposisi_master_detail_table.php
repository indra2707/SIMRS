<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_disposisi_master_detail', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_disposisi_master');
            $table->foreign('id_disposisi_master')
                ->references('id')->on('tbl_disposisi_master')
                ->onDelete('cascade');

            // Nomor urut baris sesuai kertas (1, 2, 3, ... dst)
            $table->unsignedInteger('urutan')->default(1);

            // Pegawai yang muncul di baris ini, mis. "HEAD OF HUMAN CAPITAL & GENERAL AFFAIR"
            $table->unsignedBigInteger('id_pegawai_tujuan');
            $table->foreign('id_pegawai_tujuan')
                ->references('id')->on('pegawai');

            $table->unsignedBigInteger('id_unit')->nullable();

            $table->timestamps();

            $table->unique(
                ['id_disposisi_master', 'id_pegawai_tujuan'],
                'uq_master_pegawai_tujuan'
            );
            $table->index(['id_disposisi_master', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_disposisi_master_detail');
    }
};
