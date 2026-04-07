@extends('layouts.app')

@section('page-title', 'Manajemen Data')
@section('page-subtitle', 'Kelola dan download data historis penerimaan peserta didik baru')

@section('content')

{{-- ===================== --}}
{{-- ALERT NOTIFIKASI --}}
{{-- ===================== --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Perhatian!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ===================== --}}
{{-- IMPORT SUMMARY --}}
{{-- ===================== --}}
<div class="card p-4 mb-4">
    <h5 class="mb-3">Import Data Summary PPDB (Excel)</h5>

    <form action="{{ route('laporan.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="summary">

        <div class="row align-items-center">
            <div class="col-md-6">
                <input type="file" name="file" class="form-control" required>
                <small class="text-muted">
                    Format kolom: <b>tahun | total_siswa</b>
                </small>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary">
                    Import Summary
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ===================== --}}
{{-- IMPORT DETAIL --}}
{{-- ===================== --}}
<div class="card p-4 mb-4">
    <h5 class="mb-3">Import Data Detail PPDB (Excel)</h5>

    <form action="{{ route('laporan.import.detail') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row align-items-center">
            <div class="col-md-6">
                <input type="file" name="file" class="form-control" required>
                <small class="text-muted">
                    Format kolom: <b>tahun | kategori | sub_kategori | jumlah</b>
                </small>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary">
                    Import Detail
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ===================== --}}
{{-- EXPORT --}}
{{-- ===================== --}}
<div class="mb-4">
    @if($detailCount > 0)
        <a href="{{ route('laporan.excel') }}" class="btn btn-success me-2">
            Download Excel
        </a>
        <a href="{{ route('laporan.pdf') }}" class="btn btn-danger">
            Download PDF
        </a>
    @else
        <button class="btn btn-secondary me-2" disabled>
            Download Excel (Data belum tersedia)
        </button>
        <button class="btn btn-secondary" disabled>
            Download PDF (Data belum tersedia)
        </button>
    @endif
</div>

{{-- ===================== --}}
{{-- RESET DATA DETAIL --}}
{{-- ===================== --}}
<div class="mb-4">
    <form action="{{ route('laporan.reset.detail') }}" method="POST"
          onsubmit="return confirm('Yakin ingin MENGHAPUS SEMUA data detail PPDB? Tindakan ini tidak dapat dibatalkan!')">
        @csrf
        <button type="submit" class="btn btn-outline-danger">
            Reset Data Detail PPDB
        </button>
    </form>
</div>

{{-- ===================== --}}
{{-- TABEL SUMMARY --}}
{{-- ===================== --}}
<div class="card p-3">
    <h5 class="mb-3">Data Summary PPDB</h5>

    <table class="table table-bordered bg-white mb-0">
        <thead class="table-light">
            <tr>
                <th width="30%">Tahun</th>
                <th>Total Siswa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->tahun }}</td>
                    <td>{{ number_format($row->total_siswa) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">
                        Data belum tersedia
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
