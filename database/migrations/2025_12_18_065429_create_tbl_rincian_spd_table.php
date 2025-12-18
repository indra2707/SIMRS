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
        Schema::create('tbl_rincian_spd', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 150);
            $table->integer('id_pegawai');
            $table->integer('id_biaya');
            $table->integer('harga');
            $table->integer('jumlah');
            $table->timestamps();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_rincian_spd');
    }
};
