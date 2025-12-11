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
        Schema::create('pekerja', function (Blueprint $table) {
            $table->id();
            $table->string('anak_perusahaan')->nullable();
            $table->string('rumah_sakit')->nullable();
            $table->string('nomor_sk_struktur')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('penempatan')->nullable();
            $table->string('lokasi_kerja')->nullable();
            $table->string('nomor_pekerja')->nullable();
            $table->string('nama_pekerja')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('agama')->nullable();
            $table->string('nik')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->string('disabilitas')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('golongan_upah')->nullable();
            $table->string('status_kepegawaian')->nullable();
            $table->date('tmt_status_kepegawaian')->nullable();
            $table->date('tmt_pwtt')->nullable();
            $table->date('tmt_pwt')->nullable();
            $table->string('masa_kerja')->nullable();
            $table->string('fungsi')->nullable();
            $table->string('sub_fungsi')->nullable();
            $table->date('tmt_jabatan')->nullable();
            $table->date('tmt_golongan_upah')->nullable();
            $table->string('penyetaraan_jabatan_ap')->nullable();
            $table->string('penyetaraan_golongan_upah_ap')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->string('nomor_bpjstk')->nullable();
            $table->string('nomor_bpjskesehatan')->nullable();
            $table->string('nomor_npwp')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('nomor_kontak_darurat')->nullable();
            $table->string('nama_kontak_darurat')->nullable();
            $table->string('hubungan_kontak_darurat')->nullable();
            $table->string('email')->nullable();
            $table->string('email_dinas')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_npwp')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('nomor_str')->nullable();
            $table->string('str_seumur_hidup')->nullable();
            $table->date('masa_berlaku_str')->nullable();
            $table->string('nomor_sip')->nullable();
            $table->date('masa_berlaku_sip')->nullable();
            $table->string('asuransi_profesi')->nullable();
            $table->string('nomor_polis')->nullable();
            $table->date('masa_berlaku_asuransi')->nullable();

            // Pendidikan
            $table->string('pend_diploma')->nullable();
            $table->string('pend_s1')->nullable();
            $table->string('pend_s2')->nullable();
            $table->string('pend_s3')->nullable();
            $table->string('kampus_terakhir')->nullable();
            $table->string('keterangan')->nullable();

            $table->date('tanggal_akhir_kontrak')->nullable();
            $table->string('jenjang_pendidikan_terakhir')->nullable();

            // Input tracking
            $table->string('input_by')->nullable();
            $table->date('input_date')->nullable();
            $table->string('update_by')->nullable();
            $table->date('update_date')->nullable();

            // Username
            $table->string('temp_username')->nullable();
            $table->string('username')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pekerja');
    }
};
