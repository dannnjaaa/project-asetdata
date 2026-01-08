<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Boot framework
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Authenticate user id 2
Illuminate\Support\Facades\Auth::loginUsingId(2);

// Call controller
$controller = app()->make(App\Http\Controllers\MonitoringController::class);
$resp = app()->call([$controller, 'export'], ['type' => 'excel']);

echo 'Response class: ' . get_class($resp) . PHP_EOL;

if (method_exists($resp,'getStatusCode')) {
    echo 'Status: ' . $resp->getStatusCode() . PHP_EOL;
}

if ($resp instanceof Symfony\Component\HttpFoundation\StreamedResponse) {
    echo 'StreamedResponse detected (CSV fallback probably used).' . PHP_EOL;
} else {
    echo 'Response is not StreamedResponse. '; 
    if ($resp instanceof Symfony\Component\HttpFoundation\BinaryFileResponse) {
        echo 'BinaryFileResponse (Excel download likely).' . PHP_EOL;
    } else {
        echo 'Class: ' . get_class($resp) . PHP_EOL;
    }
}

$kernel->terminate($request, $response);
