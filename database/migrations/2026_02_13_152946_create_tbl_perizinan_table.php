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
        Schema::create('tbl_perizinan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_perizinan');
            $table->string('jenis_perizinan');
            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->string('upload');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_perizinan');
    }
};
