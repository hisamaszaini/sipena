<?php

namespace App\Exports;

use App\Models\Ranting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RantingExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Ranting::with('desa.kecamatan')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Nomor SK', 'Pimpinan', 'No. Telpon', 'Desa', 'Kecamatan', 'Alamat'];
    }

    public function map($data): array
    {
        return [
            $data->nama,
            $data->nomor_sk,
            $data->nama_pimpinan,
            $data->no_telp,
            $data->desa->nama,
            $data->desa->kecamatan->nama,
            $data->alamat
        ];
    }
}
