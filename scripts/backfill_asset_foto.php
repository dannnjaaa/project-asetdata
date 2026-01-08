<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Asset;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Log;

$count = 0;
$pengajuans = Pengajuan::whereNotNull('foto')->get();
foreach ($pengajuans as $p) {
    if ($p->asset && empty($p->asset->foto)) {
        $p->asset->foto = $p->foto;
        $p->asset->save();
        $count++;
        echo "Backfilled asset {$p->asset->id} with foto {$p->foto}\n";
    }
}

echo "Done. Backfilled: {$count}\n";
