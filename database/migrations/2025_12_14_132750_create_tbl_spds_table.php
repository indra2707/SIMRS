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
        Schema::create('tbl_spds', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 50)->unique();
            $table->integer('id_pegawai');
            $table->enum('pelaksanaan', [
                'PD-DN',
                'PD-LN',
                'SIJ',
                'Mutasi',
                'Cuti'
            ]);

            $table->integer('id_kota1');
            $table->integer('id_kota2');

            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->date('tgl_masuk');

            $table->enum('kendaraan', [
                'Pesawat',
                'Kereta',
                'Kapal Laut',
                'Bus',
                'Mobil'
            ]);

            $table->enum('ditanggung', [
                'Perusahaan',
                'Pribadi'
            ]);

            $table->string('hak_cuti', 100)->nullable();
            $table->string('cuti_lalu', 100)->nullable();
            $table->string('jatuh_tempo', 100)->nullable();
            $table->string('panjar_cuti', 100)->nullable();
            $table->string('keterangan', 300);

            $table->integer('id_pimpinan');
            $table->enum('pengikut', ['0', '1']);

            $table->enum('status', [
                'Draft',
                'Close'
            ]);

            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_at', 100);

            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_spds');
    }
};
