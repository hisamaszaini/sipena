<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use Illuminate\Http\Request;

class BiodataController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nik'           => 'required|string|size:18|unique:biodata,nik',
            'nba'           => 'nullable|string|max:18',
            'nama'          => 'required|string|max:50',
            'email'         => 'nullable|email|max:60',
            'no_telp'       => 'required|string|max:15|unique:biodata,no_telp',
            'jenis_kelamin' => 'required|string|in:laki-laki,perempuan',
            'agama'         => 'nullable|string|max:10',
            'kecamatan_id'  => 'required|integer|exists:kecamatan,id',
            'tempat_lahir'  => 'nullable|string|max:30',
            'tanggal_lahir' => 'nullable|date',
            'alamat_tinggal'=> 'nullable|string|max:60',
            'alamat_asal'   => 'nullable|string|max:60',
        ]);        

        $biodata = Biodata::firstOrCreate(
            ['nik' => $data['nik']],
            $data
        );

        return response()->json([
            'message' => 'Biodata berhasil disimpan',
            'data' => $biodata
        ]);
    }

    public function autocomplete(Request $request)
    {
        $nik = $request->input('nik');

        if (strlen($nik) >= 16) {
            $biodata = Biodata::where('nik', $nik)->first();

            if ($biodata) {
                return response()->json([
                    'success' => true,
                    'data'    => $biodata
                ]);
            }
        }

        return response()->json(['success' => false]);
    }
}
