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
            $table->id();
            $table->string('kode', 4)->unique();
            $table->foreignId('provinsi_id')->references('id')->on('provinsi')->cascadeOnDelete();
            $table->string('nama', 60);
        });

        DB::table('kabupaten')->insert([
            'kode' => 3502,
            'provinsi_id' => 1,
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
