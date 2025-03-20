<?php

namespace App\Exports;

use App\Models\Cabang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CabangExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Cabang::with('kecamatan')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Nomor SK', 'Pimpinan', 'No. Telpon', 'Kecamatan', 'Alamat'];
    }

    public function map($data): array
    {
        return [
            $data->nama,
            $data->nomor_sk,
            $data->nama_pimpinan,
            $data->no_telp,
            $data->kecamatan->nama,
            $data->alamat
        ];
    }
}
