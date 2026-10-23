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
        Schema::create('tbl_perpustakaan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('kategori', ['TKO', 'Pedoman', 'SK', 'SPO', 'Lainnya']);
            $table->enum('status', ['verifikasi', 'belum_verifikasi'])->default('belum_verifikasi');
            $table->text('lampiran')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_perpustakaan');
    }
};
