<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_disposisi_surat', function (Blueprint $table) {
            $table->id();

            // Surat yang didisposisikan
            $table->unsignedBigInteger('id_surat');
            $table->foreign('id_surat')
                ->references('id')->on('surat')
                ->onDelete('cascade');

            // Parent disposisi -> untuk membentuk alur/tree disposisi berjenjang.
            // null  = ini disposisi tahap pertama (dikeluarkan Direktur setelah surat Approve/Selesai)
            // not null = ini hasil diteruskan dari disposisi lain (mis. VD -> Head)
            $table->unsignedBigInteger('id_parent')->nullable();
            $table->foreign('id_parent')
                ->references('id')->on('tbl_disposisi_surat')
                ->onDelete('cascade');

            // Pengirim / yang membuat disposisi ini
            $table->unsignedBigInteger('id_pegawai_pengirim');
            $table->foreign('id_pegawai_pengirim')
                ->references('id')->on('pegawai');
            $table->unsignedBigInteger('id_unit_pengirim')->nullable();

            // Tujuan / penerima disposisi ini
            $table->unsignedBigInteger('id_pegawai_tujuan');
            $table->foreign('id_pegawai_tujuan')
                ->references('id')->on('pegawai');
            $table->unsignedBigInteger('id_unit_tujuan')->nullable();

            // Checklist sesuai lembar "Lembar Penerus" (kolom A/T/I/F)
            $table->boolean('is_action')->default(false);     // Action
            $table->boolean('is_tanggapan')->default(false);  // Tanggapan / opini
            $table->boolean('is_info')->default(false);       // Info
            $table->boolean('is_file')->default(false);       // File

            // Tingkat surat: R = Rahasia, P = Penting, S = Segera, B = Biasa
            $table->enum('tingkat_surat', ['R', 'P', 'S', 'B'])->default('B');

            $table->text('catatan')->nullable();
            $table->date('tanggal_diteruskan')->nullable();
            $table->string('paraf')->nullable(); // bisa diisi nama/username sbg bukti paraf digital

            // Status progres di sisi penerima
            $table->enum('status', ['Menunggu', 'Dibaca', 'Selesai'])->default('Menunggu');
            $table->timestamp('tanggal_dibaca')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();

            $table->timestamps();

            $table->index(['id_surat']);
            $table->index(['id_pegawai_tujuan']);
            $table->index(['id_parent']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_disposisi_surat');
    }
};
