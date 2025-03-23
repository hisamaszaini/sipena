<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kabupaten;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KecamatanExport;

class KecamatanController extends Controller
{
    public function index(Request $request)
    {
        $kabupaten = Kabupaten::all();

        if ($request->ajax()) {
            $query = Kecamatan::with('kabupaten');

            if ($request->has('kabupaten_id') && !empty($request->kabupaten_id)) {
                $query->where('kabupaten_id', $request->kabupaten_id);
            }

            return DataTables::of($query)->make(true);
        }

        $data = [
            'title' => 'Kelola Data Kecamatan',
            'kabupaten' => $kabupaten,
        ];

        return view('pages.admin.kecamatan', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:7|unique:kecamatan,kode',
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'nama' => 'required|string|max:50',
        ]);

        Kecamatan::create($request->only(['kode', 'kabupaten_id', 'nama']));

        return response()->json(['success' => 'Kecamatan berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $kecamatan = Kecamatan::with('kabupaten')->findOrFail($id);
        return response()->json($kecamatan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:7|unique:kecamatan,kode,' . $id,
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'nama' => 'required|string|max:50',
        ]);

        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->update($request->only(['kode', 'kabupaten_id', 'nama']));

        return response()->json(['success' => 'Kecamatan berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Kecamatan::findOrFail($id)->delete();
        return response()->json(['success' => 'Kecamatan berhasil dihapus']);
    }

    public function export()
    {
        return Excel::download(new KecamatanExport, 'kecamatan.xlsx');
    }
}
