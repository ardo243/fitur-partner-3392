<?php
require __DIR__ . '/vendor/autoload.php';

$client = new \GuzzleHttp\Client();
try {
    $res = $client->request('POST', 'https://app.sandbox.midtrans.com/snap/v1/transactions/4bc8871a-fd64-467e-9720-bd62f1d8df6d/charge', [
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ],
        'json' => [
            'payment_type' => 'bank_transfer',
            'bank_transfer' => ['bank' => 'bca']
        ]
    ]);
    echo 'Charge HTTP: ' . $res->getStatusCode() . "\n";
    echo $res->getBody()->getContents() . "\n";
} catch (\Exception $e) {
    echo 'Charge ERROR: ' . $e->getMessage() . "\n";
    if (method_exists($e, 'getResponse') && $e->getResponse()) {
        echo 'Body: ' . $e->getResponse()->getBody()->getContents() . "\n";
    }
}
