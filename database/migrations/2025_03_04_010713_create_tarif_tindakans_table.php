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
        Schema::create('tarif_tindakans', function (Blueprint $table) {
            $table->id();
            $table->char('kode_tarif', 20)->unique();
            $table->char('no_sk')->nullable();
            $table->string('tindakan', 255);
            $table->decimal('tarif_rs', 15, 0)->nullable();
            $table->string('kelompok_tindakan', 50)->nullable();
            $table->string('tipe', 50);
            $table->string('kategori_layanan', 50);
            $table->string('group_tindakan', 50);
            $table->enum('status_cito', ['0', '1'])->default('1')->nullable();
            $table->integer('cito')->length(11)->default(0)->comment('Satuan Persen');
            $table->enum('status', ['0', '1'])->default('1')->nullable()->comment('Status Tindakan : 0 = tidak aktif, 1 = aktif');
            $table->enum('flat', ['0', '1'])->default('1')->nullable()->comment('Jasa Medis Flat : 0 = tidak aktif, 1 = aktif');
            $table->string('status_operasi', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_tindakans');
    }
};
