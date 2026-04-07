@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Sistem Informasi Analisis dan Prediksi Penerimaan Peserta Didik Baru (PPDB) SMK Astrindo Tegal untuk Mendukung Pengambilan Keputusan Sekolah')

@section('content')

<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Siswa Tahun {{ $tahunTerakhir }}</div>
            <div class="stat-value text-primary">{{ $siswaTahunTerakhir }}</div>
            <small class="text-muted">
                {{ $pertumbuhanText }}
            </small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Prediksi Tahun {{ $tahunPrediksi }}</div>
            <div class="stat-value text-success">{{ $hasilPrediksi }}</div>
            <small class="text-muted">Regresi Linear</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Akurasi Model (R²)</div>
            <div class="stat-value text-warning">
                {{ number_format($r2 * 100, 2) }}%
            </div>
            <small class="text-muted">Koefisien Determinasi</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Error Model (MAPE)</div>
            <div class="stat-value text-danger">
                {{ number_format($mape, 2) }}%
            </div>
            <small class="text-muted">Rata-rata Kesalahan</small>
        </div>
    </div>

</div>

<div class="card p-4">
    <h5 class="mb-3">Tren & Prediksi Jumlah Peserta Didik Baru</h5>
    <div style="height:340px;">
        <canvas id="dashboardChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($chartTahun) !!};
    const dataAktual = {!! json_encode($chartAktual) !!};
    const dataPrediksi = {!! json_encode($chartPrediksi) !!};

    new Chart(document.getElementById('dashboardChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Data Aktual',
                    data: dataAktual,
                    borderColor: '#1e3a8a',
                    backgroundColor: 'rgba(30,58,138,0.15)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5
                },
                {
                    label: 'Regresi Linear & Prediksi',
                    data: dataPrediksi,
                    borderColor: '#16a34a',
                    borderDash: [6,6],
                    tension: 0.4,
                    fill: false,
                    pointRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah Peserta Didik'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tahun'
                    }
                }
            }
        }
    });
</script>

@endsection
