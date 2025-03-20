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
        Schema::create('ranting', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('nomor_sk', 30)->unique();
            $table->string('nama_pimpinan', 50);
            $table->string('no_telp', 15);
            $table->foreignId('desa_id')->references('id')->on('desa')->cascadeOnDelete();
            $table->string('alamat', 60);
            $table->foreignId('cabang_id')->references('id')->on('cabang')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranting');
    }
};
