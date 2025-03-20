<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('role', 'admin')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Username', 'Email', 'Nomor Telpon', 'Status'];
    }

    public function map($data): array
    {
        return [
            $data->name,
            $data->username,
            $data->email,
            $data->no_telp,
            ucwords($data->status),
        ];
    }
}
