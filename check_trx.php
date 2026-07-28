<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$t = \App\Models\Transaction::where('order_id', 'TRX-1785227485-ncUU1')->first();
if ($t) {
    echo "Created At: " . $t->created_at . "\n";
    echo "Snap Token: " . $t->snap_token . "\n";
} else {
    echo "Not found\n";
}
