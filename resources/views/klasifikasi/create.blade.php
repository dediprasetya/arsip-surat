@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Kategori Surat</h2>
    <form action="{{ route('klasifikasi.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama Klasifikasi</label>
            <input type="text" name="nama_klasifikasi" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('klasifikasi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
