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
        Schema::create('cabang', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('nomor_sk', 30)->unique();
            $table->string('nama_pimpinan', 50);
            $table->string('no_telp', 15);
            $table->foreignId('kecamatan_id')->references('id')->on('kecamatan')->cascadeOnDelete();
            $table->string('alamat', 60);
            $table->foreignId('daerah_id')->references('id')->on('daerah')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang');
    }
};
