<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daerah extends Model
{
    use HasFactory;

    protected $table = 'daerah';

    protected $fillable = [
        'nama',
        'nomor_sk',
        'nama_pimpinan',
        'no_telp',
        'provinsi_id',
        'kabupaten_id',
        'alamat',
    ];

    //relasi
    public function provinsi(){
        return $this->belongsTo(Provinsi::class);
    }

    public function kabupaten(){
        return $this->belongsTo(Kabupaten::class);
    }
}
