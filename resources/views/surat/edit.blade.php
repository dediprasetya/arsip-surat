@extends('layouts.app')

@section('title', 'Edit Surat')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Surat</h2>

    <!-- Pesan Error Validasi -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('staff.surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Klasifikasi -->
        <div class="mb-3">
            <label class="form-label">Klasifikasi</label>
            <select name="klasifikasi_id" class="form-control @error('klasifikasi_id') is-invalid @enderror" required>
                <option value="">Pilih Klasifikasi</option>
                @foreach ($klasifikasi as $k)
                    <option value="{{ $k->id }}" {{ old('klasifikasi_id', $surat->klasifikasi_id) == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_klasifikasi }}
                    </option>
                @endforeach
            </select>
            @error('klasifikasi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Nomor Surat -->
        <div class="mb-3">
            <label class="form-label">Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror"
                value="{{ old('nomor_surat', $surat->nomor_surat) }}" required>
            @error('nomor_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Tanggal Surat -->
        <div class="mb-3">
            <label class="form-label">Tanggal Surat</label>
            <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror"
                value="{{ old('tanggal_surat', $surat->tanggal_surat) }}" required>
            @error('tanggal_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Perihal -->
        <div class="mb-3">
            <label class="form-label">Perihal</label>
            <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror"
                value="{{ old('perihal', $surat->perihal) }}" required>
            @error('perihal') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Isi Surat -->
        <div class="mb-3">
            <label class="form-label">Isi Surat</label>
            <textarea name="isi_surat" class="form-control @error('isi_surat') is-invalid @enderror" rows="4" required>{{ old('isi_surat', $surat->isi_surat) }}</textarea>
            @error('isi_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Asal Surat -->
        <div class="mb-3">
            <label class="form-label">Asal Surat</label>
            <input type="text" name="asal_surat" class="form-control @error('asal_surat') is-invalid @enderror"
                value="{{ old('asal_surat', $surat->asal_surat) }}">
            @error('asal_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Status Surat -->
        <div class="mb-3">
            <label class="form-label">Status Surat</label>
            <select name="status_surat" class="form-control @error('status_surat') is-invalid @enderror" required>
                <option value="belum diterima" {{ old('status_surat', $surat->status_surat) == 'belum diterima' ? 'selected' : '' }}>Belum Diterima</option>
                <option value="sudah diterima" {{ old('status_surat', $surat->status_surat) == 'sudah diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                <option value="sudah ditindaklanjuti" {{ old('status_surat', $surat->status_surat) == 'sudah ditindaklanjuti' ? 'selected' : '' }}>Sudah Ditindaklanjuti</option>
            </select>
            @error('status_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Tujuan Disposisi -->
        <div class="mb-3">
            <label class="form-label">Tujuan Disposisi</label>
            <select name="tujuan_disposisi" class="form-control @error('tujuan_disposisi') is-invalid @enderror">
                <option value="">Pilih Tujuan</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('tujuan_disposisi', $surat->tujuan_disposisi) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('tujuan_disposisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Disposisi Ke -->
        <div class="mb-3">
            <label class="form-label">Disposisi Ke</label>
            <input type="text" name="disposisi_ke" class="form-control @error('disposisi_ke') is-invalid @enderror"
                value="{{ old('disposisi_ke', $surat->disposisi_ke) }}">
            @error('disposisi_ke') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- File Surat -->
        <div class="mb-3">
            <label class="form-label">File Surat (PDF)</label>
            <input type="file" name="file_surat" class="form-control @error('file_surat') is-invalid @enderror">
            @error('file_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if ($surat->file_surat)
                <p class="mt-2"><strong>File saat ini:</strong> <a href="{{ asset('storage/'.$surat->file_surat) }}" target="_blank">Lihat Surat</a></p>
            @endif
        </div>

        <!-- Jenis Surat -->
        <div class="mb-3">
            <label class="form-label">Jenis Surat</label>
            <select name="jenis_surat" class="form-control @error('jenis_surat') is-invalid @enderror" required>
                <option value="masuk" {{ old('jenis_surat', $surat->jenis_surat) == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="keluar" {{ old('jenis_surat', $surat->jenis_surat) == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
            @error('jenis_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
