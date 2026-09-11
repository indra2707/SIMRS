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
        Schema::create('tbl_ijazah', function (Blueprint $table) {
            $table->id();
            $table->string('id_pegawai');
            $table->string('nomor_ijazah');
            $table->string('institusi');
            $table->enum('pendidikan', ['D3', 'D4', 'S1', 'S2', 'S3','Profesi','Spesialis','Subspesialis']);
            $table->string('prodi');
            $table->date('tahun_lulus');
            $table->text('lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ijazah');
    }
};
