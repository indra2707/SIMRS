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
        Schema::create('sapphire', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->nullable();
            $table->string('userid')->unique();
            $table->string('name');
            $table->string('card_number')->nullable();
            $table->integer('role')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sapphire');
    }
};
