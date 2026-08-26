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
        Schema::create('tbl_aproval_detail', function (Blueprint $table) {
            $table->id();
            $table->enum('parent_jabatan', ['0', '1', '2']);
            $table->string('id_pegawai');
            $table->string('id_unit');
            $table->string('no_surat', null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_aproval_detail');
    }
};
