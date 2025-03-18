<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    use HasFactory;

    protected $table = 'biodata';

    protected $fillable = [
        'nik',
        'nba',
        'nama',
        'email',
        'no_telp',
        'jenis_kelamin',
        'agama',
        'kecamatan_kode',
        'templat_lahir',
        'tanggal_lahir',
        'alamat_tinggal',
        'alamat_asal'
    ];

    //Relasi

    public function kecamatan(){
        return $this->belongsTo(Kecamatan::class);
    }
}
