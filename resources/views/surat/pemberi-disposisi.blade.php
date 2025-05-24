@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Daftar Surat Masuk untuk Disposisi</h4>
    @foreach ($surat as $s)
    <div class="card mb-3">
        <div class="card-body">
            <strong>Nomor Surat:</strong> {{ $s->nomor_surat }}<br>
            <strong>Perihal:</strong> {{ $s->perihal }}<br>
            <strong>Tim Kerja:</strong> {{ $s->klasifikasi->timKerja->nama_tim_kerja }}
            <form action="{{ route('surat.disposisi', $s->id) }}" method="POST" class="mt-2">
                @csrf
                <label>Pilih Tujuan Disposisi:</label>
                <select name="tujuan_disposisi" class="form-control" required>
                    <option value="">-- Pilih User --</option>
                    @foreach($s->klasifikasi->timKerja->users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm mt-2">Beri Disposisi</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
