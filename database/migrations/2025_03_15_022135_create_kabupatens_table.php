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
        Schema::create('kabupaten', function (Blueprint $table) {
            $table->string('kode', 4)->primary();
            $table->string('provinsi_kode', 2);
            $table->foreign('provinsi_kode')->references('kode')->on('provinsi')->cascadeOnDelete();
            $table->string('nama', 60);
        });

        DB::table('kabupaten')->insert([
            'kode' => 3502,
            'provinsi_kode' => 35,
            'nama' => 'KABUPATEN PONOROGO',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kabupaten');
    }
};
