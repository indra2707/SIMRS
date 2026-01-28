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
        Schema::create('tbl_permintaan', function (Blueprint $table) {
            $table->id();
            $table->text('id_unit')->nullable(); 
            $table->string('no_agenda')->nullable();
            $table->string('no_surat')->nullable();
            $table->string('nama_permintaan')->nullable();
            $table->text('catatan')->nullable(); 
            $table->date('tgl')->nullable();
            $table->text('tembusan')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('tbl_permintaan');
    }
};
