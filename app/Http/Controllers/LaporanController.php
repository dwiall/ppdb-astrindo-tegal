<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

use App\Models\PpdbSummary;
use App\Models\PpdbDetail;

use App\Imports\PpdbSummaryImport;
use App\Imports\PpdbDetailImport;

use App\Exports\PpdbDetailExport;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * ==============================
     * HALAMAN LAPORAN
     * ==============================
     */
    public function index()
    {
        $data = PpdbSummary::orderBy('tahun', 'asc')->get();
        $detailCount = PpdbDetail::count();

        return view('laporan.index', compact('data', 'detailCount'));
    }

    /**
     * ==============================
     * IMPORT DATA SUMMARY (EXCEL)
     * ==============================
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PpdbSummaryImport, $request->file('file'));

        return redirect()->back()->with(
            'success',
            'Data summary PPDB berhasil diimport'
        );
    }

    /**
     * ==============================
     * IMPORT DATA DETAIL (EXCEL)
     * ==============================
     */
    public function importDetail(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PpdbDetailImport, $request->file('file'));

        return redirect()->back()->with(
            'success',
            'Data detail PPDB berhasil diimport'
        );
    }

    public function resetDetail()
    {
        \App\Models\PpdbDetail::truncate();

        return redirect()->back()->with(
            'success',
            'Data detail PPDB berhasil direset.'
        );
    }
    /**
     * ==============================
     * EXPORT EXCEL (DETAIL)
     * ==============================
     */
    public function exportExcel()
    {
        // 🔒 GUARD: TABEL & DATA HARUS ADA
        if (!Schema::hasTable('ppdb_details') || PpdbDetail::count() == 0) {
            return redirect()->back()->with(
                'error',
                'Data detail PPDB belum tersedia. Export Excel tidak dapat dilakukan.'
            );
        }

        return Excel::download(
            new PpdbDetailExport,
            'laporan_ppdb_detail.xlsx'
        );
    }

    /**
     * ==============================
     * EXPORT PDF (DETAIL)
     * ==============================
     */
    public function exportPdf()
    {
        // 🔒 GUARD: TABEL & DATA HARUS ADA
        if (!Schema::hasTable('ppdb_details') || PpdbDetail::count() == 0) {
            return redirect()->back()->with(
                'error',
                'Data detail PPDB belum tersedia. Export PDF tidak dapat dilakukan.'
            );
        }

        $data = PpdbDetail::orderBy('tahun', 'asc')
            ->orderBy('kategori', 'asc')
            ->orderBy('sub_kategori', 'asc')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('data'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('laporan_ppdb_detail.pdf');
    }
}
