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
        Schema::create('tbl_dokumen_lainnya', function (Blueprint $table) {
            $table->id();
            $table->string('id_pegawai');
            $table->enum('jenis', ['KTP', 'KK', 'NPWP', 'BPJS Kesehatan', 'BPJS Ketenagakerjaan']); 
            $table->string('nomor');           
            $table->text('catatan');
            $table->text('lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_dokumen_lainnya');
    }
};
