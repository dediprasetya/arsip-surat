<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keluar</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Data Surat Keluar</h2>
    <table>
        <thead>
            <tr>
                <th>Nomor Surat</th>
                <th>Klasifikasi</th>
                <th>Tanggal Surat</th>
                <th>Perihal</th>
                <th>Tujuan</th>
                <th>Isi Surat</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($suratKeluar as $item)
                <tr>
                    <td>{{ $item->nomor_surat }}</td>
                    <td>
                        {{ $item->klasifikasi->nama_klasifikasi }} <br>
                        <small>
                            {{ $item->klasifikasi->timKerja->nama_klasifikasi ?? '-' }} - 
                            {{ $item->klasifikasi->timKerja->bidang->nama_bidang ?? '-' }}
                        </small>
                    </td>
                    <td>{{ $item->tanggal_surat }}</td>
                    <td>{{ $item->perihal }}</td>
                    <td>{{ $item->tujuan_surat }}</td>
                    <td>{{ $item->isi_surat }}</td>
                    <td>{{ $item->catatan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br><br>
    <hr>
    <p style="font-size: 11px; text-align: center;">
        Dicetak dari situs {{ url('/') }} oleh {{ Auth::user()->name }} |
        Tanggal: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d-m-Y') }} |
        Jam: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }}
    </p>
</body>
</html>
