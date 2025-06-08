@extends('layouts.kepala')

@section('title', 'Dashboard Kepala Bidang')

@section('content')
<div class="container-fluid">
    <h4>Selamat datang, {{ $user->name }} (Kepala Bidang)</h4>
    <p>Berikut daftar surat yang perlu didisposisi:</p>

    @if($suratMasuk->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Tanggal Surat</th>
                        <th>Tim Kerja</th>
                        <th>Disposisikan ke</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suratMasuk as $key => $surat)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $surat->nomor_surat }}</td>
                        <td>{{ $surat->perihal }}</td>
                        <td>{{ $surat->tanggal_surat }}</td>
                        <td>{{ $surat->klasifikasi->timKerja->nama_tim_kerja ?? '-' }}</td>
                        <td>
                            <!-- Tombol untuk buka modal -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#disposisiModal{{ $surat->id }}">
                                Disposisikan
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="disposisiModal{{ $surat->id }}" tabindex="-1" aria-labelledby="disposisiModalLabel{{ $surat->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('disposisi.kepala') }}">
                                    @csrf
                                    <input type="hidden" name="surat_id" value="{{ $surat->id }}">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="disposisiModalLabel{{ $surat->id }}">Disposisi Surat</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="tujuan_disposisi" class="form-label">Pilih Staf</label>
                                            <select name="tujuan_disposisi" class="form-select" required>
                                                <option value="">-- Pilih Staf --</option>
                                                @foreach($surat->klasifikasi->timKerja->users ?? [] as $staf)
                                                    <option value="{{ $staf->id }}">{{ $staf->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="isi_disposisi" class="form-label">Isi Disposisi</label>
                                            <textarea name="isi_disposisi" class="form-control" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Simpan Disposisi</button>
                                    </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">Tidak ada surat yang perlu didisposisi.</p>
    @endif
</div>
@endsection
