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
        Schema::create('daerah', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('nomor_sk', 30)->unique();
            $table->string('nama_pimpinan', 50);
            $table->string('no_telp', 15);
            $table->string('provinsi_kode', 2);
            $table->foreign('provinsi_kode')->references('kode')->on('provinsi')->cascadeOnDelete();
            $table->string('kabupaten_kode', 4);
            $table->foreign('kabupaten_kode')->references('kode')->on('kabupaten')->cascadeOnDelete();
            $table->string('alamat', 60);
            $table->timestamps();
        });

        DB::table('daerah')->insert([
            'nama' => 'Ponorogo',
            'nomor_sk' => 'nomornya',
            'nama_pimpinan' => 'pimpinan',
            'no_telp' => '08372617213',
            'provinsi_kode' => '35',
            'kabupaten_kode' => '3502',
            'alamat' => 'Jalan Ponorogo Raya',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daerah');
    }
};
