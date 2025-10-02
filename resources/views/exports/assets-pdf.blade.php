<!DOCTYPE html>
<html>
<head>
    <title>Assets Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .table th,
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: left;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Asset</h1>
        <p>Generated at: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Asset</th>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
                <th>Tanggal Perolehan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->id }}</td>
                <td>{{ $asset->nama }}</td>
                <td>{{ $asset->kode }}</td>
                <td>{{ optional($asset->kategori)->nama_kategori }}</td>
                <td>{{ optional($asset->lokasi)->nama_lokasi }}</td>
                <td>{{ $asset->kondisi }}</td>
                <td>{{ $asset->tgl_perolehan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>