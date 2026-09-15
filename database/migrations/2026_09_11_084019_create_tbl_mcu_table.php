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
        Schema::create('tbl_mcu', function (Blueprint $table) {
            $table->id();
            $table->string('id_pegawai');
            $table->date('tanggal');
            $table->enum('hasil', ['P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'P7']);
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
        Schema::dropIfExists('tbl_mcu');
    }
};
