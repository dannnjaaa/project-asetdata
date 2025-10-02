<?php
// boots the Laravel app and prints first asset foto and qr_path and existence
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;

$asset = Asset::first();
if(!$asset){
    echo "No asset found\n";
    exit(0);
}

echo "Asset id: {$asset->id}\n";
echo "foto db value: {$asset->foto}\n";

if ($asset->foto) {
    $fotoExists = Storage::disk('public')->exists($asset->foto);
    echo "foto exists on disk? ".($fotoExists?"YES":"NO")."\n";
    if($fotoExists){
        echo "foto absolute path: ".storage_path('app/public/'.$asset->foto)."\n";
    } else {
        // also check public/storage copy
        $publicPath = public_path('storage/'.$asset->foto);
        echo "public/storage path exists? ".(file_exists($publicPath)?"YES":"NO")."\n";
        echo "expected public path: {$publicPath}\n";
    }
} else {
    echo "no foto set in DB\n";
}

// print web-accessible URLs
$base = rtrim(config('app.url'), '/');
if($asset->foto){ echo "public URL foto: {$base}/storage/{$asset->foto}\n"; }

