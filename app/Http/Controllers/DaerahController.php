<?php

namespace App\Http\Controllers;

use App\Models\Daerah;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\Request;

class DaerahController extends Controller
{
    public function index()
    {
        $daerah = Daerah::with(['provinsi', 'kabupaten'])->first();

        $provinsi = Provinsi::all();
        $kabupaten = Kabupaten::where('provinsi_id', $daerah->provinsi_id ?? null)->get();
        $title = 'Kelola Data Daerah';

        return view('pages.admin.daerah', compact('daerah', 'provinsi', 'kabupaten', 'title'));
    }

    public function edit()
    {
        $daerah = Daerah::with(['provinsi', 'kabupaten'])->firstOrFail();
        return response()->json($daerah);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'nomor_sk' => 'required|string',
            'nama_pimpinan' => 'required|string',
            'no_telp' => 'required|string|min:10|max:15',
            'provinsi_id' => 'required|exists:provinsi,id',
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'alamat' => 'required|string|max:60',
        ]);

        $daerah = Daerah::firstOrFail();
        $daerah->update($request->only([
            'nama', 'nomor_sk', 'nama_pimpinan', 'no_telp', 'provinsi_id', 'kabupaten_id', 'alamat'
        ]));

        return response()->json(['success' => 'Data daerah berhasil diperbarui']);
    }
}
