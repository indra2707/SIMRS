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
        Schema::create('tbl_disposisi_jabatan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_aproval');
            $table->unsignedInteger('urutan')->default(1);
            $table->string('nama_jabatan');

            // Pemegang posisi ini SAAT INI (nullable -- jabatan bisa
            // kosong/belum ada orangnya)
            $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->unsignedBigInteger('id_unit');

            $table->timestamps();

            $table->index(['id_aproval']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
