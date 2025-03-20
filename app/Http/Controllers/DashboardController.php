<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $auth = Auth::user();

        if ($auth->role == 'admin') {
            $genderCount = DB::table('biodata')
                ->selectRaw("
                    COUNT(CASE WHEN jenis_kelamin = 'laki-laki' THEN 1 END) as jumlahLaki, 
                    COUNT(CASE WHEN jenis_kelamin = 'perempuan' THEN 1 END) as jumlahPerempuan
                ")->first();

            $jumlahLaki = !empty($genderCount) && !is_null($genderCount->jumlahLaki) ? $genderCount->jumlahLaki : 0;
            $jumlahPerempuan = !empty($genderCount) && !is_null($genderCount->jumlahPerempuan) ? $genderCount->jumlahPerempuan : 0;
            $jumlahAnggota = $jumlahLaki + $jumlahPerempuan;

            $jumlahCabang = DB::table('cabang')->count() ?? 0;
            $jumlahRanting = DB::table('ranting')->count() ?? 0;
            $jumlahOperator = DB::table('users')->where('role', 'operator')->count() ?? 0;

            $data = [
                'title' => "Dashboard Admin",
                'jumlahAnggota' => $jumlahAnggota,
                'jumlahCabang' => $jumlahCabang,
                'jumlahRanting' => $jumlahRanting,
                'jumlahOperator' => $jumlahOperator,
                'jumlahLaki' => $jumlahLaki,
                'jumlahPerempuan' => $jumlahPerempuan,
            ];

            return view('pages.admin.dashboard', $data);
        }

        if ($auth->role == 'operator') {
            $jumlahAnggota = Biodata::where('created_by', $auth->id)->count() ?? 0;
            $jumlahLaki = Biodata::where('jenis_kelamin', 'laki-laki')->where('created_by', $auth->id)->count() ?? 0;
            $jumlahPerempuan = Biodata::where('jenis_kelamin', 'perempuan')->where('created_by', $auth->id)->count() ?? 0;

            $data = [
                'jumlahAnggota' => $jumlahAnggota,
                'jumlahLaki' => $jumlahLaki,
                'jumlahPerempuan' => $jumlahPerempuan,
            ];

            // return view('pages.operator.dashboard', $data);
        }

        return redirect()->route('login')->with('error', 'Akses tidak diizinkan.');
    }

    public function chartData()
    {
        $chartData = DB::table(DB::raw("(SELECT DATE_FORMAT(NOW() - INTERVAL n MONTH, '%Y-%m') as bulan FROM (SELECT 0 n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5) t) months"))
            ->leftJoin(DB::raw("(SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as jumlah FROM biodata WHERE created_at >= NOW() - INTERVAL 5 MONTH GROUP BY bulan) biodata"), 'months.bulan', '=', 'biodata.bulan')
            ->selectRaw("months.bulan, COALESCE(biodata.jumlah, 0) as jumlah")
            ->orderBy('months.bulan', 'asc')
            ->pluck('jumlah', 'bulan');

        return response()->json([
            'categories' => array_keys($chartData->toArray()),
            'values' => array_values($chartData->toArray()),
        ]);
    }
}
