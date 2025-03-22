<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnggotaRantingExport implements FromCollection, WithMapping, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($data): array
    {        
        return [
            $data->biodata->nba,
            $data->biodata->nama,
            $data->ranting->nama,
            $data->jabatan,
            $data->biodata->no_telp,
            $data->biodata->jenis_kelamin,
            $data->biodata->tempat_lahir,
            $data->biodata->tanggal_lahir,
            $data->biodata->pendidikan_terakhir,
            $data->biodata->alamat_tinggal,
            $data->status,
        ];
    }

    public function headings(): array
    {
        return [
            'NBA', 
            'Nama', 
            'Ranting', 
            'Jabatan', 
            'No. Telpon', 
            'Jenis Kelamin', 
            'Tempat Lahir', 
            'Tanggal Lahir', 
            'Pendidikan Terakhir',
            'Alamat Tinggal', 
            'Status'
        ];
    }
}
