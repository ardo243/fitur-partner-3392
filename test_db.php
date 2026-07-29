<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trx = \App\Models\Transaction::with('event')->latest()->first();
if ($trx) {
    echo "Last Trx ID: " . $trx->id . PHP_EOL;
    echo "Last Trx Status: " . $trx->status . PHP_EOL;
    echo "Event ID: " . $trx->event_id . PHP_EOL;
    
    // get event direct from DB using DB facade
    $eventDB = \Illuminate\Support\Facades\DB::table('events')->where('id', $trx->event_id)->first();
    echo "Event Stock in DB: " . $eventDB->stock . PHP_EOL;
}
