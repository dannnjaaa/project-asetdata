<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengajuan Print</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ddd; padding:8px; }
        th { background:#f5f5f5; }
    </style>
</head>
<body>
    <h3>Data Pengajuan ({{ $timestamp ?? now()->format('Y-m-d H:i') }})</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Asset</th>
                <th>Nama Asset</th>
                <th>Nama Pengaju</th>
                <th>Catatan</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuans as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->kode ?? optional($p->asset)->kode ?? '' }}</td>
                    <td>{{ $p->nama_asset ?? optional($p->asset)->nama ?? '' }}</td>
                    <td>{{ $p->nama_pengaju ?? optional($p->user)->name ?? '' }}</td>
                    <td>{{ $p->catatan ?? '' }}</td>
                    <td>{{ ucfirst($p->status) }}</td>
                    <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
