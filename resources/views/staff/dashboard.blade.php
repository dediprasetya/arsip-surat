@extends(auth()->user()->role == 'admin' ? 'layouts.app' : 'layouts.staff')

@section('content')
<div class="container">
    <h2>Sistem Arsip Surat - Bidang Pengadaan dan Informasi BKPSDM Kabupaten Semarang</h2>
    <p>Selamat datang, {{ Auth::user()->name }}!</p>

    <div class="row">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4>{{ $totalSuratMasuk }}</h4>
                    <p>Surat Masuk (Ditujukan kepada Anda)</p>
                    <a href="{{ route('staff.surat-masuk') }}" class="btn btn-light btn-sm">Lihat Surat Masuk</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4>{{ $totalSuratKeluar }}</h4>
                    <p>Surat Keluar (Dibuat oleh Anda)</p>
                    <a href="{{ route('surat-keluar.index') }}" class="btn btn-light btn-sm">Lihat Surat Keluar</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Grafik Surat Masuk & Keluar</div>
            <div class="card-body">
                <canvas id="suratChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('suratChart').getContext('2d');
    const suratChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Surat Masuk', 'Surat Keluar'],
            datasets: [{
                label: 'Jumlah Surat',
                data: [{{ $totalSuratMasuk }}, {{ $totalSuratKeluar }}],
                backgroundColor: ['#007bff', '#28a745'],
                borderColor: ['#0056b3', '#1e7e34'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection

