<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'event_id', 'voucher_id', 'ticket_tier_id',
        'order_id', 'customer_name', 'customer_email', 'customer_phone',
        'total_price', 'discount_amount', 'status', 'snap_token'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function ticketTier()
    {
        return $this->belongsTo(TicketTier::class);
    }

    /**
     * Lazy Cleanup Global: Merilis semua tiket untuk transaksi 'Pending' yang kadaluarsa (>6 menit).
     */
    public static function releaseAllExpired()
    {
        \Illuminate\Support\Facades\DB::transaction(function () {
            $expiredTransactions = self::where('status', 'Pending')
                ->whereRaw('created_at < NOW() - INTERVAL 6 MINUTE')
                ->lockForUpdate()
                ->get();

            foreach ($expiredTransactions as $transaction) {
                // Kembalikan stok event
                Event::where('id', $transaction->event_id)->increment('stock');

                // Kembalikan tier
                if ($transaction->ticket_tier_id) {
                    TicketTier::where('id', $transaction->ticket_tier_id)->decrement('sold_count');
                }

                // Kembalikan voucher
                if ($transaction->voucher_id) {
                    Voucher::where('id', $transaction->voucher_id)->decrement('used_count');
                }

                // Update status menjadi failed/expired
                $transaction->update(['status' => 'failed']);
            }
        });
    }
}