<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaRanting extends Model
{
    use HasFactory;

    protected $table = 'anggota_ranting';

    protected $fillable = [
        'ranting_id',
        'biodata_id',
        'jabatan',
        'status'
    ];

    //Relasi
    public function ranting(){
        return $this->belongsTo(Ranting::class);
    }

    public function biodata(){
        return $this->belongsTo(Biodata::class);
    }
}
