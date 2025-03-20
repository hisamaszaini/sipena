<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('desa', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->foreignId('kecamatan_id')->references('id')->on('kecamatan')->cascadeOnDelete();
            $table->string('nama', 50);
        });

        DB::table('desa')->insert([
            'kode' => 3502070009,
            'kecamatan_id' => 1,
            'nama' => 'SIDOHARJO'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};
