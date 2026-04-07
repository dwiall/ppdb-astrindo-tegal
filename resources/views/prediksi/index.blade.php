@extends('layouts.app')

@section('page-title', 'Prediksi PPDB')
@section('page-subtitle', 'Prediksi Jumlah Peserta Didik Baru Menggunakan Metode Regresi Linear dan Moving Average')

@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h5 class="mb-0">Ringkasan Prediksi PPDB</h5>
    <form method="GET" action="{{ route('prediksi') }}">
        <div class="input-group input-group-sm" style="width: 260px;">
            <label class="input-group-text" for="metode">Metode</label>
            <select name="metode" id="metode" class="form-select" onchange="this.form.submit()">
                <option value="regresi_linear" {{ $metode === 'regresi_linear' ? 'selected' : '' }}>Regresi Linear</option>
                <option value="moving_average" {{ $metode === 'moving_average' ? 'selected' : '' }}>Moving Average</option>
            </select>
        </div>
    </form>
</div>

<div class="row g-4 mb-4">

    <!-- Tahun Prediksi -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Tahun Prediksi</div>
            <div class="stat-value text-primary">{{ $tahunPrediksi }}</div>
            <small class="text-muted">PPDB</small>
        </div>
    </div>

    <!-- Hasil Prediksi Total -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Hasil Prediksi Total</div>
            <div class="stat-value text-success">{{ $hasilPrediksi }}</div>
            <small class="text-muted">Jumlah Peserta Didik ({{ $metode === 'regresi_linear' ? 'Regresi Linear' : 'Moving Average' }})</small>
        </div>
    </div>

    <!-- Error MAPE Global -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Error Prediksi (MAPE)</div>
            <div class="stat-value text-danger">
                {{ number_format($mape, 2) }}%
            </div>
            <small class="text-muted">Rata-rata Kesalahan</small>
        </div>
    </div>

    <!-- Hasil Prediksi AKL -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Prediksi AKL</div>
            <div class="stat-value text-success">{{ $statProdi['AKL']['prediksi_tahun_depan'] ?? 0 }}</div>
            <small class="text-danger d-block">
                Error rata-rata: {{ isset($statProdi['AKL']) ? number_format($statProdi['AKL']['mape'], 2) : '0.00' }}%
            </small>
        </div>
    </div>

    <!-- Hasil Prediksi DKV -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Prediksi DKV</div>
            <div class="stat-value text-success">{{ $statProdi['DKV']['prediksi_tahun_depan'] ?? 0 }}</div>
            <small class="text-danger d-block">
                Error rata-rata: {{ isset($statProdi['DKV']) ? number_format($statProdi['DKV']['mape'], 2) : '0.00' }}%
            </small>
        </div>
    </div>

    <!-- Hasil Prediksi TKJ -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Prediksi TKJ</div>
            <div class="stat-value text-success">{{ $statProdi['TKJ']['prediksi_tahun_depan'] ?? 0 }}</div>
            <small class="text-danger d-block">
                Error rata-rata: {{ isset($statProdi['TKJ']) ? number_format($statProdi['TKJ']['mape'], 2) : '0.00' }}%
            </small>
        </div>
    </div>

    <!-- Hasil Prediksi TO -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Prediksi TO</div>
            <div class="stat-value text-success">{{ $statProdi['TO']['prediksi_tahun_depan'] ?? 0 }}</div>
            <small class="text-danger d-block">
                Error rata-rata: {{ isset($statProdi['TO']) ? number_format($statProdi['TO']['mape'], 2) : '0.00' }}%
            </small>
        </div>
    </div>

</div>

<div class="card p-4">
    <h5 class="mb-1">Grafik Data Aktual & Prediksi Per Program Studi ({{ $metode === 'regresi_linear' ? 'Regresi Linear' : 'Moving Average' }})</h5>
    <small class="text-muted d-block mb-3">
        Garis penuh menunjukkan data aktual, garis putus-putus menunjukkan hasil prediksi per program studi.
    </small>
    <div style="height:320px;">
        <canvas id="prediksiChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($chartTahun) !!};
    const dataAktualProdi = {!! json_encode($chartAktualProdi) !!};
    const dataPrediksiProdi = {!! json_encode($chartPrediksiProdi) !!};
    const formatJumlah = (val) => new Intl.NumberFormat('id-ID').format(val ?? 0);

    new Chart(document.getElementById('prediksiChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                // AKL
                {
                    label: 'AKL (Aktual)',
                    data: dataAktualProdi.AKL ?? [],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.15)',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 3
                },
                {
                    label: 'AKL (Prediksi)',
                    data: dataPrediksiProdi.AKL ?? [],
                    borderColor: '#2563eb',
                    borderDash: [6, 4],
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 2
                },
                // DKV
                {
                    label: 'DKV (Aktual)',
                    data: dataAktualProdi.DKV ?? [],
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,0.15)',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 3
                },
                {
                    label: 'DKV (Prediksi)',
                    data: dataPrediksiProdi.DKV ?? [],
                    borderColor: '#16a34a',
                    borderDash: [6, 4],
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 2
                },
                // TKJ
                {
                    label: 'TKJ (Aktual)',
                    data: dataAktualProdi.TKJ ?? [],
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.15)',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 3
                },
                {
                    label: 'TKJ (Prediksi)',
                    data: dataPrediksiProdi.TKJ ?? [],
                    borderColor: '#f59e0b',
                    borderDash: [6, 4],
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 2
                },
                // TO
                {
                    label: 'TO (Aktual)',
                    data: dataAktualProdi.TO ?? [],
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220,38,38,0.15)',
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 3
                },
                {
                    label: 'TO (Prediksi)',
                    data: dataPrediksiProdi.TO ?? [],
                    borderColor: '#dc2626',
                    borderDash: [6, 4],
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 2
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10,
                        padding: 14
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17,24,39,0.92)',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${formatJumlah(context.parsed.y)}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Peserta Didik'
                    },
                    ticks: {
                        callback: function(value) {
                            return formatJumlah(value);
                        }
                    },
                    grid: {
                        color: 'rgba(148,163,184,0.18)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tahun'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

@endsection
