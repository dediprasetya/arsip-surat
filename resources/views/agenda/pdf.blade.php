<!DOCTYPE html>
<html>
<head>
    <title>Agenda Surat Masuk</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h3>Agenda Surat Masuk - {{ date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Surat</th>
                <th>Tanggal Surat</th>
                <th>Asal Surat</th>
                <th>Perihal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suratMasuk as $index => $surat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $surat->nomor_surat }}</td>
                <td>{{ date('d-m-Y', strtotime($surat->tanggal_surat)) }}</td>
                <td>{{ $surat->asal_surat }}</td>
                <td>{{ $surat->perihal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
