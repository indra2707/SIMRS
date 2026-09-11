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
        Schema::create('tbl_disposisi_surat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_surat');
            $table->unsignedBigInteger('id_aproval');

            $table->string('no_agenda')->nullable();

            // R = Rahasia, P = Penting, S = Segera, B = Biasa
            $table->enum('tingkat_surat', ['R', 'P', 'S', 'B'])->nullable();

            // Kolom "NOTE" di form
            $table->text('catatan')->nullable();

            $table->unsignedBigInteger('id_pengirim');
            $table->unsignedBigInteger('id_unit');

            $table->timestamps();

            $table->index(['id_surat']);

            $table->foreign('id_surat')
                ->references('id')->on('surat')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_disposisi_surat');
    }
};
