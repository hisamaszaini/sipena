<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DesaExport;
use Illuminate\Support\Facades\Auth;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $kecamatan = Kecamatan::all();

        if ($request->ajax()) {
            $query = Desa::with('kecamatan');

            if ($request->has('kecamatan_id') && !empty($request->kecamatan_id)) {
                $query->where('kecamatan_id', $request->kecamatan_id);
            }

            return DataTables::of($query)->make(true);
        }

        $data = [
            'title' => 'Kelola Data Desa',
            'kecamatan' => $kecamatan,
        ];

        return view($user == "admin" ? 'pages.admin.desa' : 'pages.operator.desa', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:desa,kode',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'nama' => 'required|string|max:50',
        ]);

        Desa::create($request->only(['kode', 'kecamatan_id', 'nama']));

        return response()->json(['success' => 'Desa berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $desa = Desa::with('kecamatan')->findOrFail($id);
        return response()->json($desa);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:desa,kode,' . $id,
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'nama' => 'required|string|max:50',
        ]);

        $desa = Desa::findOrFail($id);
        $desa->update($request->only(['kode', 'kecamatan_id', 'nama']));

        return response()->json(['success' => 'Desa berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Desa::findOrFail($id)->delete();
        return response()->json(['success' => 'Desa berhasil dihapus']);
    }

    public function export()
    {
        return Excel::download(new DesaExport, 'desa.xlsx');
    }
}
