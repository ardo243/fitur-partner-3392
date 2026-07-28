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
}