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
        Schema::create('anggota_daerah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daerah_id')->references('id')->on('daerah')->cascadeOnDelete();
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
        Schema::dropIfExists('anggota_daerah');
    }
};
