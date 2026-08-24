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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('no_surat', 100)->unique();
            $table->unsignedBigInteger('approval_id')->nullable();
            $table->json('lampiran')->nullable();
            $table->string('jumlah_lampiran');
            $table->string('perihal', 255);
            $table->longText('isi_surat');
            $table->enum('status', ['Draft', 'Approve']);
            $table->string('id_unit');
            $table->string('id_pegawai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
