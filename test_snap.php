<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$params = [
    'transaction_details' => [
        'order_id' => 'TEST-'.time(),
        'gross_amount' => 10000,
    ]
];
\Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
\Midtrans\Config::$isProduction = false;
try {
    $token = \Midtrans\Snap::getSnapToken($params);
    echo "Sandbox token generated: " . $token . "\n";
} catch (\Exception $e) {
    echo "Sandbox ERROR: " . $e->getMessage() . "\n";
}
\Midtrans\Config::$isProduction = true;
try {
    $token = \Midtrans\Snap::getSnapToken($params);
    echo "Production token generated: " . $token . "\n";
} catch (\Exception $e) {
    echo "Production ERROR: " . $e->getMessage() . "\n";
}
