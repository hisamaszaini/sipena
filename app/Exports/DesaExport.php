<?php

namespace App\Exports;

use App\Models\Desa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DesaExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return Desa::with('kecamatan.kabupaten')->get();
    }

    public function headings(): array
    {
        return ['Kode', 'Nama Desa', 'Nama Kecamatan', 'Nama Kabupaten'];
    }

    public function map($desa): array
    {
        return [
            $desa->kode,
            $desa->nama,
            $desa->kecamatan ? $desa->kecamatan->nama : 'Tidak Diketahui',
            $desa->kecamatan && $desa->kecamatan->kabupaten ? $desa->kecamatan->kabupaten->nama : 'Tidak Diketahui',
        ];
    }
}

