<?php

namespace App\Http\Controllers;

use App\Exports\RantingExport;
use App\Models\Ranting;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RantingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            $query = Ranting::with(['desa:id,nama,kecamatan_id', 'cabang:id,nama']);

            $desaIds = [];

            if ($request->filled('kecamatan_id')) {
                $desaIds = Desa::where('kecamatan_id', $request->kecamatan_id)->pluck('id')->toArray();
                $query->whereIn('desa_id', $desaIds);
            }

            if ($user->role == "operator") {
                $query->where('cabang_id', $user->cabang_id);
            }

            return DataTables::eloquent($query)->make(true);
        }

        if ($user->role == "admin") {
            $data = [
                'title' => 'Kelola Data Ranting',
                'kecamatan' => Kecamatan::select('id', 'nama')->get(),
                'desa' => Desa::select('id', 'nama')->get(),
                'cabang' => Cabang::select('id', 'nama')->get(),
            ];
        } else {
            $data = [
                'title' => 'Kelola Data Ranting',
                'desa' => Desa::select('id', 'nama')->where('kecamatan_id', $user->cabang->kecamatan_id)->get(),
                'user' => $user,
            ];
        }
        return view(($user->role == 'admin' ? 'pages.admin.ranting' : 'pages.operator.ranting'), $data);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Anda tidak memiliki izin untuk menambahkan cabang'], 403);
        }

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
        $user = Auth::user();
        $query = Ranting::with('desa.kecamatan');
        if($user->role == "operator"){
            $query = $query->where('cabang_id', $user->cabang_id);
            $data = $query->get();
            return Excel::download(new RantingExport($data), "ranting_cabang_$user->cabang_id.xlsx");
        }
        $data = $query->get();
        return Excel::download(new RantingExport($data), 'ranting.xlsx');
    }
}
