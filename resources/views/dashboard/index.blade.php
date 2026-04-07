@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Sistem Informasi Analisis dan Prediksi Penerimaan Peserta Didik Baru (PPDB) SMK Astrindo Tegal untuk Mendukung Pengambilan Keputusan Sekolah')

@section('content')

<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Siswa Tahun {{ $tahunTerakhir }}</div>
            <div class="stat-value text-primary">{{ $siswaTahunTerakhir }}</div>
            <small class="text-muted">
                {{ $pertumbuhanText }}
            </small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Prediksi Tahun {{ $tahunPrediksi }}</div>
            <div class="stat-value text-success">{{ $hasilPrediksi }}</div>
            <small class="text-muted">
                Metode terbaik: {{ $metodeTerbaikGlobal === 'moving_average' ? 'Moving Average' : 'Regresi Linear' }}
            </small>
        </div>
    </div>


    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Error Model (MAPE)</div>
            <div class="stat-value text-danger">
                {{ number_format($mape, 2) }}%
            </div>
            <small class="text-muted">Rata-rata Kesalahan</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Prediksi AKL</div>
            <div class="stat-value text-success">{{ $statProdi['AKL']['prediksi_tahun_depan'] ?? 0 }}</div>
            <small class="text-danger d-block">
                Error rata-rata: {{ isset($statProdi['AKL']) ? number_format($statProdi['AKL']['mape'], 2) : '0.00' }}%
            </small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Prediksi DKV</div>
            <div class="stat-value text-success">{{ $statProdi['DKV']['prediksi_tahun_depan'] ?? 0 }}</div>
            <small class="text-danger d-block">
                Error rata-rata: {{ isset($statProdi['DKV']) ? number_format($statProdi['DKV']['mape'], 2) : '0.00' }}%
            </small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Prediksi TKJ</div>
            <div class="stat-value text-success">{{ $statProdi['TKJ']['prediksi_tahun_depan'] ?? 0 }}</div>
            <small class="text-danger d-block">
                Error rata-rata: {{ isset($statProdi['TKJ']) ? number_format($statProdi['TKJ']['mape'], 2) : '0.00' }}%
            </small>
        </div>
    </div>

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
    <h5 class="mb-1">Tren & Prediksi Jumlah Peserta Didik Baru Per Program Studi</h5>
    <small class="text-muted d-block mb-3">
        Informasi: Grafik menampilkan data aktual (garis penuh) dan hasil prediksi terbaik (garis putus-putus)
        menggunakan metode {{ $metodeTerbaikGlobal === 'moving_average' ? 'Moving Average' : 'Regresi Linear' }} berdasarkan nilai MAPE.
    </small>
    <div style="height:340px;">
        <canvas id="dashboardChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($chartTahun) !!};
    const dataAktualProdi = {!! json_encode($chartAktualProdi) !!};
    const dataPrediksiProdi = {!! json_encode($chartPrediksiProdi) !!};
    const formatJumlah = (val) => new Intl.NumberFormat('id-ID').format(val ?? 0);

    new Chart(document.getElementById('dashboardChart'), {
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
                    fill: false,
                    tension: 0.35,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 3
                },
                {
                    label: 'AKL (Prediksi)',
                    data: dataPrediksiProdi.AKL ?? [],
                    borderColor: '#2563eb',
                    borderDash: [6, 4],
                    fill: false,
                    tension: 0.35,
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
                    fill: false,
                    tension: 0.35,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 3
                },
                {
                    label: 'DKV (Prediksi)',
                    data: dataPrediksiProdi.DKV ?? [],
                    borderColor: '#16a34a',
                    borderDash: [6, 4],
                    fill: false,
                    tension: 0.35,
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
                    fill: false,
                    tension: 0.35,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 3
                },
                {
                    label: 'TKJ (Prediksi)',
                    data: dataPrediksiProdi.TKJ ?? [],
                    borderColor: '#f59e0b',
                    borderDash: [6, 4],
                    fill: false,
                    tension: 0.35,
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
                    fill: false,
                    tension: 0.35,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    borderWidth: 3
                },
                {
                    label: 'TO (Prediksi)',
                    data: dataPrediksiProdi.TO ?? [],
                    borderColor: '#dc2626',
                    borderDash: [6, 4],
                    fill: false,
                    tension: 0.35,
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
