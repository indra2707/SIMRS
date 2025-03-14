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
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_petugas', 50)->unique();
            $table->string('nama', 100);
            $table->string('nik', 50)->unique();
            $table->enum('jenis_kelamin', allowed: ['L', 'P']);
            $table->string('status_petugas', length: 100)->nullable();
            $table->string('no_hp', length: 30);
            $table->text('alamat');
            $table->string('kode_bpjs', 100)->unique();
            $table->string('kategori', 100)->nullable();
            $table->string('no_sip', 100)->nullable();
            $table->date('masa_berlaku_sip')->nullable();
            $table->string('kode_spesialis', 100)->nullable();
            $table->string('tindakan_konsul', 100)->nullable();
            $table->string('tindakan_visite', 100)->nullable();
            $table->text(column: 'foto')->nullable();
            $table->text(column: 'signatures')->nullable()->comment('Tanda tangan petugas');
            $table->enum('status', allowed: ['1', '0'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
