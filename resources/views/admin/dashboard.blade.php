@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-1">Sistem Arsip Surat - Bidang Pengadaan dan Informasi BKPSDM Kabupaten Semarang</h2>
    <p>Selamat datang, {{ $user->name }}!</p>

    {{-- Statistik --}}
    <div class="row my-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>{{ $totalSuratMasuk }}</h5>
                    <p>Surat Masuk</p>
                    <a href="{{ route('surat.index') }}" class="btn btn-light btn-sm">Lihat Surat Masuk</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>{{ $totalSuratKeluar }}</h5>
                    <p>Surat Keluar</p>
                    <a href="{{ route('surat-keluar.index') }}" class="btn btn-light btn-sm">Lihat Surat Keluar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h5>{{ $totalUser }}</h5>
                    <p>Total Pengguna</p>
                    <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">Lihat Pengguna</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5>{{ $totalBidang }}</h5>
                    <p>Bidang</p>
                    <a href="{{ route('klasifikasi.index') }}" class="btn btn-light btn-sm">Lihat Bidang</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-secondary text-white shadow">
                <div class="card-body">
                    <h5>{{ $totalTimKerja }}</h5>
                    <p>Tim Kerja</p>
                    <a href="{{ route('klasifikasi.index') }}" class="btn btn-light btn-sm">Lihat Tim Kerja</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-dark text-white shadow">
                <div class="card-body">
                    <h5>{{ $totalKlasifikasi }}</h5>
                    <p>Klasifikasi Surat</p>
                    <a href="{{ route('klasifikasi.index') }}" class="btn btn-light btn-sm">Lihat Klasifikasi</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Grafik Surat Masuk</div>
            <div class="card-body">
                <canvas id="chartSuratMasuk" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Grafik Surat Keluar</div>
            <div class="card-body">
                <canvas id="chartSuratKeluar" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const suratMasukData = {!! json_encode($suratMasukChart) !!};
    const suratKeluarData = {!! json_encode($suratKeluarChart) !!};

    const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    new Chart(document.getElementById('chartSuratMasuk'), {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Jumlah Surat Masuk',
                data: Array.from({length: 12}, (_, i) => suratMasukData[i + 1] || 0),
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Surat Masuk Bulanan'
                }
            }
        }
    });

    new Chart(document.getElementById('chartSuratKeluar'), {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Jumlah Surat Keluar',
                data: Array.from({length: 12}, (_, i) => suratKeluarData[i + 1] || 0),
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Surat Keluar Bulanan'
                }
            }
        }
    });
</script>

@endsection
