<?php

namespace App\Http\Controllers;

use App\Exports\CabangExport;
use App\Models\Cabang;
use App\Models\Kecamatan;
use App\Models\Daerah;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Cabang::with(['kecamatan:id,nama', 'daerah:id,nama']);
    
            if ($request->filled('kecamatan_id')) {
                $query->where('kecamatan_id', $request->kecamatan_id);
            }
    
            return DataTables::eloquent($query)->make(true);
        }
    
        $data = [
            'title' => 'Kelola Data Cabang',
            'kecamatan' => Kecamatan::select('id', 'nama')->get(),
            'daerah' => Daerah::select('id', 'nama')->get(),
        ];
    
        return view('pages.admin.cabang', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:cabang,nama',
            'nomor_sk' => 'required|string|unique:cabang,nomor_sk',
            'nama_pimpinan' => 'required|string',
            'no_telp' => 'required|string|min:10|max:15|unique:cabang,no_telp',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'alamat' => 'required|string|max:60',
            'daerah_id' => 'required|exists:daerah,id',
        ]);

        Cabang::create($request->only(['nama', 'nomor_sk', 'nama_pimpinan', 'no_telp', 'kecamatan_id', 'alamat', 'daerah_id']));

        return response()->json(['success' => 'Cabang berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $cabang = Cabang::with(['kecamatan', 'daerah'])->findOrFail($id);
        return response()->json($cabang);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|unique:cabang,nama,' . $id,
            'nomor_sk' => 'required|string|unique:cabang,nomor_sk,' . $id,
            'nama_pimpinan' => 'required|string',
            'no_telp' => 'required|string|min:10|max:15|unique:cabang,no_telp,' . $id,
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'alamat' => 'required|string|max:60',
            'daerah_id' => 'required|exists:daerah,id',
        ]);

        $cabang = Cabang::findOrFail($id);
        $cabang->update($request->only(['nama', 'nomor_sk', 'nama_pimpinan', 'no_telp', 'kecamatan_id', 'alamat', 'daerah_id']));

        return response()->json(['success' => 'Cabang berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Cabang::findOrFail($id)->delete();
        return response()->json(['success' => 'Cabang berhasil dihapus']);
    }

    public function export()
    {
        return Excel::download(new CabangExport, 'cabang.xlsx');
    }
}
