@extends('layouts.app')

@section('page-title', 'Prediksi PPDB')
@section('page-subtitle', 'Prediksi Jumlah Peserta Didik Baru Menggunakan Metode Regresi Linear')

@section('content')

<div class="row g-4 mb-4">

    <!-- Tahun Prediksi -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Tahun Prediksi</div>
            <div class="stat-value text-primary">{{ $tahunPrediksi }}</div>
            <small class="text-muted">PPDB</small>
        </div>
    </div>

    <!-- Hasil Prediksi -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Hasil Prediksi</div>
            <div class="stat-value text-success">{{ $hasilPrediksi }}</div>
            <small class="text-muted">Jumlah Peserta Didik</small>
        </div>
    </div>

    <!-- Akurasi R2 -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Akurasi Model (R²)</div>
            <div class="stat-value text-warning">
                {{ number_format($r2 * 100, 2) }}%
            </div>
            <small class="text-muted">Koefisien Determinasi</small>
        </div>
    </div>

    <!-- Error MAPE -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Error Prediksi (MAPE)</div>
            <div class="stat-value text-danger">
                {{ number_format($mape, 2) }}%
            </div>
            <small class="text-muted">Rata-rata Kesalahan</small>
        </div>
    </div>

</div>

<div class="card p-4">
    <h5 class="mb-3">Grafik Data Aktual & Prediksi (Regresi Linear)</h5>
    <div style="height:320px;">
        <canvas id="prediksiChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($chartTahun) !!};
    const dataAktual = {!! json_encode($chartAktual) !!};
    const dataPrediksi = {!! json_encode($chartPrediksi) !!};

    new Chart(document.getElementById('prediksiChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Data Aktual',
                    data: dataAktual,
                    borderColor: '#1e3a8a',
                    backgroundColor: 'rgba(30,58,138,0.15)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5
                },
                {
                    label: 'Regresi Linear & Prediksi',
                    data: dataPrediksi,
                    borderColor: '#16a34a',
                    borderDash: [6, 6],
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
