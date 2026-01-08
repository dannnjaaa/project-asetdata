<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

$name = 'tv';
$asset = Asset::where('nama', $name)->first();
if (! $asset) {
    echo "No asset named '{$name}' found\n";
    exit(0);
}

echo "Asset id: {$asset->id}\n";
echo "nama: {$asset->nama}\n";
echo "foto (DB): " . ($asset->foto ?? '[null]') . "\n";

if ($asset->foto) {
    $exists = Storage::disk('public')->exists($asset->foto) ? 'YES' : 'NO';
    echo "exists on public disk? {$exists}\n";
    $publicPath = public_path('storage/' . $asset->foto);
    echo "public/storage path: {$publicPath} -> " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";
    $url = rtrim(config('app.url'), '/') . '/storage/' . ltrim($asset->foto, '/');
    echo "public URL: {$url}\n";
}

echo "Rendered img src used in view would be: ";
if (! $asset->foto) {
    echo "[placeholder or default]\n";
} else {
    echo "storage/" . ltrim($asset->foto, '/') . "\n";
}

echo "Done.\n";
