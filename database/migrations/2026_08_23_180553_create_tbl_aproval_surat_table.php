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
        Schema::create('tbl_aproval_surat', function (Blueprint $table) {
           $table->id();
            $table->string('id_surat');
            $table->string('id_aproval');
            $table->string('parent_jabatan');
            $table->string('id_pegawai');
            $table->string('id_unit');
            $table->dateTime('tanggal_aproval')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_aproval_surat');
    }
};
