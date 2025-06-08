@extends(auth()->user()->role == 'admin' ? 'layouts.app' : 'layouts.staff')

@section('title', 'Surat Keluar')

@push('styles')
<link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    {{-- Notifikasi sukses --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Data Surat Keluar</h5>
        <div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahSuratModal">
                <i class="fas fa-plus"></i> Tambah Surat
            </button>
        </div>
    </div>
    <div class="card-body">

        {{-- Form Pencarian --}}
        <form method="GET" action="{{ route('surat-keluar.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan perihal, nomor surat..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
        </form>

        {{-- Tabel Surat --}}
        <div class="table-responsive">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('surat-keluar.export.pdf', ['search' => request('search')]) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Unduh PDF
                </a>
                <a href="{{ route('surat-keluar.export.excel', ['search' => request('search')]) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Unduh Excel
                </a>
            </div>
            {{-- Alert Jika Pencarian Tidak Ditemukan --}}
            @if(request('search') && $suratKeluar->isEmpty())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Data dengan kata kunci <strong>{{ request('search') }}</strong> tidak ditemukan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Klasifikasi</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal</th>
                        <th>Perihal</th>
                        <th>Tujuan</th>
                        <th>Catatan</th>
                        <th>Pembuat</th>
                        <th>Status Surat</th>
                        <th>File Surat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratKeluar as $index => $surat)
                    <tr>
                        <td>{{ $suratKeluar->firstItem() + $index }}</td>
                        <td>
                            {{ $surat->klasifikasi->nama_klasifikasi }} -<br> 
                            <small>
                                {{ $surat->klasifikasi->timKerja->nama_tim_kerja ?? '-' }} - 
                                {{ $surat->klasifikasi->timKerja->bidang->nama_bidang ?? '-' }}
                            </small>
                        </td>
                        <td>{{ $surat->nomor_surat }}</td>
                        <td>{{ $surat->tanggal_surat }}</td>
                        <td>{{ $surat->perihal }}</td>
                        <td>{{ $surat->tujuan_surat }}</td>
                        <td>{{ $surat->catatan }}</td>
                        <td>{{ $surat->user->name }}</td>
                        <td style="min-width: 200px;">
                            @if ($surat->status === 'disetujui')
                                <span class="badge bg-success">Disetujui</span><br>
                                <small>{{ \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d-m-Y H:i') }}</small>
                            @elseif ($surat->status === 'ditolak')
                                <span class="badge bg-danger">Ditolak</span><br>
                                <small><strong>Alasan:</strong> {{ $surat->alasan_penolakan }}</small><br>
                                <small>{{ \Carbon\Carbon::parse($surat->tanggal_persetujuan)->format('d-m-Y H:i') }}</small>
                            @else
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @endif
                        </td>
                        <td>
                            @if($surat->file_surat)
                                <a href="{{ Storage::url('surat_keluar/' . $surat->file_surat) }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-file-alt"></i> Lihat
                                </a>
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit"
                                data-id="{{ $surat->id }}"
                                data-klasifikasi_id="{{ $surat->klasifikasi_id }}"
                                data-nomor_surat="{{ $surat->nomor_surat }}"
                                data-tanggal_surat="{{ $surat->tanggal_surat }}"
                                data-perihal="{{ $surat->perihal }}"
                                data-tujuan_surat="{{ $surat->tujuan_surat }}"
                                data-isi_surat="{{ $surat->isi_surat }}"
                                data-catatan="{{ $surat->catatan }}"
                                data-status="{{ $surat->status }}"
                                data-alasan_penolakan="{{ $surat->alasan_penolakan }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editSuratModal">
                                <i class="fas fa-edit"></i>
                            </button>
                        <!-- Tombol Hapus -->
                            <form action="{{ route('surat-keluar.destroy', $surat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus surat ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $suratKeluar->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div>

 <!-- Modal Tambah Surat Keluar -->
 <div class="modal fade" id="tambahSuratModal" tabindex="-1" aria-labelledby="tambahSuratModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route('surat-keluar.store') }}" enctype="multipart/form-data" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahSuratModalLabel">Tambah Surat Keluar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form input surat keluar -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Klasifikasi Surat</label>
                                    <select name="klasifikasi_id" class="form-control" required>
                                        <option value="">Pilih Klasifikasi</option>
                                        @foreach ($klasifikasi as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nama_klasifikasi }} 
                                                ({{ $item->timKerja->nama_tim_kerja ?? '-' }} - {{ $item->timKerja->bidang->nama_bidang ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Nomor Surat</label>
                                    <input type="text" name="nomor_surat" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Tanggal Surat</label>
                                    <input type="date" name="tanggal_surat" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Perihal</label>
                                    <input type="text" name="perihal" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Tujuan Surat</label>
                                    <input type="text" name="tujuan_surat" class="form-control" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Isi Surat</label>
                                    <textarea name="isi_surat" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Catatan</label>
                                    <textarea name="catatan" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>File Surat (maks. 2MB)</label>
                                    <input type="file" name="file_surat" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Surat</button>
                        </div>
                    </form>
                </div>
            </div>

<!-- Modal Edit Surat Keluar -->
<div class="modal fade" id="editSuratModal" tabindex="-1" aria-labelledby="editSuratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="" enctype="multipart/form-data" class="modal-content" id="formEditSurat">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editSuratModalLabel">Edit Surat Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <!-- Form input surat keluar (diisi lewat JS) -->
                <input type="hidden" name="reset_status" id="edit_reset_status" value="0">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Klasifikasi Surat</label>
                        <select name="klasifikasi_id" id="edit_klasifikasi_id" class="form-control" required>
                            <option value="">Pilih Klasifikasi</option>
                            @foreach ($klasifikasi as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama_klasifikasi }} 
                                ({{ $item->timKerja->nama_tim_kerja ?? '-' }} - {{ $item->timKerja->bidang->nama_bidang ?? '-' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Nomor Surat</label>
                        <input type="text" name="nomor_surat" id="edit_nomor_surat" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" id="edit_tanggal_surat" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Perihal</label>
                        <input type="text" name="perihal" id="edit_perihal" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tujuan Surat</label>
                        <input type="text" name="tujuan_surat" id="edit_tujuan_surat" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Isi Surat</label>
                        <textarea name="isi_surat" id="edit_isi_surat" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Catatan</label>
                        <textarea name="catatan" id="edit_catatan" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-12 mb-3" id="alasan_penolakan_container" style="display:none;">
                        <label class="text-danger">Alasan Penolakan</label>
                        <div class="alert alert-danger" id="alasan_penolakan_text"></div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Ganti File Surat (opsional, maksimal 2MB)</label>
                        <input type="file" name="file_surat" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Update Surat</button>
            </div>
        </form>
        
    </div>
</div>


@endsection

@push('scripts')
<script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/i18n/id.js') }}"></script>
<script>
    $(document).ready(function () {
        // Hanya inisialisasi select2 saat modal benar-benar terbuka
        $('#tambahSuratModal').on('shown.bs.modal', function () {
            $('#klasifikasi_id').select2({
                dropdownParent: $('#tambahSuratModal'),
                width: '100%',
                placeholder: 'Pilih Klasifikasi'
            });
        });

        $('#editSuratModal').on('shown.bs.modal', function () {
            $('#edit_klasifikasi_id').select2({
                dropdownParent: $('#editSuratModal'),
                width: '100%',
                placeholder: 'Pilih Klasifikasi'
            });

            // Trigger ulang change agar nilai pre-filled ter-load
            $('#edit_klasifikasi_id').trigger('change');
        });

        $('.btn-edit').on('click', function () {
            const status = $(this).data('status');
            const alasan = $(this).data('alasan_penolakan');

            $('#formEditSurat').attr('action', '/surat-keluar/' + $(this).data('id'));
            $('#edit_klasifikasi_id').val($(this).data('klasifikasi_id'));
            $('#edit_nomor_surat').val($(this).data('nomor_surat'));
            $('#edit_tanggal_surat').val($(this).data('tanggal_surat'));
            $('#edit_perihal').val($(this).data('perihal'));
            $('#edit_tujuan_surat').val($(this).data('tujuan_surat'));
            $('#edit_isi_surat').val($(this).data('isi_surat'));
            $('#edit_catatan').val($(this).data('catatan'));

            // Jika status surat adalah "ditolak", tampilkan alasan dan set reset_status
            if (status === 'ditolak') {
                $('#alasan_penolakan_container').show();
                $('#alasan_penolakan_text').text(alasan);
                $('#edit_reset_status').val(1);
            } else {
                $('#alasan_penolakan_container').hide();
                $('#edit_reset_status').val(0);
            }

            // Buka modal setelah semua data dimasukkan
            $('#editSuratModal').modal('show');
        });
    });

</script>
@endpush
