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
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 7)->unique();
            $table->foreignId('kabupaten_id')->references('id')->on('kabupaten')->cascadeOnDelete();
            $table->string('nama', 50);
        });

        DB::table('kecamatan')->insert([
            'kode' => 3502070,
            'kabupaten_id' => 1,
            'nama' => 'PULUNG',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
