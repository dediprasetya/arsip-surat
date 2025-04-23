@extends('layouts.staff')

@section('title', 'Daftar Surat Staf')

@section('content')
<div class="container">
    <h3 class="my-3">Daftar Surat yang Ditujukan Kepada Anda</h3>

        <!-- Tombol toggle pencarian -->
        <div class="mb-3">
            <button class="btn btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#filterForm" aria-expanded="false" aria-controls="filterForm">
                🔍 Filter Data
            </button>
        </div>

        <!-- Form Filter -->
        <div class="collapse {{ request()->hasAny(['nomor_surat', 'asal_surat', 'perihal', 'tanggal_surat', 'nomor_agenda_umum', 'klasifikasi', 'status_surat']) ? 'show' : '' }}" id="filterForm">
            <form method="GET" action="{{ route('staff.dashboard') }}">
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
                    <div class="col-md-3">
                        <input type="text" name="nomor_agenda_umum" class="form-control" placeholder="Nomor Agenda Umum" value="{{ request('nomor_agenda_umum') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="klasifikasi" class="form-control" placeholder="Klasifikasi Surat" value="{{ request('klasifikasi') }}">
                    </div>                
                    <div class="col-md-3">
                        <select name="status_surat" class="form-control">
                            <option value="">-- Status Surat --</option>
                            <option value="belum diterima" {{ request('status_surat') == 'belum diterima' ? 'selected' : '' }}>Belum Diterima</option>
                            <option value="sudah diterima" {{ request('status_surat') == 'sudah diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                            <option value="sudah ditindaklanjuti" {{ request('status_surat') == 'sudah ditindaklanjuti' ? 'selected' : '' }}>Sudah Ditindaklanjuti</option>
                        </select>
                    </div>
                </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="d-flex justify-content-end">
    <!-- Tombol Ekspor Excel -->
        <form action="{{ route('staff.export.excel') }}" method="GET" class="mb-3">
            <!-- Jika kamu ingin ekspor berdasarkan filter yang sedang digunakan, tambahkan hidden input -->
            <input type="hidden" name="nomor_surat" value="{{ request('nomor_surat') }}">
            <input type="hidden" name="asal_surat" value="{{ request('asal_surat') }}">
            <input type="hidden" name="perihal" value="{{ request('perihal') }}">
            <input type="hidden" name="tanggal_surat" value="{{ request('tanggal_surat') }}">
            <input type="hidden" name="nomor_agenda_umum" value="{{ request('nomor_agenda_umum') }}">
            <input type="hidden" name="klasifikasi_id" value="{{ request('klasifikasi_id') }}">
            <input type="hidden" name="status_surat" value="{{ request('status_surat') }}">

            <button type="submit" class="btn btn-success">
                📥 Unduh Excel
            </button>
        </form>

    </div>

    @if($surat->isEmpty())
        <div class="alert alert-warning">Tidak ada surat yang ditujukan kepada Anda.</div>
    @else
        <table class="table table-bordered table-striped" id="tabelSurat">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Klasifikasi</th>
                    <th>Nomor Surat</th>
                    <th>Tanggal Surat</th>
                    <th>Perihal</th>
                    <th>Asal Surat</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surat as $index => $s)
                <tr data-id="{{ $s->id }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $s->klasifikasi->nama_klasifikasi ?? 'Tidak Diketahui' }}</td>
                    <td>{{ $s->nomor_surat }}</td>
                    <td>{{ $s->tanggal_surat }}</td>
                    <td>{{ $s->perihal }}</td>
                    <td>{{ $s->asal_surat }}</td>
                    <td class="status-cell">
                        <span class="badge badge-{{ $s->status_surat === 'sudah ditindaklanjuti' ? 'success' : 'warning' }}">
                            {{ ucfirst($s->status_surat) }}
                        </span>
                    </td>
                    <td>
                        @if($s->file_surat)
                            <a href="{{ url('storage/' . $s->file_surat) }}" target="_blank" class="btn btn-info btn-sm">
                                <i class="fas fa-file-alt"></i> Lihat
                            </a>
                        @else
                            <span class="text-muted">Tidak ada file</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-secondary btn-sm baca-surat"
                            data-id="{{ $s->id }}" 
                            data-nomor="{{ $s->nomor_surat }}"
                            data-tanggal="{{ $s->tanggal_surat }}"
                            data-perihal="{{ $s->perihal }}"
                            data-isi="{{ $s->isi_surat }}"
                            data-asal="{{ $s->asal_surat }}"
                            data-isi-dispo="{{ $s->isi_disposisi }}"
                            data-file="{{ $s->file_surat ? url('storage/' . $s->file_surat) : '' }}"
                            data-status="{{ $s->status_surat }}">
                            <i class="fas fa-eye"></i> Terima Surat
                        </button>

                        <button class="btn {{ $s->status_surat === 'sudah ditindaklanjuti' ? 'btn-secondary' : 'btn-success' }} btn-sm tindak-lanjut" 
                            data-id="{{ $s->id }}" 
                            {{ $s->status_surat === 'sudah ditindaklanjuti' ? 'disabled' : '' }}>
                            {{ $s->status_surat === 'sudah ditindaklanjuti' ? 'Selesai' : 'Tindak Lanjuti' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Modal untuk Detail Surat -->
<div class="modal fade" id="modalBacaSurat" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Nomor Surat:</strong> <span id="modalNomor"></span></p>
                <p><strong>Tanggal Surat:</strong> <span id="modalTanggal"></span></p>
                <p><strong>Perihal:</strong> <span id="modalPerihal"></span></p>
                <p><strong>Asal Surat:</strong> <span id="modalAsal"></span></p>
                <p><strong>Isi Surat:</strong> <span id="modalIsi"></span></p>
                <p><strong>Isi Disposisi:</strong> <span id="modalIsiDisposisi"></span></p>
                <p><strong>File Surat:</strong> <a id="modalFile" href="#" target="_blank">Lihat File</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pencarian Surat
        //document.getElementById('searchSurat').addEventListener('keyup', function () {
            //let filter = this.value.toLowerCase();
            //document.querySelectorAll('#tabelSurat tbody tr').forEach(row => {
                //row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
            //});
        //});

        // Delegasi Event untuk tombol dalam tabel
        document.getElementById('tabelSurat').addEventListener('click', function (event) {
            let target = event.target.closest('button');

            if (!target) return;

            let suratId = target.getAttribute('data-id');

            // Modal Baca Surat
            if (target.classList.contains('baca-surat')) {
                document.getElementById('modalNomor').textContent = target.getAttribute('data-nomor');
                document.getElementById('modalTanggal').textContent = target.getAttribute('data-tanggal');
                document.getElementById('modalPerihal').textContent = target.getAttribute('data-perihal');
                document.getElementById('modalAsal').textContent = target.getAttribute('data-asal');
                document.getElementById('modalIsi').textContent = target.getAttribute('data-isi');
                document.getElementById('modalIsiDisposisi').textContent = target.getAttribute('data-isi-dispo');

                let fileLink = target.getAttribute('data-file');
                if (fileLink) {
                    document.getElementById('modalFile').setAttribute('href', fileLink);
                    document.getElementById('modalFile').style.display = 'inline';
                } else {
                    document.getElementById('modalFile').style.display = 'none';
                }

                $('#modalBacaSurat').modal('show');

                // Periksa apakah surat belum pernah diterima, jika belum, ubah statusnya
                let statusSurat = target.getAttribute('data-status');
                if (statusSurat === 'belum diterima') {
                    fetch(`/staff/surat/${suratId}/terima`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            target.setAttribute('data-status', 'sudah diterima');
                            target.closest('tr').querySelector('.status-cell').innerHTML = 
                                '<span class="badge badge-info">Sudah Diterima</span>';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }

            // Tindak Lanjut Surat
            if (target.classList.contains('tindak-lanjut')) {
                fetch(`/staff/surat/${suratId}/tindaklanjuti`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        target.innerText = 'Selesai';
                        target.classList.replace('btn-success', 'btn-secondary');
                        target.setAttribute('disabled', 'disabled');
                        target.closest('tr').querySelector('.status-cell').innerHTML = 
                            '<span class="badge badge-success">Sudah Ditindaklanjuti</span>';
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

</script>
@endsection
