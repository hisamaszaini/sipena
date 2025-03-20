<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OperatorExport;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('cabang')->where('role', 'operator');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            return DataTables::eloquent($query)->make(true);
        }

        $data = [
            'title' => 'Kelola Data Operator',
            'cabang' => Cabang::all(),
        ];

        return view('pages.admin.operator', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string',
            'username'      => 'required|string|max:60',
            'email'     => 'required|email|unique:users,email',
            'no_telp'    => 'required|string|min:10|max:15|unique:users,no_telp',
            'password'     => 'required|string|min:6|confirmed',
            'status'    => 'required|in:aktif,nonaktif',
            'cabang_id' => 'required|exists:cabang,id',
        ]);

        User::create([
            'name'      => $request->nama,
            'username'  => $request->username,
            'email'     => $request->email,
            'no_telp'     => $request->no_telp,
            'password'  => bcrypt($request->password),
            'role'      => 'operator',
            'status'    => $request->status,
            'cabang_id'  => $request->cabang_id,
        ]);

        return response()->json(['success' => 'Operator berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $operator = User::with('cabang')->findOrFail($id);
        return response()->json($operator);
    }

    public function update(Request $request, $id)
    {
        $operator = User::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string',
            'username' => 'required|string|max:60|unique:users,username,' . $id,
            'email'    => 'required|email|unique:users,email,' . $id,
            'no_telp'    => 'required|string|min:10|max:15|unique:users,no_telp,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'status'   => 'required|in:aktif,nonaktif',
            'cabang_id' => 'required|exists:cabang,id',
        ]);

        $data = [
            'name'     => $request->input('nama'),
            'username' => $request->username,
            'email'    => $request->email,
            'no_telp'   => $request->no_telp,
            'status'   => $request->status,
            'cabang_id'  => $request->cabang_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $operator->update($data);

        return response()->json(['success' => 'Operator berhasil diperbarui']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => 'Operator berhasil dihapus']);
    }

    public function export()
    {
        return Excel::download(new OperatorExport, 'operator.xlsx');
    }
}
