<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaCabang extends Model
{
    use HasFactory;

    protected $table = 'anggota_cabang';

    protected $fillable = [
        'cabang_id',
        'biodata_id',
        'jabatan',
        'status'
    ];

    //Relasi
    public function cabang(){
        return $this->belongsTo(Cabang::class);
    }

    public function biodata(){
        return $this->belongsTo(Biodata::class);
    }
    
}