@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
    


@section('content')
<div class="table-responsive">
    <h2>Pengelolaan Surat Masuk</h2>

    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
    <a href="{{ route('surat.create') }}" class="btn btn-primary mb-3">
        Tambah Surat
    </a>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tombol toggle pencarian -->
    <div class="mb-3">
        <button class="btn btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#filterForm" aria-expanded="false" aria-controls="filterForm">
            🔍 Filter Data
        </button>
    </div>

    <!-- Form pencarian -->
    <div class="collapse {{ request()->hasAny(['nomor_surat', 'asal_surat', 'perihal', 'tanggal_surat', 'nomor_agenda_umum', 'klasifikasi', 'tujuan_disposisi', 'status_surat']) ? 'show' : '' }}" id="filterForm">
        <form method="GET" action="{{ route('surat.index') }}">
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" name="nomor_surat" class="form-control" placeholder="Cari Nomor Surat" value="{{ request('nomor_surat') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="asal_surat" class="form-control" placeholder="Cari Asal Surat" value="{{ request('asal_surat') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="perihal" class="form-control" placeholder="Cari Perihal" value="{{ request('perihal') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal_surat" class="form-control" value="{{ request('tanggal_surat') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" name="nomor_agenda_umum" class="form-control" placeholder="Cari Nomor Agenda Umum" value="{{ request('nomor_agenda_umum') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="klasifikasi" class="form-control" placeholder="Cari Klasifikasi Surat" value="{{ request('klasifikasi') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="tujuan_disposisi" class="form-control" placeholder="Cari Tujuan Disposisi" value="{{ request('tujuan_disposisi') }}">
                </div>
                <div class="col-md-3">
                    <select name="status_surat" class="form-control">
                        <option value="">-- Pilih Status Surat --</option>
                        <option value="belum diterima" {{ request('status_surat') == 'belum diterima' ? 'selected' : '' }}>Belum Diterima</option>
                        <option value="sudah diterima" {{ request('status_surat') == 'sudah diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                        <option value="sudah ditindaklanjuti" {{ request('status_surat') == 'sudah ditindaklanjuti' ? 'selected' : '' }}>Sudah Ditindaklanjuti</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="{{ route('surat.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
    <div class="d-flex justify-content-end mb-3">
    <a href="{{ route('surat-masuk.export.pdf', request()->query()) }}" class="btn btn-danger mb-3">
            <i class="fas fa-file-pdf"></i> Unduh PDF
        </a>
        <a href="{{ route('surat-masuk.export-excel', request()->query()) }}" class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-primary text-center align-middle">
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Nomor Surat</th>
                <th style="width: 10%;">Tanggal Surat</th>
                <th style="width: 15%;">Nomor Agenda Umum</th>
                <th style="width: 15%;">Asal Surat</th>
                <th style="width: 15%;">Perihal</th>
                <th style="width: 10%;">Tujuan Disposisi</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 15%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surat as $index => $s)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $s->nomor_surat }}</td>
                <td>{{ $s->tanggal_surat }}</td>
                <td>{{ $s->nomor_agenda_umum }}</td>
                <td>{{ $s->asal_surat }}</td>
                <td>{{ $s->perihal }}</td>
                <td>{{ $s->tujuan_disposisi ? $s->user->name : '-' }}</td>
                <td>{{ $s->status_surat }}</td>
                <td>
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailSuratModal{{ $s->id }}">
                        Detail
                    </button>

                    @if(Auth::user()->role == 'admin' || Auth::user()->id == $s->tujuan_disposisi)
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSuratModal{{ $s->id }}">
                            Edit
                        </button>
                    @endif

                    @if(Auth::user()->role == 'admin')
                        <form action="{{ route('surat.destroy', $s->id) }}" method="POST" style="display:inline;" 
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    @endif
                </td>

            </tr>

            <!-- Modal Detail Surat -->
            <div class="modal fade" id="detailSuratModal{{ $s->id }}" tabindex="-1" aria-labelledby="detailSuratLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailSuratLabel">Detail Surat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            
                            <p><strong>Nomor Surat:</strong> {{ $s->nomor_surat }}</p>
                            <p><strong>Asal Surat:</strong> {{ $s->asal_surat }}</p>
                            <p><strong>Tanggal Surat:</strong> {{ $s->tanggal_surat }}</p>
                            <p><strong>Diterima:</strong> {{ $s->tanggal_penerimaan_surat }}</p>
                            <p><strong>Perihal:</strong> {{ $s->perihal }}</p>
                            <p><strong>Isi Surat:</strong> {{ $s->isi_surat ?? '-' }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($s->status_surat) }}</p>
                            <p><strong>Tanggal Disposisi:</strong> {{ $s->tanggal_disposisi }}</p>
                            <p><strong>Tujuan Disposisi:</strong> {{ $s->tujuan_disposisi ? $s->user->name : '-' }}</p>
                            <p><strong>Tanggal Diterima Staff:</strong> {{ $s->tanggal_diterima_staf }}</p>
                            <p><strong>Tanggal Ditindaklanjuti Staff:</strong> {{ $s->tanggal_ditindaklanjuti_staf }}</p>

                            <!-- Tambahan Bidang, Tim Kerja, dan Klasifikasi -->
                            <p><strong>Bidang:</strong> {{ $s->bidang ? $s->bidang->nama_bidang : '-' }}</p>
                            <p><strong>Tim Kerja:</strong> {{ $s->timKerja ? $s->timKerja->nama_tim_kerja : '-' }}</p>
                            <p><strong>Klasifikasi:</strong> {{ $s->klasifikasi ? $s->klasifikasi->nama_klasifikasi : '-' }}</p>
                            <p><strong>Nomor Agenda Umum:</strong> {{ $s->nomor_agenda_umum ?? '-' }}</p>

                            <!-- Menampilkan file surat -->
                            @if($s->file_surat)
                                <p><strong>File Surat:</strong></p>
                                @php
                                    $fileExtension = pathinfo($s->file_surat, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ asset('storage/' . $s->file_surat) }}" class="img-fluid" alt="File Surat">
                                @elseif($fileExtension == 'pdf')
                                    <iframe src="{{ asset('storage/' . $s->file_surat) }}" width="100%" height="400px"></iframe>
                                @else
                                    <p><a href="{{ asset('storage/' . $s->file_surat) }}" target="_blank">Lihat File</a></p>
                                @endif
                            @else
                                <p><strong>File Surat:</strong> Tidak tersedia</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

                        <!-- Modal Edit Surat -->
                            <div class="modal fade" id="editSuratModal{{ $s->id }}" tabindex="-1" aria-labelledby="editSuratLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Surat</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('surat.update', $s->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <!-- Pilih Klasifikasi -->
                                                <div class="mb-3">
                                                <label for="nomor_surat" class="form-label">Jenis Surat</label>
                                                <select name="klasifikasi_id" id="klasifikasi_id" class="form-control">
                                                    <option value="">Pilih Klasifikasi</option>
                                                    @foreach ($klasifikasi as $item)
                                                        <option value="{{ $item->id }}" 
                                                            {{ $s->klasifikasi_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nama_klasifikasi }} - {{ $item->timKerja->nama_tim_kerja ?? '-' }} - ({{ $item->timKerja->bidang->nama_bidang ?? '-' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="nomor_surat" class="form-label">Nomor Surat</label>
                                                    <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="{{ $s->nomor_surat }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nomor_agenda_umum" class="form-label">Nomor Agenda Umum</label>
                                                    <input type="text" class="form-control" id="nomor_agenda_umum" name="nomor_agenda_umum" value="{{ $s->nomor_agenda_umum }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                                                    <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="{{ $s->tanggal_surat }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="perihal" class="form-label">Perihal</label>
                                                    <input type="text" class="form-control" id="perihal" name="perihal" value="{{ $s->perihal }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="tanggal_penerimaan_surat" class="form-label">Tanggal Penerimaan</label>
                                                    <input type="date" class="form-control" id="tanggal_penerimaan_surat" name="tanggal_penerimaan_surat" value="{{ $s->tanggal_penerimaan_surat }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="tanggal_disposisi" class="form-label">Tanggal Disposisi</label>
                                                    <input type="date" class="form-control" id="tanggal_disposisi" name="tanggal_disposisi" value="{{ $s->tanggal_disposisi }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="isi_surat" class="form-label">Isi Surat</label>
                                                    <textarea class="form-control" id="isi_surat" name="isi_surat" rows="3">{{ $s->isi_surat }}</textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="asal_surat" class="form-label">Asal Surat</label>
                                                    <input type="text" class="form-control" id="asal_surat" name="asal_surat" value="{{ $s->asal_surat }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="status_surat" class="form-label">Status Surat</label>
                                                    <select class="form-control" id="status_surat" name="status_surat">
                                                        <option value="belum diterima" {{ $s->status_surat == 'belum diterima' ? 'selected' : '' }}>Belum Diterima</option>
                                                        <option value="sudah diterima" {{ $s->status_surat == 'sudah diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                                                        <option value="sudah ditindaklanjuti" {{ $s->status_surat == 'sudah ditindaklanjuti' ? 'selected' : '' }}>Sudah Ditindaklanjuti</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="tujuan_disposisi" class="form-label">Tujuan Disposisi</label>
                                                    <select class="form-control" id="tujuan_disposisi" name="tujuan_disposisi">
                                                        <option value="">Pilih Tujuan</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}" {{ $s->tujuan_disposisi == $user->id ? 'selected' : '' }}>
                                                                {{ $user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="isi_disposisi" class="form-label">Isi Disposisi</label>
                                                    <textarea class="form-control" id="isi_disposisi" name="isi_disposisi" rows="2">{{ $s->isi_disposisi }}</textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="file_surat" class="form-label">File Surat (JPG, PNG, PDF)</label>
                                                    <input type="file" class="form-control" id="file_surat" name="file_surat" accept=".jpg,.jpeg,.png,.pdf">
                                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file.</small>
                                                </div>

                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
            @endforeach
        </tbody>
    </table>

        <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $surat->links() }}
    </div>
</div>

<!-- Modal Tambah Surat -->
<div class="modal fade" id="tambahSuratModal" tabindex="-1" aria-labelledby="tambahSuratLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="tambahSuratLabel">Tambah Surat</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
                                 @csrf
                                
                                    <div class="mb-3">
                                        <label for="klasifikasi_id" class="form-label">Klasifikasi Surat</label>
                                        <select class="form-control select2" id="klasifikasi_id" name="klasifikasi_id" required>
                                            <option value="">Pilih Klasifikasi</option>
                                            @foreach($klasifikasi as $k)
                                                <option value="{{ $k->id }}">
                                                    {{ $k->nama_klasifikasi }} - {{ $k->timKerja->nama_tim_kerja }} - ({{ $k->timKerja->bidang->nama_bidang }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nomor_surat" class="form-label">Nomor Surat</label>
                                        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nomor_agenda_umum" class="form-label">Nomor Agenda Umum</label>
                                        <input type="text" class="form-control" id="nomor_agenda_umum" name="nomor_agenda_umum" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="perihal" class="form-label">Perihal</label>
                                        <input type="text" class="form-control" id="perihal" name="perihal" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tanggal_penerimaan_surat" class="form-label">Tanggal Penerimaan</label>
                                        <input type="date" class="form-control" id="tanggal_penerimaan_surat" name="tanggal_penerimaan_surat" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="isi_surat" class="form-label">Isi Surat</label>
                                        <textarea class="form-control" id="isi_surat" name="isi_surat" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="asal_surat" class="form-label">Asal Surat</label>
                                        <input type="text" class="form-control" id="asal_surat" name="asal_surat" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status_surat" class="form-label">Status Surat</label>
                                        <select class="form-control" id="status_surat" name="status_surat" required>
                                            <option value="belum diterima">Belum Diterima</option>
                                            <option value="sudah diterima">Sudah Diterima</option>
                                            <option value="sudah ditindaklanjuti">Sudah Ditindaklanjuti</option>
                                        </select>
                                    </div>

                                    
                                    <div class="mb-3">
                                        <label for="file_surat" class="form-label">File Surat (JPG, PNG, PDF)</label>
                                        <input type="file" class="form-control" id="file_surat" name="file_surat" accept=".jpg,.jpeg,.png,.pdf">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        /** Konfirmasi sebelum hapus surat **/
        document.querySelectorAll(".delete-form").forEach(form => {
            form.addEventListener("submit", function (event) {
                if (!confirm("Apakah Anda yakin ingin menghapus surat ini?")) {
                    event.preventDefault();
                }
            });
        });

        /** Inisialisasi Select2 **/
        const dropdownParent = $('#tambahSuratModal').length ? $('#tambahSuratModal') : $(document.body);
        $('#klasifikasi_id').select2({
            placeholder: 'Pilih Klasifikasi',
            allowClear: true,
            dropdownParent: dropdownParent
        });

        /** Ambil user berdasarkan klasifikasi **/
        //$('#klasifikasi_id').on('change', function () {
        //    const klasifikasiId = $(this).val();
        //    const tujuanDisposisi = $('#tujuan_disposisi');

        //    if (klasifikasiId) {
        //        $.ajax({
        //            url: '/get-users-by-klasifikasi/' + klasifikasiId,
        //            type: 'GET',
        //            success: function (data) {
        //                tujuanDisposisi.empty().append('<option value="">Pilih User</option>');
        //                if (data.length > 0) {
        //                    $.each(data, function (index, user) {
         //                       tujuanDisposisi.append(`<option value="${user.id}">${user.name}</option>`);
         //                   });
        //                }
        //            },
        //            error: function () {
        //                alert('Gagal mengambil data user.');
        //            }
        //        });
        //    } else {
        //        tujuanDisposisi.empty().append('<option value="">Pilih User</option>');
        //    }
        //});
    });
</script>
@endpush


