@extends('layouts.kepala')

@section('content')
<div class="row">
    <!-- Kartu Surat Masuk -->
    <div class="col-md-6 col-xl-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <strong>Surat Masuk</strong>
                <h3>{{ $jumlahSuratMasuk }}</h3>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="{{ route('kepala.surat.masuk') }}" class="text-white small stretched-link">Lihat Surat</a>
                <i class="bi bi-arrow-right"></i>
            </div>
        </div>
    </div>

    <!-- Kartu Surat Keluar -->
    <div class="col-md-6 col-xl-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <strong>Surat Keluar</strong>
                <h3>{{ $jumlahSuratKeluar }}</h3>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="{{ route('kepala.surat.keluar') }}" class="text-white small stretched-link">Lihat Surat</a>
                <i class="bi bi-arrow-right"></i>
            </div>
        </div>
    </div>
</div>
@endsection

