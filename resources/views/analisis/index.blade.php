@extends('layouts.app')

@section('page-title', 'Analisis PPDB')
@section('page-subtitle', 'Analisis Wilayah, Jurusan, dan Asal Sekolah Peserta Didik')

@section('content')

{{-- FILTER TAHUN --}}
<div class="card p-4 mb-4">
    <form method="GET" action="{{ route('analisis') }}">
        <div class="row align-items-end">
            <div class="col-md-4">
                <label class="form-label">Pilih Tahun</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @foreach($listTahun as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

{{-- ===================== ANALISIS WILAYAH ===================== --}}
<div class="card p-4 mb-5">
    <h5 class="mb-3">Distribusi Peserta Berdasarkan Wilayah</h5>

    <table class="table table-bordered mb-4">
        <thead class="table-light">
            <tr>
                <th>Wilayah</th>
                <th>Total Peserta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wilayah as $row)
            <tr>
                <td>{{ $row->sub_kategori }}</td>
                <td>{{ $row->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <canvas id="wilayahChart" height="120"></canvas>
</div>

{{-- ===================== ANALISIS JURUSAN ===================== --}}
<div class="card p-4 mb-5">
    <h5 class="mb-3">Distribusi Peserta Berdasarkan Jurusan</h5>

    <table class="table table-bordered mb-4">
        <thead class="table-light">
            <tr>
                <th>Jurusan</th>
                <th>Total Peserta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jurusan as $row)
            <tr>
                <td>{{ $row->sub_kategori }}</td>
                <td>{{ $row->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <canvas id="jurusanChart"></canvas>
        </div>
    </div>
</div>

{{-- ===================== TOP 10 ASAL SEKOLAH ===================== --}}
<div class="card p-4 mb-5">
    <h5 class="mb-3">Top 10 Asal Sekolah</h5>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Asal Sekolah</th>
                <th>Total Peserta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sekolah as $row)
            <tr>
                <td>{{ $row->sub_kategori }}</td>
                <td>{{ $row->total }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center">Data tidak tersedia</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ===================== CHART JS ===================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* ===== WILAYAH BAR CHART ===== */
const wilayahLabels = {!! json_encode($wilayah->pluck('sub_kategori')) !!};
const wilayahValues = {!! json_encode($wilayah->pluck('total')) !!};

new Chart(document.getElementById('wilayahChart'), {
    type: 'bar',
    data: {
        labels: wilayahLabels,
        datasets: [{
            data: wilayahValues,
            backgroundColor: '#60a5fa'
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah Peserta'
                }
            }
        }
    }
});

/* ===== JURUSAN PIE CHART ===== */
const jurusanLabels = {!! json_encode($jurusan->pluck('sub_kategori')) !!};
const jurusanValues = {!! json_encode($jurusan->pluck('total')) !!};

new Chart(document.getElementById('jurusanChart'), {
    type: 'pie',
    data: {
        labels: jurusanLabels,
        datasets: [{
            data: jurusanValues,
            backgroundColor: [
                '#2563eb',
                '#16a34a',
                '#f59e0b',
                '#dc2626',
                '#7c3aed',
                '#0d9488'
            ]
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

@endsection
