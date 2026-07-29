<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trx = \App\Models\Transaction::latest()->first();
if ($trx) {
    echo "Last Trx ID: " . $trx->id . PHP_EOL;
    echo "Last Trx Status: " . $trx->status . PHP_EOL;
    echo "Last Trx Created: " . $trx->created_at . PHP_EOL;
    echo "Event Stock: " . $trx->event->stock . PHP_EOL;
    
    // Check if there are other transactions for this event
    $count = \App\Models\Transaction::where('event_id', $trx->event_id)->count();
    echo "Total Trx for Event: " . $count . PHP_EOL;
} else {
    echo "No transaction found." . PHP_EOL;
}
