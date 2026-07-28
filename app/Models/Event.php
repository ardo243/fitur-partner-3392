<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'category_id', 'partner_id', 'organization_id', 'title', 'description', 'date',
        'location', 'price', 'stock', 'poster_path'
    ];

    public function ticketTiers()
    {
        return $this->hasMany(TicketTier::class)->orderBy('sort_order');
    }

    /**
     * Ambil tier harga yang sedang aktif saat ini.
     * Kembalikan null jika tidak ada tier (gunakan harga event langsung).
     */
    public function getActiveTier(): ?TicketTier
    {
        $now = Carbon::now();
        return $this->ticketTiers()
            ->where('sold_count', '<', \DB::raw('quota'))
            ->where(function ($q) use ($now) {
                $q->whereNull('sale_start')->orWhere('sale_start', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('sale_end')->orWhere('sale_end', '>=', $now);
            })
            ->orderBy('sort_order')
            ->first();
    }

    protected $casts = [
        'date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function organization()
{
    return $this->belongsTo(Organization::class);
}
    
}
