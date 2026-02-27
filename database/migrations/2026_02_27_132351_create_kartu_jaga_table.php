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
        Schema::create('kartu_jaga', function (Blueprint $table) {
            $table->id();
            $table->string('card_number');
            $table->string('nama_pasien');
            $table->string('nama');
            $table->string('no_hp');
            $table->string('ruangan');
            $table->string('no_kartu');
            $table->decimal('deposit', 10, 2);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_jaga');
    }
};
