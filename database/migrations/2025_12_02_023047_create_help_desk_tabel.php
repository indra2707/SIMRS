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
        Schema::create('help_desk_tabel', function (Blueprint $table) {
            $table->id();
            $table->text('keterangan');
            $table->enum('status', ['accept', 'on-progress', 'done'])->default('accept');
            $table->foreignId('user_id');
            $table->string('tiket');
            $table->string('judul_laporan');
            $table->enum('kategori', ['IT', 'Medis', 'Teknik']);
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi', 'Darurat']);
            $table->text('gambar');
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_desk_tabel');
    }
};
