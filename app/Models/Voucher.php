<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'discount_type', 'discount_value', 'quota',
        'used_count', 'valid_from', 'valid_until', 'is_active', 'description',
    ];

    protected $casts = [
        'valid_from'  => 'date',
        'valid_until' => 'date',
        'is_active'   => 'boolean',
    ];

    /**
     * Cek apakah voucher ini masih valid untuk digunakan.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->used_count >= $this->quota) return false;

        $today = Carbon::today();
        if ($this->valid_from && $today->lt($this->valid_from)) return false;
        if ($this->valid_until && $today->gt($this->valid_until)) return false;

        return true;
    }

    /**
     * Hitung nilai diskon berdasarkan harga asal.
     * Mengembalikan rupiah yang dipotong.
     */
    public function calculateDiscount(int $price): int
    {
        if ($this->discount_type === 'percent') {
            // Maksimum 100%
            $pct = min($this->discount_value, 100);
            return (int) round($price * $pct / 100);
        }

        // Fixed — pastikan tidak melebihi harga
        return min($this->discount_value, $price);
    }

    /**
     * Relasi ke transaksi yang menggunakan voucher ini.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
