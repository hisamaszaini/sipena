<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminExport;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('role', 'admin');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            return DataTables::eloquent($query)->make(true);
        }

        $data = [
            'title' => 'Kelola Data Admin',
        ];

        return view('pages.admin.admin', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string',
            'username'      => 'required|string|max:60',
            'email'     => 'required|email|unique:users,email',
            'no_telp'    => 'required|string|min:10|max:15|unique:users,no_telp',
            'password'     => 'required|string|min:8|confirmed',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        User::create([
            'name'      => $request->nama,
            'username'  => $request->username,
            'email'     => $request->email,
            'no_telp'     => $request->no_telp,
            'password'  => bcrypt($request->password),
            'role'      => 'admin',
            'status'    => $request->status
        ]);

        return response()->json(['success' => 'Admin berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string',
            'username' => 'required|string|max:60|unique:users,username,' . $id,
            'email'    => 'required|email|unique:users,email,' . $id,
            'no_telp'    => 'required|string|min:10|max:15|unique:users,no_telp,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'status'   => 'required|in:aktif,nonaktif',
        ]);

        $data = [
            'name'     => $request->input('nama'),
            'username' => $request->username,
            'email'    => $request->email,
            'no_telp'   => $request->no_telp,
            'status'   => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);

        return response()->json(['success' => 'Admin berhasil diperbarui']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => 'Admin berhasil dihapus']);
    }

    public function export()
    {
        return Excel::download(new AdminExport, 'admin.xlsx');
    }
}
