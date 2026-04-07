@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan Data PPDB Periode 2021–2025')

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-label">Total Pendaftar</div>
        <div class="stat-value">{{ number_format($totalPendaftar) }}</div>
        <div class="stat-change positive">Periode 2021–2025</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-label">Total Diterima</div>
        <div class="stat-value">{{ number_format($totalDiterima) }}</div>
        <div class="stat-change positive">
            {{ number_format($persenDiterima,1) }}% dari pendaftar
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div class="stat-label">Total Registrasi</div>
        <div class="stat-value">{{ number_format($totalRegistrasi) }}</div>
        <div class="stat-change positive">
            {{ number_format($persenRegistrasi,1) }}% dari diterima
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Tren Jumlah Pendaftar Per Tahun</h5>
        <canvas id="trenChart" height="120"></canvas>
    </div>
</div>

@endsection
