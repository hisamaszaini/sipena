<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaDaerah extends Model
{
    use HasFactory;

    protected $table = 'anggota_daerah';

    protected $fillable = [
        'daerah_id',
        'biodata_id',
        'jabatan',
        'status'
    ];

    //Relasi
    public function daerah(){
        return $this->belongsTo(Daerah::class);
    }

    public function biodata(){
        return $this->belongsTo(Biodata::class);
    }
}
