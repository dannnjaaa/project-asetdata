<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Export Assets - {{ $timestamp }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; text-align: left; }
        .header { margin-bottom: 12px; }
        .muted { color: #666; font-size: 0.9rem; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Asset Report</h2>
        <div class="muted">Generated: {{ now()->format('d M Y H:i') }}</div>
        <div class="no-print" style="margin-top:8px;">
            <button onclick="window.print();" style="padding:8px 12px;">Print / Save as PDF</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Spesifikasi</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->kode }}</td>
                <td>{{ $a->nama }}</td>
                <td>{{ optional($a->kategori)->nama_kategori }}</td>
                <td>{{ optional($a->lokasi)->nama_lokasi }}</td>
                <td>{{ $a->spesifikasi }}</td>
                <td>{{ $a->kondisi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
