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
        'kecamatan_id',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan_terakhir',
        'pendidikan_sekarang',
        'alamat_tinggal',
        'alamat_asal',
        'created_by',
    ];

    //Relasi

    public function kecamatan(){
        return $this->belongsTo(Kecamatan::class);
    }
}
