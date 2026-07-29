<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$event = \App\Models\Event::find(11);

$request = \Illuminate\Http\Request::create('/checkout/' . $event->id, 'POST', [
    'customer_name' => 'Test User',
    'customer_email' => 'test@example.com',
    'customer_phone' => '08123456789'
]);

$controller = new \App\Http\Controllers\CheckoutController();
$response = $controller->store($request, $event);

if ($response instanceof \Illuminate\Http\RedirectResponse) {
    echo "Redirect URL: " . $response->getTargetUrl() . PHP_EOL;
    if (session()->has('error')) {
        echo "Session Error: " . session('error') . PHP_EOL;
    }
}
