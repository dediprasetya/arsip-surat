<table>
    <thead>
        <tr>
            <th>Nomor Surat</th>
            <th>Perihal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suratKeluar as $surat)
        <tr>
            <td>{{ $surat->nomor_surat }}</td>
            <td>{{ $surat->perihal }}</td>
            <td>{{ ucfirst($surat->status) }}</td>
            <td>
                @if($surat->status === 'menunggu')
                    <form action="{{ route('surat-keluar.setujui', $surat->id) }}" method="POST" style="display:inline">
                        @csrf
                        <button class="btn btn-success btn-sm">Setujui</button>
                    </form>

                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $surat->id }}">Tolak</button>

                    <!-- Modal Tolak -->
                    <div class="modal fade" id="modalTolak{{ $surat->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('surat-keluar.tolak', $surat->id) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tolak Surat</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="alasan_penolakan" class="form-control" placeholder="Alasan penolakan..." required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <span class="text-muted">Tidak ada aksi</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
