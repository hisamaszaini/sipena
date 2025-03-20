<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OperatorExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with('cabang')->where('role', 'operator')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Username', 'Email', 'Nomor Telpon', 'Cabang', 'Status'];
    }

    public function map($data): array
    {
        return [
            $data->name,
            $data->username,
            $data->email,
            $data->no_telp,
            $data->cabang ? $data->cabang->nama : 'Tidak Ada',
            ucwords($data->status),
        ];
    }
}
