@extends('layouts.kepala')

@section('content')
<h4 class="mb-4">Daftar Surat Keluar</h4>
<table class="table table-bordered table-striped" id="tabelSuratKeluar">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nomor Surat</th>
            <th>Perihal</th>
            <th>Tujuan Surat</th>
            <th>Tanggal Surat</th>
            <th>Catatan Surat</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($surat_keluar as $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $s->nomor_surat }}</td>
                <td>{{ $s->perihal }}</td>
                <td>{{ $s->tujuan_surat }}</td>
                <td>{{ \Carbon\Carbon::parse($s->tanggal_surat)->format('d-m-Y') }}</td>
                <td>{{ $s->catatan }}</td>
                <td>
                    @if ($s->status == 'menunggu')
                        <span class="badge bg-warning text-dark">Menunggu</span>
                    @elseif ($s->status == 'disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@push('scripts')
<script>
    $(document).ready(function () {
        $('#tabelSuratKeluar').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            }
        });
    });
</script>
@endpush

@endsection
