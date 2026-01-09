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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('anak_perusahaan');
            $table->string('id_sk_struktur');
            $table->string('id_jabatan');
            $table->string('penempatan');
            $table->string('lokasi_kerja');
            $table->enum('status_kepegawaian', ["PWTT", "PWT", "Mitra Pegawai", "Mitra Dokter", "Outsourcing", "Internship"]);
            $table->string('nomor_pekerja');
            $table->string('nama_pekerja');
            $table->enum('jenis_kelamin', ["Laki-laki", "Perempuan"]);
            $table->enum('agama', ["Islam", "Kristen", "Hindu", "Buddha", "Konghucu", "Katolik"]);
            $table->string('nik', 16);
            $table->enum('status_pernikahan', ["Menikah", "Belum Menikah", "Cerai", "Janda", "Kawin", "Lajang"]);
            $table->enum('golongan_darah', ["A", "A+", "A-", "B", "B+", "B-", "AB", "AB+", "AB-", "O", "O+", "O-"]);
            $table->enum('disabilitas', ["Ya", "Tidak"])->default('Tidak');
            $table->date('tanggal_lahir');
            $table->enum('golongan_upah', ['Utama', 'Madya', 'Biasa']);
            $table->date('tmt_status_kepegawaian')->nullable();
            $table->date('tmt_pwtt')->nullable();
            $table->date('tmt_pwt')->nullable();
            $table->string('masa_kerja')->nullable();
            $table->enum('fungsi', ["Medis", "Perawat", "Nakes Lain", "Non Medis"]);
            $table->string('id_sub_fungsi');
            $table->date('tmt_golongan_upah')->nullable();
            $table->string('penyetaraan_jabatan_ap')->nullable();
            $table->string('penyetaraan_golongan_upah_ap')->nullable();
            $table->string('id_bank');
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->string('nomor_bpjstk')->nullable();
            $table->string('nomor_bpjskesehatan')->nullable();
            $table->string('nomor_npwp')->nullable();
            $table->string('nomor_hp', 13)->nullable();
            $table->string('nomor_kontak_darurat', 13)->nullable();
            $table->string('nama_kontak_darurat')->nullable();
            $table->enum('hubungan_kontak_darurat', ["Orang Tua", "Ayah", "Ibu", "Suami", "Istri", "Saudara Kandung", "Keluarga", "Teman", "Atasan"])->nullable();

            $table->date('tmt_jabatan')->nullable();
            $table->string('email')->nullable();
            $table->string('email_dinas')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_npwp')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('nomor_str')->nullable();
            $table->enum('str_seumur_hidup', ["Ya", "Tidak"])->nullable();
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
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            // Username
            $table->string('foto')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
