<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desa';

    protected $fillable = [
        'kode',
        'kecamatan_kode',
        'nama'
    ];

    //Relasi
    public function kecamatan(){
        return $this->belongsTo(Kecamatan::class);
    }
}
