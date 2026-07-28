<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$serverKey = env('MIDTRANS_SERVER_KEY');
$client = new \GuzzleHttp\Client();

echo "Testing Server Key: '" . $serverKey . "'\n";
echo "Testing Client Key: '" . env('MIDTRANS_CLIENT_KEY') . "'\n";

echo "====================================\n";

// Test Sandbox
try {
    $res = $client->request('GET', 'https://api.sandbox.midtrans.com/v2/4111111111/status', [
        'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
        'auth' => [$serverKey, '']
    ]);
    echo 'Sandbox HTTP: ' . $res->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo 'Sandbox ERROR: ' . $e->getMessage() . "\n";
}

// Test Production
try {
    $res = $client->request('GET', 'https://api.midtrans.com/v2/4111111111/status', [
        'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
        'auth' => [$serverKey, '']
    ]);
    echo 'Production HTTP: ' . $res->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo 'Production ERROR: ' . $e->getMessage() . "\n";
}
