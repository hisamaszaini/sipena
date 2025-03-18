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
        Schema::create('anggota_cabang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->references('id')->on('cabang')->cascadeOnDelete();
            $table->foreignId('biodata_id')->references('id')->on('biodata')->cascadeOnDelete();
            $table->string('jabatan', 20)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_cabang');
    }
};
