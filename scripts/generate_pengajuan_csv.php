<?php
// Script to generate pengajuan CSV for testing (no auth). It writes to storage/testing_pengajuan.csv
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengajuan;

$pengajuans = Pengajuan::with(['asset','user'])->orderBy('created_at','desc')->get();

$filename = __DIR__ . '/../storage/testing_pengajuan.csv';
$out = fopen($filename, 'w');
// BOM for Excel
fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

$columns = ['ID','Kode Asset','Nama Asset','Nama Pengaju','Catatan','Status','Tanggal Pengajuan'];
fputcsv($out, $columns, ',');

foreach ($pengajuans as $p) {
    $row = [
        $p->id,
        optional($p->asset)->kode ?? '',
        optional($p->asset)->nama ?? '',
        $p->nama_pengaju ?? optional($p->user)->name,
        $p->catatan,
        ucfirst($p->status),
        $p->created_at->format('d M Y H:i'),
    ];
    fputcsv($out, $row, ',');
}

fclose($out);
echo "Wrote {$filename} (" . filesize($filename) . " bytes)\n";
