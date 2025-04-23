@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Buku Agenda - Surat Masuk</h4>

    <!-- Filter -->
    <form action="{{ route('agenda.index') }}" method="GET">
        <div class="row">
            <div class="col-md-4">
                <label>Bulan</label>
                <select name="bulan" class="form-control">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ sprintf('%02d', $i) }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label>Tahun</label>
                <select name="tahun" class="form-control">
                    @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>

    <!-- Tabel -->
    <div class="mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Surat</th>
                    <th>Nomor Agenda Umum</th>
                    <th>Tanggal Surat</th>
                    <th>Asal Surat</th>
                    <th>Perihal</th>
                    <th>Isi Surat</th>
                    <th>Isi Disposisi</th>
                    <th>Tujuan Disposisi</th>
                    <th>Status Surat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratMasuk as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $surat->nomor_surat }}</td>
                    <td>{{ $surat->nomor_agenda_umum }}</td>
                    <td>{{ date('d-m-Y', strtotime($surat->tanggal_surat)) }}</td>
                    <td>{{ $surat->asal_surat }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td>{{ $surat->isi_surat }}</td>
                    <td>{{ $surat->isi_disposisi }} </td>
                    <td>{{ $surat->tujuan_disposisi ? $surat->user->name : '-' }} </td>
                    <td>{{ $surat->status_surat }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tombol Export -->
    <div class="mt-3">
        <a href="{{ route('agenda.export.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('agenda.export.excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
</div>
@endsection
