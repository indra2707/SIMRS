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
        Schema::create('sk_tarifs', function (Blueprint $table) {
            $table->id();
            $table->char('no_sk', 100)->unique();
            $table->date('tgl_efektif_mulai');
            $table->date('tgl_efektif_akhir');
            $table->text('deskripsi');
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_tarifs');
    }
};
