<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Surat Masuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        h2, h4 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Data Surat Masuk</h2>
    <h4>BKPSDM Kabupaten Semarang</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Surat</th>
                <th>Tanggal Surat</th>
                <th>Asal Surat</th>
                <th>Perihal</th>
                <th>Klasifikasi</th>
                <th>Status Surat</th>
                <th>Bidang</th>
                <th>Tim Kerja</th>
                <th>Nomor Agenda Umum</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nomor_surat }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_surat)->format('d-m-Y') }}</td>
                <td>{{ $item->asal_surat }}</td>
                <td>{{ $item->perihal }}</td>
                <td>{{ $item->klasifikasi->nama ?? '-' }}</td>
                <td>{{ ucfirst($item->status_surat) }}</td>
                <td>{{ $item->klasifikasi->timKerja->bidang->nama_bidang ?? '-' }}</td>
                <td>{{ $item->klasifikasi->timKerja->nama_tim_kerja ?? '-' }}</td>
                <td>{{ $item->nomor_agenda_umum }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
