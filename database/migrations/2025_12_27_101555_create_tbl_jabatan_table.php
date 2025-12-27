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
        Schema::create('tbl_jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('id_sk_struktur', 250);
            $table->enum('unit', ['RS Pertamina Royal Biringkanaya', 'Kantor Pusat PBM'])->default('RS Pertamina Royal Biringkanaya');
            $table->string('nama_jabatan', 250);
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_jabatan');
    }
};
