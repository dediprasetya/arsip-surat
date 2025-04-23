@extends('layouts.app')

@section('title', 'Tambah/Edit Surat')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h5>{{ isset($surat) ? 'Edit' : 'Tambah' }} Surat</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($surat) ? route('surat.update', $surat->id) : route('surat.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($surat)) @method('PUT') @endif

            <div class="mb-3">
            <label>Klasifikasi</label>
            <select name="klasifikasi_id" class="form-control" required>
                <option value="">Pilih Klasifikasi</option>
                @foreach ($klasifikasi as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_klasifikasi }}</option>
                @endforeach
            </select>
            </div>

            <div class="mb-3">
                <label>Nomor Surat</label>
                <input type="text" name="nomor_surat" class="form-control" value="{{ $surat->nomor_surat ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label>Tanggal Surat</label>
                <input type="date" name="tanggal_surat" class="form-control" value="{{ $surat->tanggal_surat ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label>Perihal</label>
                <input type="text" name="perihal" class="form-control" value="{{ $surat->perihal ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label>Isi Surat</label>
                <textarea name="isi_surat" class="form-control" required>{{ $surat->isi_surat ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label>Upload File (PDF)</label>
                <input type="file" name="file_surat" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('surat.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
