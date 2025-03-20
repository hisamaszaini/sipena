<?php

namespace App\Http\Controllers;

use App\Exports\RantingExport;
use App\Models\Ranting;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RantingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Ranting::with(['desa:id,nama,kecamatan_id', 'cabang:id,nama']);
    
            $desaIds = [];

            if ($request->filled('kecamatan_id')) {
                $desaIds = Desa::where('kecamatan_id', $request->kecamatan_id)->pluck('id')->toArray();
                $query->whereIn('desa_id', $desaIds);
            }
    
            return DataTables::eloquent($query)->make(true);
        }
    
        $data = [
            'title' => 'Kelola Data Ranting',
            'kecamatan' => Kecamatan::select('id', 'nama')->get(),
            'desa' => Desa::select('id', 'nama')->get(),
            'cabang' => Cabang::select('id', 'nama')->get(),
        ];
    
        return view('pages.admin.ranting', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:ranting,nama',
            'nomor_sk' => 'required|string|unique:ranting,nomor_sk',
            'nama_pimpinan' => 'required|string',
            'no_telp' => 'required|string|min:10|max:15|unique:ranting,no_telp',
            'desa_id' => 'required|exists:desa,id',
            'alamat' => 'required|string|max:60',
            'cabang_id' => 'required|exists:cabang,id',
        ]);

        Ranting::create($request->only(['nama', 'nomor_sk', 'nama_pimpinan', 'no_telp', 'desa_id', 'alamat', 'cabang_id']));

        return response()->json(['success' => 'Ranting berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $ranting = Ranting::with(['desa', 'cabang'])->findOrFail($id);
        return response()->json($ranting);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|unique:ranting,nama,' . $id,
            'nomor_sk' => 'required|string|unique:ranting,nomor_sk,' . $id,
            'nama_pimpinan' => 'required|string',
            'no_telp' => 'required|string|min:10|max:15|unique:ranting,no_telp,' . $id,
            'desa_id' => 'required|exists:desa,id',
            'alamat' => 'required|string|max:60',
            'cabang_id' => 'required|exists:cabang,id',
        ]);

        $ranting = Ranting::findOrFail($id);
        $ranting->update($request->only(['nama', 'nomor_sk', 'nama_pimpinan', 'no_telp', 'desa_id', 'alamat', 'cabang_id']));

        return response()->json(['success' => 'Ranting berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Ranting::findOrFail($id)->delete();
        return response()->json(['success' => 'Ranting berhasil dihapus']);
    }

    public function export()
    {
        return Excel::download(new RantingExport, 'ranting.xlsx');
    }
}
