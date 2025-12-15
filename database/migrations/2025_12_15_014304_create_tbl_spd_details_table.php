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
        Schema::create('tbl_spd_details', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 50)->nullable();
            $table->integer('id_pegawai')->nullable();
            $table->integer('id_mengajukan')->nullable();
            $table->integer('id_menyetujui')->nullable();
            $table->enum('jenis', ['Panjar', 'SP3'])->nullable();
            $table->enum('status', ['Draft', 'Close']);
            $table->decimal('panjar', 10, 0)->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('created_by', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_spd_details');
    }
};
