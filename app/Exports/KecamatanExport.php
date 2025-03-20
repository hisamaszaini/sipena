<?php

namespace App\Exports;

use App\Models\Kecamatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KecamatanExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return Kecamatan::with('kabupaten')->get();
    }

    public function headings(): array
    {
        return ['Kode', 'Nama Kecamatan', 'Nama Kabupaten'];
    }

    public function map($kecamatan): array
    {
        return [
            $kecamatan->kode,
            $kecamatan->nama,
            $kecamatan->kabupaten ? $kecamatan->kabupaten->nama : 'Tidak Diketahui',
        ];
    }
}

