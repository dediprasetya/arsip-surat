@extends('layouts.kepala')

@section('content')
<h4 class="mb-4">Daftar Surat Masuk</h4>
<table class="table table-bordered table-striped" id="tabelSuratMasuk">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nomor Surat</th>
            <th>Perihal</th>
            <th>Asal Surat</th>
            <th>Tanggal Surat</th>
            <th>Status Surat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($surat_masuk as $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $s->nomor_surat }}</td>
                <td>{{ $s->perihal }}</td>
                <td>{{ $s->asal_surat }}</td>
                <td>{{ \Carbon\Carbon::parse($s->tanggal_surat)->format('d-m-Y') }}</td>
                <td>{{ $s->status_surat }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@push('scripts')
<script>
    $(document).ready(function () {
        $('#tabelSuratMasuk').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' // Bahasa Indonesia
            }
        });
    });
</script>
@endpush
@endsection
