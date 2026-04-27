@extends('layouts.app')

@section('page-title', 'Prediksi SPMB')
@section('page-subtitle', 'Perkiraan Jumlah Pendaftar Tahun Berikutnya')

@section('content')
<form method="POST" action="/prediksi">
    @csrf
    <div class="mb-3">
        <label class="form-label">Tahun Prediksi</label>
        <input type="number" name="tahun" class="form-control" required>
    </div>

    <button class="btn btn-primary">Hitung Prediksi</button>
</form>

@if(isset($hasilPrediksi))
    <div class="stat-card mt-4">
        <h5>Hasil Prediksi</h5>
        <p><strong>{{ $hasilPrediksi }}</strong> pendaftar</p>
        <small>Metode: Regresi Linear</small>
    </div>
@endif
@endsection
