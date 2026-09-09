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
        Schema::create('tbl_disposisi_surat_detail', function (Blueprint $table) {
            $table->id();
                       $table->unsignedBigInteger('id_disposisi_surat');
 
            // Snapshot dari template (biar histori tidak berubah kalau
            // template/pemegang jabatan berubah di kemudian hari)
            $table->unsignedBigInteger('id_disposisi_jabatan')->nullable();
            $table->string('nama_jabatan');
            $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->unsignedBigInteger('id_unit');
 
            // Jenis tindakan -- checkbox, bisa lebih dari satu per baris
            $table->boolean('tindakan_action')->default(false);
            $table->boolean('tindakan_tanggapan')->default(false);
            $table->boolean('tindakan_info')->default(false);
            $table->boolean('tindakan_file')->default(false);
 
            // Menunggu -> belum dibuka
            // Dibaca    -> sudah dibuka, belum diparaf/ditindaklanjuti
            // Selesai   -> sudah diparaf/ditindaklanjuti
            $table->enum('status', ['Menunggu', 'Dibaca', 'Selesai'])
                ->default('Menunggu');
 
            $table->timestamp('tanggal_diteruskan')->nullable();
            $table->timestamp('tanggal_dibaca')->nullable();
            $table->timestamp('tanggal_paraf')->nullable();
 
            $table->text('catatan_tindak_lanjut')->nullable();
 
            $table->timestamps();
 
            $table->index(['id_disposisi_surat']);
            $table->index(['id_pegawai', 'id_unit']);
 
            $table->foreign('id_disposisi_surat')
                ->references('id')->on('tbl_disposisi_surat')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_disposisi_surat_detail');
    }
};
