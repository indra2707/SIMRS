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
        Schema::create('tbl_spk_rkk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('id_pegawai');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->text('lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_spk_rkk');
    }
};
