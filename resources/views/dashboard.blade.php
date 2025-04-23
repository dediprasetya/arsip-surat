@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5>Dashboard</h5>
    </div>
    <div class="card-body">
        <p>Total Surat: {{ $total_surat }}</p>
        <p>Total Disposisi: {{ $total_disposisi }}</p>
        <a href="{{ route('surat.index') }}" class="btn btn-primary">Kelola Surat</a>
    </div>
</div>
<div class="card">
    <div class="card-header bg-warning text-white">Notifikasi</div>
    <div class="card-body">
        @foreach (auth()->user()->notifications as $notif)
            <div class="alert alert-info">
                <p><strong>{{ $notif->data['nomor_surat'] }}</strong> - {{ $notif->data['perihal'] }}</p>
                <a href="{{ $notif->data['link'] }}" class="btn btn-sm btn-primary">Lihat Surat</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
