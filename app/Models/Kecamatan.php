<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';

    protected $fillable = [
        'kode',
        'kabupaten_kode',
        'nama'
    ];

    //relasi
    public function kabupaten(){
        return $this->belongsTo(Kabupaten::class);
    }
}
