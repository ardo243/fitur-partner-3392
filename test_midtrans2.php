<?php
require __DIR__ . '/vendor/autoload.php';

$serverKey = 'SB-Mid-server-JHcUkf0IhMwZpT2Z8UtGljuz';
$client = new \GuzzleHttp\Client();

echo "Testing Server Key: '" . $serverKey . "'\n";

try {
    $res = $client->request('GET', 'https://api.sandbox.midtrans.com/v2/4111111111/status', [
        'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
        'auth' => [$serverKey, '']
    ]);
    echo 'Sandbox HTTP: ' . $res->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo 'Sandbox ERROR: ' . $e->getMessage() . "\n";
}
