<?php

namespace App\Http\Controllers;

use App\Models\PpdbDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AnalisisController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun;

        // =========================
        // CEK TABEL ppdb_details
        // =========================
        if (Schema::hasTable('ppdb_details')) {

            // =========================
            // Dropdown Tahun
            // =========================
            $listTahun = PpdbDetail::select('tahun')
                ->distinct()
                ->orderBy('tahun')
                ->pluck('tahun');

            // =========================
            // QUERY DASAR (FILTER TAHUN)
            // =========================
            $baseQuery = PpdbDetail::query();

            if ($tahun) {
                $baseQuery->where('tahun', $tahun);
            }

            // =========================
            // ANALISIS WILAYAH
            // =========================
            $wilayah = (clone $baseQuery)
                ->where('kategori', 'Wilayah')
                ->selectRaw('sub_kategori, SUM(jumlah) as total')
                ->groupBy('sub_kategori')
                ->orderByDesc('total')
                ->get();

            // =========================
            // ANALISIS JURUSAN
            // =========================
            $jurusan = (clone $baseQuery)
                ->where('kategori', 'Jurusan')
                ->selectRaw('sub_kategori, SUM(jumlah) as total')
                ->groupBy('sub_kategori')
                ->orderByDesc('total')
                ->get();

            $totalJurusan = $jurusan->sum('total');
            $jurusanPersen = $jurusan->map(function ($row) use ($totalJurusan) {
                $persen = $totalJurusan > 0 ? ($row->total / $totalJurusan) * 100 : 0;
                return round($persen, 2);
            });

            // =========================
            // ANALISIS ASAL SEKOLAH (TOP 10)
            // =========================
            $sekolah = (clone $baseQuery)
                ->where('kategori', 'Asal Sekolah')
                ->selectRaw('sub_kategori, SUM(jumlah) as total')
                ->groupBy('sub_kategori')
                ->orderByDesc('total')
                ->limit(10)
                ->get();

        } else {
            // =========================
            // JIKA TABEL BELUM ADA / TERHAPUS
            // =========================
            $listTahun = collect();
            $wilayah   = collect();
            $jurusan   = collect();
            $jurusanPersen = collect();
            $sekolah   = collect();
        }

        return view('analisis.index', compact(
            'listTahun',
            'tahun',
            'wilayah',
            'jurusan',
            'sekolah',
            'jurusanPersen'
        ));
    }
}
