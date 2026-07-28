<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TicketTier extends Model
{
    protected $fillable = [
        'event_id', 'name', 'price', 'quota',
        'sold_count', 'sale_start', 'sale_end', 'sort_order',
    ];

    protected $casts = [
        'sale_start' => 'datetime',
        'sale_end'   => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Cek apakah tier ini sedang tersedia (berdasarkan tanggal & sisa kuota).
     */
    public function isAvailable(): bool
    {
        $now = Carbon::now();

        if ($this->sold_count >= $this->quota) return false;
        if ($this->sale_start && $now->lt($this->sale_start)) return false;
        if ($this->sale_end   && $now->gt($this->sale_end))   return false;

        return true;
    }

    /**
     * Sisa kuota tier ini.
     */
    public function getRemainingAttribute(): int
    {
        return max(0, $this->quota - $this->sold_count);
    }
}
