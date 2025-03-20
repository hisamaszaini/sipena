<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';
    public $timestamps = false; 
    protected $fillable = [
        'kode',
        'provinsi_id',
        'nama'
    ];

    //Relasi
    public function provinsi(){
        return $this->belongsTo(Provinsi::class);
    }
}
