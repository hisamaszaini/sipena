<?php

namespace App\Http\Controllers;

use App\Exports\CabangExport;
use App\Models\Cabang;
use App\Models\Kecamatan;
use App\Models\Daerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role == "admin") {
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

        if ($user->role == "operator") {
            $data = [
                'title' => 'Kelola Data Cabang',
                'kecamatan' => Kecamatan::select('id', 'nama')->get(),
                'daerah' => Daerah::select('id', 'nama')->get(),
                'cabang' => Cabang::findOrFail($user->cabang_id),
            ];

            return view('pages.operator.cabang', $data);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Anda tidak memiliki izin untuk menambahkan cabang'], 403);
        }

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
        $user = Auth::user();

        $query = Cabang::with(['kecamatan', 'daerah']);
        if ($user->role == 'operator') {
            $query->where('id', $user->cabang_id);
        }

        $cabang = $query->findOrFail($id);
        return response()->json($cabang);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $query = Cabang::query();
        if ($user->role == 'operator') {
            $query->where('id', $user->cabang_id);
        }

        $cabang = $query->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|unique:cabang,nama,' . $id,
            'nomor_sk' => 'required|string|unique:cabang,nomor_sk,' . $id,
            'nama_pimpinan' => 'required|string',
            'no_telp' => 'required|string|min:10|max:15|unique:cabang,no_telp,' . $id,
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'alamat' => 'required|string|max:60',
            'daerah_id' => 'required|exists:daerah,id',
        ]);

        $cabang->update($request->only(['nama', 'nomor_sk', 'nama_pimpinan', 'no_telp', 'kecamatan_id', 'alamat', 'daerah_id']));

        return response()->json(['success' => 'Cabang berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            Cabang::findOrFail($id)->delete();
            return response()->json(['success' => 'Cabang berhasil dihapus']);
        }

        return response()->json(['error' => 'Anda tidak memiliki izin untuk menghapus cabang'], 403);
    }

    public function export()
    {
        return Excel::download(new CabangExport, 'cabang.xlsx');
    }
}
