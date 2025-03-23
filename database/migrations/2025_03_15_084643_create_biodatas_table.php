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
        Schema::create('biodata', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nba', 18)->nullable();
            $table->string('nama', 50);
            $table->string('email', 60)->nullable();
            $table->string('no_telp', 15)->unique();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('agama', 10)->nullable();
            $table->foreignId('kecamatan_id')->references('id')->on('kecamatan')->cascadeOnDelete();
            $table->string('tempat_lahir', 30)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan_terakhir', 30)->nullable();
            $table->string('pendidikan_sekarang', 30)->nullable();
            $table->string('alamat_tinggal', 60)->nullable();
            $table->string('alamat_asal', 60)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodata');
    }
};
