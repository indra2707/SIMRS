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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_surat');
            $table->unsignedBigInteger('id_unit');


            $table->unsignedBigInteger('id_pengirim');


            $table->unsignedBigInteger('id_penerima');


            $table->text('catatan')->nullable();


            $table->enum('status', ['Menunggu', 'Dibaca', 'Selesai'])
                ->default('Menunggu');

            $table->timestamp('tanggal_dibaca')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();


            $table->text('catatan_tindak_lanjut')->nullable();

            $table->timestamps();

            $table->index(['id_surat']);
            $table->index(['id_penerima', 'id_unit']);

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
        Schema::dropIfExists('disposisi');
    }
};
