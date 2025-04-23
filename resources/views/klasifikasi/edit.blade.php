@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Kategori Surat</h2>
    <form action="{{ route('klasifikasi.update', $klasifikasi->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" value="{{ $klasifikasi->kode }}" required>
        </div>
        <div class="mb-3">
            <label>Nama Klasifikasi</label>
            <input type="text" name="nama_klasifikasi" class="form-control" value="{{ $klasifikasi->nama_klasifikasi }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('klasifikasi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
