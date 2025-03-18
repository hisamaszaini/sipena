<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $fillable = [
        'nama',
        'nomor_sk',
        'nama_pimpinan',
        'no_telp',
        'kecamatan_kode',
        'alamat',
        'daerah_id',
    ];

    /**
     * Relasi ke tabel Kecamatan
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_kode', 'kode');
    }

    /**
     * Relasi ke tabel Daerah
     */
    public function daerah()
    {
        return $this->belongsTo(Daerah::class);
    }

    /**
     * Relasi ke tabel AnggotaCabang
     */
    public function anggotaCabang()
    {
        return $this->hasMany(AnggotaCabang::class);
    }
}
