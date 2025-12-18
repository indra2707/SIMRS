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
        Schema::create('tbl_biaya_spd', function (Blueprint $table) {
           $table->id();
            $table->string('nama', 150);
            $table->integer('harga_utama');
            $table->integer('harga_madya');
            $table->integer('harga_biasa');
            $table->enum('status', ['1', '0'])->default('1');
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
        Schema::dropIfExists('tbl_biaya_spd');
    }
};
