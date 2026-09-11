<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_sertifikat', function (Blueprint $table) {
            $table->id();
            $table->string('id_pegawai');
            $table->string('nama');
            $table->string('penyelenggara');
            $table->year('tahun');
            $table->enum('jenis', ['Pelatihan', 'Sertifikat']);
            $table->text('lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_sertifikat');
    }
};
