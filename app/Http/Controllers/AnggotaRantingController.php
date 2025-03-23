<?php

namespace App\Http\Controllers;

use App\Exports\AnggotaRantingExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AnggotaCabang;
use App\Models\Biodata;
use App\Models\AnggotaDaerah;
use App\Models\AnggotaRanting;
use App\Models\Cabang;
use App\Models\Ranting;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AnggotaRantingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($request->ajax()) {
            $query = AnggotaRanting::with('biodata', 'ranting');

            if ($request->has('cabang_id') && !empty($request->cabang_id)) {
                $query->where('cabang_id', $request->cabang_id);
            }

            if($user->role == "operator"){
                $rantingIds = [];
                $rantingIds = Ranting::where('cabang_id', $user->cabang_id)->pluck('id')->toArray();
                $query->whereIn('ranting_id', $rantingIds);
            }

            return DataTables::of($query)->make(true);
        }

        $kecamatan = Kecamatan::all();
        if($user->role == "admin"){
            $ranting = Ranting::all();
        } else {
            $ranting = Ranting::where('cabang_id', $user->cabang_id)->get();
        }

        $data = [
            'title' => 'Kelola Data Anggota Ranting',
            'ranting' => $ranting,
            'kecamatan' => $kecamatan,
        ];

        return view(($user->role == "admin" ? 'pages.admin.anggotaranting' : 'pages.operator.anggotaranting'), $data);

    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $biodataExists = Biodata::where('nik', $request->nik)->exists();

        $nikRules = $biodataExists ? 'required|string|max:18' : 'required|string|max:18|unique:biodata,nik';
        $noTelpRules = $biodataExists ? 'required|string|max:15' : 'required|string|max:15|unique:biodata,no_telp';

        $request->validate([
            // Validasi biodata
            'nik'            => $nikRules,
            'nba'            => 'nullable|string|max:18',
            'nama'           => 'required|string|max:50',
            'email'          => 'nullable|email|max:60',
            'no_telp'        => $noTelpRules,
            'jenis_kelamin'  => 'required|string|in:laki-laki,perempuan',
            'agama'          => 'nullable|string|max:10',
            'kecamatan_id'   => 'required|integer|exists:kecamatan,id',
            'tempat_lahir'   => 'nullable|string|max:30',
            'tanggal_lahir'  => 'nullable|date',
            'pendidikan_terakhir'  => 'nullable|string|max:30',
            'pendidikan_sekarang'  => 'nullable|string|max:30',
            'alamat_tinggal' => 'nullable|string|max:60',
            'alamat_asal'    => 'nullable|string|max:60',

            // Validasi anggota ranting
            'ranting_id'     => 'required|integer|exists:ranting,id',
            'jabatan'        => 'required|string',
            'status'         => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $biodataData = $request->only([
                'nik',
                'nba',
                'nama',
                'email',
                'no_telp',
                'jenis_kelamin',
                'agama',
                'kecamatan_id',
                'tempat_lahir',
                'tanggal_lahir',
                'pendidikan_terakhir',
                'pendidikan_sekarang',
                'alamat_tinggal',
                'alamat_asal'
            ]);

            if ($user->role === 'operator') {
                $biodataData['created_by'] = $user->id();
            }

            $biodata = Biodata::firstOrCreate(['nik' => $biodataData['nik']], $biodataData);

            $anggotaData = $request->only(['ranting_id', 'jabatan', 'status']);
            $anggotaData['biodata_id'] = $biodata->id;

            $anggotaRanting = AnggotaRanting::create($anggotaData);

            DB::commit();
            return response()->json([
                'message' => 'Anggota ranting berhasil disimpan beserta biodata',
                'data'    => $anggotaRanting->load('biodata', 'ranting')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $anggotaRanting = AnggotaRanting::with('biodata', 'ranting')->findOrFail($id);
        return response()->json($anggotaRanting);
    }

    public function update(Request $request, $id)
    {
        $anggotaRanting = AnggotaRanting::findOrFail($id);

        $request->validate([
            // Validasi biodata
            'nik'           => 'required|string|max:18|unique:biodata,nik,' . $anggotaRanting->biodata_id,
            'nba'           => 'nullable|string|max:18',
            'nama'          => 'required|string|max:50',
            'email'         => 'nullable|email|max:60',
            'no_telp'       => 'required|string|max:15|unique:biodata,no_telp,' . $anggotaRanting->biodata_id,
            'jenis_kelamin' => 'required|string|in:laki-laki,perempuan',
            'agama'         => 'nullable|string|max:10',
            'kecamatan_id'  => 'required|integer|exists:kecamatan,id',
            'tempat_lahir'  => 'nullable|string|max:30',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_terakhir'  => 'nullable|string|max:30',
            'pendidikan_sekarang'  => 'nullable|string|max:30',
            'alamat_tinggal' => 'nullable|string|max:60',
            'alamat_asal'   => 'nullable|string|max:60',

            // Validasi anggota ranting
            'ranting_id'     => 'required|integer|exists:ranting,id',
            'jabatan'       => 'required|string',
            'status'        => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Update biodata
            $biodataData = $request->only([
                'nik',
                'nba',
                'nama',
                'email',
                'no_telp',
                'jenis_kelamin',
                'agama',
                'kecamatan_id',
                'tempat_lahir',
                'tanggal_lahir',
                'pendidikan_terakhir',
                'pendidikan_sekarang',
                'alamat_tinggal',
                'alamat_asal',
            ]);
            $anggotaRanting->biodata()->update($biodataData);

            // Update data anggota ranting
            $anggotaData = $request->only(['ranting_id', 'jabatan', 'status']);
            $anggotaRanting->update($anggotaData);

            DB::commit();
            return response()->json([
                'message' => 'Anggota ranting dan biodata berhasil diperbarui',
                'data'    => $anggotaRanting->load('biodata', 'ranting')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $anggotaRanting = AnggotaRanting::findOrFail($id);
            $biodataId = $anggotaRanting->biodata_id;

            $anggotaRanting->delete();

            $countAnggotaCabang = AnggotaCabang::where('biodata_id', $biodataId)->count();
            $countAnggotaDaerah = AnggotaDaerah::where('biodata_id', $biodataId)->count();
            $countAnggotaRanting = AnggotaRanting::where('biodata_id', $biodataId)->count();

            if (($countAnggotaCabang + $countAnggotaDaerah + $countAnggotaRanting) === 0) {
                Biodata::find($biodataId)->delete();
            }

            DB::commit();
            return response()->json([
                'message' => 'Anggota ranting berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function export()
    {
        $user = Auth::user();
        $query = AnggotaRanting::with(['ranting', 'biodata']);

        if ($user->role == 'operator') {
            $rantingIds = [];
            $rantingIds = Ranting::where('cabang_id', $user->cabang_id)->pluck('id')->toArray();
            $query->whereIn('ranting_id', $rantingIds);
            $data = $query->get();
            return Excel::download(new AnggotaRantingExport($data), "anggotaranting_cabang_$user->cabang_id.xlsx");
        } else {
            $data = $query->get();

            return Excel::download(new AnggotaRantingExport($data), 'anggotaranting.xlsx');
        }
    }
}
