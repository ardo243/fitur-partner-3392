<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$transaction = \App\Models\Transaction::where('order_id', 'TRX-1785227485-ncUU1')->first();
if ($transaction) {
    ob_start();
    echo view('checkout.payment', ['transaction' => $transaction, 'categories' => \App\Models\Category::all()])->render();
    $html = ob_get_clean();
    
    // Find the script tag
    preg_match('/<script src="[^"]*snap\.js"[^>]*>/i', $html, $matches);
    print_r($matches);
}
