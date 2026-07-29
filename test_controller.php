<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$event = \App\Models\Event::find(11);
echo "Stock before: " . $event->stock . PHP_EOL;

$request = \Illuminate\Http\Request::create('/checkout/' . $event->id, 'POST', [
    'customer_name' => 'Test User',
    'customer_email' => 'test@example.com',
    'customer_phone' => '08123456789'
]);

$controller = new \App\Http\Controllers\CheckoutController();
$response = $controller->store($request, $event);

$event = \App\Models\Event::find(11);
echo "Stock after: " . $event->stock . PHP_EOL;

$trx = \App\Models\Transaction::latest()->first();
echo "New Trx ID: " . $trx->id . PHP_EOL;
echo "New Trx Status: " . $trx->status . PHP_EOL;
