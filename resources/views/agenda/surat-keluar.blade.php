@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Agenda Surat Keluar</h4>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="bulan" class="form-control">
                <option value="">-- Pilih Bulan --</option>
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="tahun" class="form-control">
                <option value="">-- Pilih Tahun --</option>
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">Tampilkan</button>
        </div>
    </form>
    <a href="{{ route('agenda.surat.keluar.pdf', request()->query()) }}" class="btn btn-danger btn-sm mb-2">
        <i class="fas fa-file-pdf"></i> Cetak PDF
    </a>

    <a href="{{ route('agenda.surat.keluar.excel', request()->query()) }}" class="btn btn-success btn-sm mb-2">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Surat</th>
                <th>Tanggal</th>
                <th>Perihal</th>
                <th>Tujuan</th>
                <th>Isi_surat</th>
                <th>Pembuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $surat->nomor_surat }}</td>
                    <td>{{ $surat->tanggal_surat }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td>{{ $surat->tujuan_surat }}</td>
                    <td>{{ $surat->isi_surat ?? '-' }}</td>
                    <td>{{ $surat->user->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
