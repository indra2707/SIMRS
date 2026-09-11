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
        Schema::create('tbl_str_sip', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('id_pegawai');
            $table->enum('jenis', ['STR', 'SIP']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir')->nullable();
            $table->enum('masa_berlaku', ['0', '1'])->nullable()->default('0');
            $table->text('lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_str_sip');
    }
};
