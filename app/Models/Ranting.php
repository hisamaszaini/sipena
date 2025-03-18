<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranting extends Model
{
    protected $table = 'ranting';

    protected $fillable = [
        'nama',
        'nomor_sk',
        'nama_pimpinan',
        'no_telp',
        'desa_kode',
        'alamat',
        'cabang_id'
    ];

    //relasi
    public function desa(){
        return $this->belongsTo(Desa::class);
    }

    public function cabang(){
        return $this->belongsTo(Cabang::class);
    }
}