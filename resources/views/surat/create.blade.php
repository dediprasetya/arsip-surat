@extends('layouts.app')
@push('styles')
<link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="container">
    <h4 class="mb-4">Tambah Surat</h4>

    <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">
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
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nomor_surat" class="form-label">Nomor Surat</label>
                        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nomor_agenda_umum" class="form-label">Nomor Agenda Umum</label>
                        <input type="text" class="form-control" id="nomor_agenda_umum" name="nomor_agenda_umum" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_penerimaan_surat" class="form-label">Tanggal Penerimaan</label>
                        <input type="date" class="form-control" id="tanggal_penerimaan_surat" name="tanggal_penerimaan_surat" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal_disposisi" class="form-label">Tanggal Disposisi</label>
                        <input type="date" class="form-control" id="tanggal_disposisi" name="tanggal_disposisi">
                    </div>
                    <div class="col-md-6">
                        <label for="status_surat" class="form-label">Status Surat</label>
                        <select class="form-control" id="status_surat" name="status_surat" required>
                            <option value="belum diterima">Belum Diterima</option>
                            <option value="sudah diterima">Sudah Diterima</option>
                            <option value="sudah ditindaklanjuti">Sudah Ditindaklanjuti</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="perihal" class="form-label">Perihal</label>
                    <input type="text" class="form-control" id="perihal" name="perihal" required>
                </div>

                <div class="mb-3">
                    <label for="asal_surat" class="form-label">Asal Surat</label>
                    <input type="text" class="form-control" id="asal_surat" name="asal_surat" required>
                </div>

                <div class="mb-3">
                    <label for="isi_surat" class="form-label">Isi Surat</label>
                    <textarea class="form-control" id="isi_surat" name="isi_surat" rows="3"></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tujuan_disposisi" class="form-label">Tujuan Disposisi</label>
                        <select class="form-control" id="tujuan_disposisi" name="tujuan_disposisi">
                            <option value="">Pilih User</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="isi_disposisi" class="form-label">Isi Disposisi</label>
                        <textarea class="form-control" id="isi_disposisi" name="isi_disposisi" rows="2"></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="file_surat" class="form-label">File Surat (JPG, PNG, PDF)</label>
                    <input type="file" class="form-control" id="file_surat" name="file_surat" accept=".jpg,.jpeg,.png,.pdf">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('surat.index') }}" class="btn btn-secondary">Batal</a>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/i18n/id.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#klasifikasi_id').select2({
            placeholder: 'Pilih Klasifikasi',
            allowClear: true
        });

        $('#klasifikasi_id').on('change', function () {
            const klasifikasiId = $(this).val();
            const tujuanDisposisi = $('#tujuan_disposisi');

            if (klasifikasiId) {
                $.ajax({
                    url: '/get-users-by-klasifikasi/' + klasifikasiId,
                    type: 'GET',
                    success: function (data) {
                        tujuanDisposisi.empty().append('<option value="">Pilih User</option>');
                        if (data.length > 0) {
                            $.each(data, function (index, user) {
                                tujuanDisposisi.append(`<option value="${user.id}">${user.name}</option>`);
                            });
                        }
                    },
                    error: function () {
                        alert('Gagal mengambil data user.');
                    }
                });
            } else {
                tujuanDisposisi.empty().append('<option value="">Pilih User</option>');
            }
        });
    });
</script>
@endpush
