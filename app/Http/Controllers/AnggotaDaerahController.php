<?php

namespace App\Http\Controllers;

use App\Exports\AnggotaDaerahExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AnggotaDaerah;
use App\Models\Biodata;
use App\Models\AnggotaCabang;
use App\Models\AnggotaRanting;
use App\Models\Daerah;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AnggotaDaerahController extends Controller
{
    /**
     * Tampilkan semua anggota daerah beserta biodatanya.
     */
    public function index(Request $request)
    {
        // Data tambahan untuk form filter/modals
        $daerah = Daerah::all();
        $kecamatan = Kecamatan::all();

        if ($request->ajax()) {
            $query = AnggotaDaerah::with('biodata', 'daerah');

            // Jika Anda ingin menambahkan filter tertentu, misalnya berdasarkan cabang atau atribut biodata,
            // tambahkan kondisi di sini. Contoh:
            // if ($request->has('cabang_id') && !empty($request->cabang_id)) {
            //     $query->where('cabang_id', $request->cabang_id);
            // }

            return DataTables::of($query)->make(true);
        }

        $data = [
            'title' => 'Kelola Data Anggota Daerah',
            'daerah' => $daerah,
            'kecamatan' => $kecamatan, // jika biodata perlu di-filter berdasar kecamatan pada modal
        ];

        return view('pages.admin.anggotadaerah', $data);
    }


    /**
     * Simpan data anggota daerah beserta biodatanya.
     */
    public function store(Request $request)
    {
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

            // Validasi anggota daerah
            'daerah_id'     => 'required|integer|exists:daerah,id',
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

            // Jika biodata dengan NIK yang sama sudah ada, ambil datanya; jika tidak, buat baru.
            $biodata = Biodata::firstOrCreate(['nik' => $biodataData['nik']], $biodataData);

            $anggotaData = $request->only(['daerah_id', 'jabatan', 'status']);
            $anggotaData['biodata_id'] = $biodata->id;

            $anggotaDaerah = AnggotaDaerah::create($anggotaData);

            DB::commit();
            return response()->json([
                'message' => 'Anggota daerah berhasil disimpan beserta biodata',
                'data'    => $anggotaDaerah->load('biodata', 'daerah')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan detail anggota daerah beserta biodatanya.
     */
    public function edit($id)
    {
        $anggotaDaerah = AnggotaDaerah::with('biodata', 'daerah')->findOrFail($id);
        return response()->json($anggotaDaerah);
    }

    /**
     * Perbarui data anggota daerah dan biodata terkait.
     */
    public function update(Request $request, $id)
    {
        $anggotaDaerah = AnggotaDaerah::findOrFail($id);

        // Validasi data update dengan pengecualian biodata (nik dan no_telp) milik record saat ini
        $request->validate([
            // Validasi biodata
            'nik'           => 'required|string|max:18|unique:biodata,nik,' . $anggotaDaerah->biodata_id,
            'nba'           => 'nullable|string|max:18',
            'nama'          => 'required|string|max:50',
            'email'         => 'nullable|email|max:60',
            'no_telp'       => 'required|string|max:15|unique:biodata,no_telp,' . $anggotaDaerah->biodata_id,
            'jenis_kelamin' => 'required|string|in:laki-laki,perempuan',
            'agama'         => 'nullable|string|max:10',
            'kecamatan_id'  => 'required|integer|exists:kecamatan,id',
            'tempat_lahir'  => 'nullable|string|max:30',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_terakhir'  => 'nullable|string|max:30',
            'pendidikan_sekarang'  => 'nullable|string|max:30',
            'alamat_tinggal' => 'nullable|string|max:60',
            'alamat_asal'   => 'nullable|string|max:60',

            // Validasi anggota daerah
            'daerah_id'     => 'required|integer|exists:daerah,id',
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
                'alamat_asal'
            ]);
            $anggotaDaerah->biodata()->update($biodataData);

            // Update data anggota daerah
            $anggotaData = $request->only(['daerah_id', 'jabatan', 'status']);
            $anggotaDaerah->update($anggotaData);

            DB::commit();
            return response()->json([
                'message' => 'Anggota daerah dan biodata berhasil diperbarui',
                'data'    => $anggotaDaerah->load('biodata', 'daerah')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus data anggota daerah. Jika biodata tidak berelasi dengan tabel
     * anggota_cabang, anggota_daerah, dan anggota_ranting, maka hapus juga biodatanya.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $anggotaDaerah = AnggotaDaerah::findOrFail($id);
            $biodataId = $anggotaDaerah->biodata_id;

            // Hapus data anggota daerah
            $anggotaDaerah->delete();

            // Periksa relasi biodata pada tabel anggota_cabang, anggota_daerah, dan anggota_ranting
            $countAnggotaCabang = AnggotaCabang::where('biodata_id', $biodataId)->count();
            $countAnggotaDaerah = AnggotaDaerah::where('biodata_id', $biodataId)->count();
            $countAnggotaRanting = AnggotaRanting::where('biodata_id', $biodataId)->count();

            if (($countAnggotaCabang + $countAnggotaDaerah + $countAnggotaRanting) === 0) {
                Biodata::find($biodataId)->delete();
            }

            DB::commit();
            return response()->json([
                'message' => 'Anggota daerah berhasil dihapus'
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
        $query = AnggotaDaerah::with(['daerah', 'biodata']);
        $data = $query->get();
        return Excel::download(new AnggotaDaerahExport($data), 'anggotadaerah.xlsx');
    }
}
