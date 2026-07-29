<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use App\Models\Voucher;
use App\Models\TicketTier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        // Lazy Cleanup: Otomatis merilis tiket dari transaksi expired (6+ menit)
        \App\Models\Transaction::releaseAllExpired();

        if (now()->gt($event->date)) {
            return redirect()->route('events.show', $event->id)->with('error', 'Mohon maaf, acara ini sudah selesai sehingga pemesanan tiket telah ditutup.');
        }

        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        // Ambil semua tier harga event (terurut), untuk ditampilkan di halaman checkout
        $tiers = $event->ticketTiers()->orderBy('sort_order')->get();
        $activeTier = $event->getActiveTier();

        return view('checkout.create', compact('event', 'categories', 'tiers', 'activeTier'));
    }

    public function store(Request $request, Event $event)
    {
        // Lazy Cleanup: Otomatis merilis tiket dari transaksi expired (6+ menit)
        \App\Models\Transaction::releaseAllExpired();

        // 1. Validasi Input
        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_email'  => 'required|email|max:255',
            'customer_phone'  => 'required|string|max:20',
            'voucher_code'    => 'nullable|string|max:50',
        ]);

        // 2. Cegah checkout jika event sudah selesai
        if (now()->gt($event->date)) {
            return redirect()->route('events.show', $event->id)->with('error', 'Mohon maaf, acara ini sudah selesai sehingga pemesanan tiket telah ditutup.');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Lock event row to prevent race condition
            $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->first();

            if ($lockedEvent->stock <= 0) {
                \Illuminate\Support\Facades\DB::rollBack();
                return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
            }

            // 3. Tentukan tier dan harga dasar secara otomatis
            $selectedTier = $lockedEvent->getActiveTier();
            $basePrice = $selectedTier ? $selectedTier->price : $lockedEvent->price;

            // 4. Proses Voucher (jika ada)
            $discountAmount = 0;
            $voucherId = null;

            if ($request->filled('voucher_code')) {
                $voucher = Voucher::where('code', strtoupper($request->voucher_code))->lockForUpdate()->first();
                if ($voucher && $voucher->isValid()) {
                    $discountAmount = $voucher->calculateDiscount($basePrice);
                    $voucherId = $voucher->id;
                }
            }

            // 5. Hitung total akhir (harga - diskon + biaya admin)
            $adminFee = ($basePrice == 0) ? 0 : 5000;
            $totalPrice = max(0, $basePrice - $discountAmount) + $adminFee;

            // 6. Generate Kode TRX (Unik)
            $orderId = 'TRX-' . time() . '-' . Str::random(5);

            // 7. Rekam Transaksi ke Database
            $transaction = Transaction::create([
                'event_id'        => $lockedEvent->id,
                'voucher_id'      => $voucherId,
                'ticket_tier_id'  => $selectedTier?->id,
                'order_id'        => $orderId,
                'customer_name'   => $request->customer_name,
                'customer_email'  => $request->customer_email,
                'customer_phone'  => $request->customer_phone,
                'total_price'     => $totalPrice,
                'discount_amount' => $discountAmount,
                'status'          => 'Pending',
            ]);

            // 8. Jika voucher valid, reserve used_count
            if ($voucherId) {
                $voucher->increment('used_count');
            }

            // 9. Jika tier digunakan, reserve sold_count
            if ($selectedTier) {
                $lockedTier = TicketTier::where('id', $selectedTier->id)->lockForUpdate()->first();
                if ($lockedTier && $lockedTier->isAvailable()) {
                    $lockedTier->increment('sold_count');
                } else {
                    \Illuminate\Support\Facades\DB::rollBack();
                    return back()->with('error', 'Mohon maaf, kuota tier tiket ini sudah habis.');
                }
            }

            // 10. Reserve Event Stock (Kurangi stok seketika)
            $lockedEvent->decrement('stock');

            \Illuminate\Support\Facades\DB::commit();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses pesanan: ' . $e->getMessage());
        }

        // --- LOGIKA BYPASS UNTUK ACARA GRATIS ---
        if ($totalPrice == 0) {
            $transaction->update(['status' => 'success']);

            // Stok sudah dikurangi di blok reserve atas, jadi langsung kirim email
            try {
                \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                    ->send(new \App\Mail\EventTicketMail($transaction));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
            }

            return redirect()->route('checkout.success', $transaction->order_id)->with('success', 'Transaksi berhasil, tiket Anda telah diterbitkan!');
        }

        // --- INTEGRASI SNAP MIDTRANS ---
        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = $isProduction;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,
            ],
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s O"),
                'unit'       => 'minute',
                'duration'   => 5
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);
            return redirect()->route('checkout.payment', $transaction->order_id);
        } catch (\Exception $e) {
            // RELEASE RESERVE JIKA GAGAL MENDAPATKAN SNAP TOKEN
            Event::where('id', $event->id)->increment('stock');
            if ($transaction->ticket_tier_id) {
                TicketTier::where('id', $transaction->ticket_tier_id)->decrement('sold_count');
            }
            if ($transaction->voucher_id) {
                Voucher::where('id', $transaction->voucher_id)->decrement('used_count');
            }
            $transaction->update(['status' => 'failed']);

            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Endpoint AJAX: validasi kode voucher dan kembalikan nilai diskon.
     */
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code'       => 'required|string',
            'base_price' => 'required|integer|min:0',
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak ditemukan.'], 200);
        }

        if (!$voucher->isValid()) {
            $msg = 'Voucher tidak berlaku.';
            if (!$voucher->is_active) $msg = 'Voucher ini sedang dinonaktifkan.';
            elseif ($voucher->used_count >= $voucher->quota) $msg = 'Kuota voucher sudah habis.';
            elseif ($voucher->valid_until && now()->gt($voucher->valid_until)) $msg = 'Voucher sudah kadaluarsa.';
            return response()->json(['valid' => false, 'message' => $msg], 200);
        }

        $basePrice = (int) $request->base_price;
        $discountAmount = $voucher->calculateDiscount($basePrice);
        $adminFee = ($basePrice == 0) ? 0 : 5000;
        $finalPrice = max(0, $basePrice - $discountAmount) + $adminFee;

        return response()->json([
            'valid'           => true,
            'message'         => 'Voucher berhasil diterapkan!',
            'discount_type'   => $voucher->discount_type,
            'discount_value'  => $voucher->discount_value,
            'discount_amount' => $discountAmount,
            'final_price'     => $finalPrice,
        ]);
    }

    public function payment($order_id)
    {
        $categories  = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        
        if ($transaction->total_price == 0) {
            return redirect()->route('checkout.success', $transaction->order_id);
        }

        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        $categories  = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        
        \Midtrans\Config::$serverKey  = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = $isProduction;
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        if ($transaction->total_price == 0 && strtolower($transaction->status) === 'success') {
            return view('checkout.success', compact('transaction', 'categories'));
        }

        try {
            $status     = \Midtrans\Transaction::status($order_id);
            $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

            if (in_array($trx_status, ['settlement', 'capture'])) {
                if (strtolower($transaction->status) === 'pending') {
                    $transaction->update(['status' => 'success']);

                    // Stok sudah dikurangi di awal (Reserve), jadi cukup kirim email
                    try {
                        \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                            ->send(new \App\Mail\EventTicketMail($transaction));
                    } catch (\Exception $e) {
                        \Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }
}
