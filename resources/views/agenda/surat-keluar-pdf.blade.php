<!DOCTYPE html>
<html>
<head>
    <title>Agenda Surat Keluar</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 6px;
            text-align: left;
        }
        h4 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h4>Agenda Surat Keluar</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Surat</th>
                <th>Tanggal Surat</th>
                <th>Perihal</th>
                <th>Tujuan</th>
                <th>Klasifikasi</th>
                <th>Pembuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $surat)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $surat->nomor_surat }}</td>
                <td>{{ $surat->tanggal_surat }}</td>
                <td>{{ $surat->perihal }}</td>
                <td>{{ $surat->tujuan_surat }}</td>
                <td>{{ $surat->klasifikasi->nama_klasifikasi ?? '-' }}</td>
                <td>{{ $surat->user->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
