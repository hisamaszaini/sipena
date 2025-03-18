<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';

    protected $fillable = [
        'kode',
        'provinsi_kode',
        'nama'
    ];

    //Relasi
    public function provinsi(){
        return $this->belongsTo(Provinsi::class);
    }
}
