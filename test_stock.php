<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$event = \App\Models\Event::first();
echo "Stock before: " . $event->stock . PHP_EOL;
try {
    \Illuminate\Support\Facades\DB::beginTransaction();
    $lockedEvent = \App\Models\Event::where('id', $event->id)->lockForUpdate()->first();
    $lockedEvent->decrement('stock');
    \Illuminate\Support\Facades\DB::commit();
    echo "Transaction committed." . PHP_EOL;
} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

$event = \App\Models\Event::first();
echo "Stock after: " . $event->stock . PHP_EOL;
