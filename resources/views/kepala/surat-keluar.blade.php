@extends('layouts.kepala')

@section('content')
<div class="container">
    <h4>Daftar Surat Keluar - Menunggu Persetujuan</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Perihal</th>
                <th>Tujuan</th>
                <th>Tanggal</th>
                <th>File</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suratKeluar as $surat)
            <tr>
                <td>{{ $surat->nomor_surat }}</td>
                <td>{{ $surat->perihal }}</td>
                <td>{{ $surat->tujuan_surat }}</td>
                <td>{{ $surat->tanggal_surat }}</td>
                <td>
                    @if ($surat->file_surat)
                        <a href="{{ Storage::url('surat_keluar/' . $surat->file_surat) }}" target="_blank">Lihat</a>
                    @endif
                </td>
                <td>
                    <form action="{{ route('surat-keluar.setujui', $surat->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-sm">Setujui</button>
                    </form>

                    <!-- Tombol tolak surat (modal) -->
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakModal{{ $surat->id }}">Tolak</button>

                    <!-- Modal penolakan -->
                    <div class="modal fade" id="tolakModal{{ $surat->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('surat-keluar.tolak', $surat->id) }}" method="POST" class="modal-content">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Alasan Penolakan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea name="alasan_penolakan" class="form-control" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Tolak Surat</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">Tidak ada surat keluar yang menunggu persetujuan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
