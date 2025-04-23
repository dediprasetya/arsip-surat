@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Manajemen Klasifikasi Surat</h3>
    
    <div class="col-md-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKlasifikasiModal">Tambah Klasifikasi</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahBidangModal">Tambah Bidang</button>
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#tambahTimKerjaModal">Tambah Tim Kerja</button>
    </div><br>

    <!-- Pencarian -->
    <div class="col-md-6">
        <input type="text" class="form-control" id="searchInput" onkeyup="searchTable()" placeholder="Cari berdasarkan kode atau nama klasifikasi">
    </div><br>

    <!-- Tabel Klasifikasi Surat -->
    <h5>Klasifikasi Surat</h5>
    <div class="table-responsive">
        <table class="table table-striped" id="klasifikasiTable">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Klasifikasi</th>
                    <th>Kode Klasifikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($klasifikasi as $index => $k)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $k->nama_klasifikasi }}</td>
                    <td>{{ $k->kode }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKlasifikasiModal{{ $k->id }}">
                            Edit
                        </button>

                        <!-- Tombol Hapus -->
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusKlasifikasiModal{{ $k->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>
                
                <!-- Modal Edit Klasifikasi -->
                <div class="modal fade" id="editKlasifikasiModal{{ $k->id }}" tabindex="-1" aria-labelledby="editKlasifikasiLabel{{ $k->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editKlasifikasiLabel{{ $k->id }}">Edit Klasifikasi Surat</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('klasifikasi.update', $k->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="nama_klasifikasi" class="form-label">Nama Klasifikasi</label>
                                        <input type="text" class="form-control" name="nama_klasifikasi" value="{{ $k->nama_klasifikasi }}" required>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="kode" class="form-label">Kode Klasifikasi</label>
                                        <input type="text" class="form-control" name="kode" value="{{ $k->kode }}" required>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="tim_kerja_id" class="form-label">Tim Kerja</label>
                                        <select class="form-select" id="tim_kerja_id" name="tim_kerja_id" required>
                                            @foreach ($timKerja as $t)
                                                <option value="{{ $t->id }}" {{ $k->tim_kerja_id == $t->id ? 'selected' : '' }}>{{ $t->nama_tim_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                
                <!-- Modal Hapus Klasifikasi -->
                <div class="modal fade" id="hapusKlasifikasiModal{{ $k->id }}" tabindex="-1" aria-labelledby="hapusKlasifikasiLabel{{ $k->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="hapusKlasifikasiLabel{{ $k->id }}">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('klasifikasi.destroy', $k->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus klasifikasi <strong>{{ $k->nama_klasifikasi }}</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tabel Bidang -->
    <h5 class="mt-4">Bidang</h5>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Bidang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bidang as $index => $b)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $b->nama_bidang }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBidangModal{{ $b->id }}">
                            Edit
                        </button>

                        <!-- Tombol Hapus -->
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusBidangModal{{ $b->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>

                <!-- Modal Edit Bidang -->
                <div class="modal fade" id="editBidangModal{{ $b->id }}" tabindex="-1" aria-labelledby="editBidangLabel{{ $b->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editBidangLabel{{ $b->id }}">Edit Bidang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('bidang.update', $b->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="nama_bidang" class="form-label">Nama Bidang</label>
                                        <input type="text" class="form-control" name="nama_bidang" value="{{ $b->nama_bidang }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Hapus Bidang -->
                <div class="modal fade" id="hapusBidangModal{{ $b->id }}" tabindex="-1" aria-labelledby="hapusBidangLabel{{ $b->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="hapusBidangLabel{{ $b->id }}">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('bidang.destroy', $b->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus bidang <strong>{{ $b->nama_bidang }}</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tabel Tim Kerja -->
    <h5 class="mt-4">Tim Kerja</h5>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Tim Kerja</th>
                    <th>Bidang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($timKerja as $index => $t)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $t->nama_tim_kerja }}</td>
                    <td>{{ $t->bidang->nama_bidang }}</td>
                    <td>
                    <!-- Tombol Edit -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTimKerjaModal{{ $t->id }}">
                        Edit
                    </button>

                    <!-- Tombol Hapus -->
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusTimKerjaModal{{ $t->id }}">
                        Hapus
                    </button>
                </td>
                </tr>
                <!-- Modal Edit Tim Kerja -->
                <div class="modal fade" id="editTimKerjaModal{{ $t->id }}" tabindex="-1" aria-labelledby="editTimKerjaLabel{{ $t->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editTimKerjaLabel{{ $t->id }}">Edit Tim Kerja</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('timkerja.update', $t->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="nama_tim_kerja" class="form-label">Nama Tim</label>
                                        <input type="text" class="form-control" name="nama_tim_kerja" value="{{ $t->nama_tim_kerja }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bidang_id" class="form-label">Bidang</label>
                                        <select class="form-control" name="bidang_id" required>
                                            @foreach ($bidang as $b)
                                                <option value="{{ $b->id }}" {{ $t->bidang_id == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Hapus Tim Kerja -->
                <div class="modal fade" id="hapusTimKerjaModal{{ $t->id }}" tabindex="-1" aria-labelledby="hapusTimKerjaLabel{{ $t->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="hapusTimKerjaLabel{{ $t->id }}">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('timkerja.destroy', $t->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus tim kerja <strong>{{ $t->nama_tim_kerja }}</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Bidang -->
<div class="modal fade" id="tambahBidangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Bidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('bidang.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Bidang</label>
                        <input type="text" class="form-control" name="nama_bidang" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Tim Kerja -->
<div class="modal fade" id="tambahTimKerjaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tim Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('timkerja.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Tim Kerja</label>
                        <input type="text" class="form-control" name="nama_tim_kerja" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bidang</label>
                        <select class="form-control" name="bidang_id" required>
                            @foreach($bidang as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Klasifikasi -->
<div class="modal fade" id="tambahKlasifikasiModal" tabindex="-1" aria-labelledby="tambahKlasifikasiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKlasifikasiLabel">Tambah Klasifikasi Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('klasifikasi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_klasifikasi">Nama Klasifikasi</label>
                        <input type="text" name="nama_klasifikasi" id="nama_klasifikasi" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode Klasifikasi</label>
                        <input type="text" name="kode" id="kode" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tim_kerja_id">Tim Kerja</label>
                        <select name="tim_kerja_id" id="tim_kerja_id" class="form-control" required>
                            <option value="">-- Pilih Tim Kerja --</option>
                            @foreach($timKerja as $tim)
                                <option value="{{ $tim->id }}">{{ $tim->nama_tim_kerja }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function searchTable() {
        let input, filter, table, tr, tdName, tdCode, i, txtValueName, txtValueCode;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("klasifikasiTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            tdCode = tr[i].getElementsByTagName("td")[1]; 
            tdName = tr[i].getElementsByTagName("td")[2]; 

            if (tdCode && tdName) {
                txtValueCode = tdCode.textContent || tdCode.innerText;
                txtValueName = tdName.textContent || tdName.innerText;

                if (txtValueCode.toUpperCase().indexOf(filter) > -1 || txtValueName.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    $(document).ready(function () {
    $('.btn-batal').click(function () {
        $('#tambahKlasifikasiModal').modal('hide');
    });

    $('#tambahKlasifikasiModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset(); // Reset form saat modal ditutup
    });
});
</script>

@endsection
