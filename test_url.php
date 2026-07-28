<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$params = [
    'transaction_details' => [
        'order_id' => 'TEST-URL-'.time(),
        'gross_amount' => 150000,
    ],
    'customer_details' => [
        'first_name' => 'Budi',
        'email' => 'budi@example.com',
        'phone' => '08123456789'
    ]
];
\Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
\Midtrans\Config::$isProduction = false;
$token = \Midtrans\Snap::getSnapToken($params);
echo 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $token . "\n";
