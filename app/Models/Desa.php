<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desa';
    public $timestamps = false;
    protected $fillable = [
        'kode',
        'kecamatan_id',
        'nama'
    ];

    //Relasi
    public function kecamatan(){
        return $this->belongsTo(Kecamatan::class);
    }
}
