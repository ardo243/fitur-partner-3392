<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Review;
use App\Models\Transaction;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:5',
        ]);

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Anda harus masuk terlebih dahulu untuk memberikan ulasan.');
        }

        // 1. Verifikasi Pembelian
        $hasPurchased = Transaction::where('event_id', $event->id)
            ->where('customer_email', $user->email)
            ->whereIn('status', ['success', 'settlement'])
            ->exists();

        if (!$hasPurchased) {
            return back()->withErrors(['review' => 'Anda harus membeli tiket acara ini terlebih dahulu untuk memberikan ulasan.']);
        }

        // 2. Batas Waktu (minimal 1 hari setelah acara selesai)
        $eventFinished = now()->gt($event->date->copy()->addDay());
        if (!$eventFinished) {
            return back()->withErrors(['review' => 'Ulasan hanya dapat dikirim minimal 1 hari setelah acara selesai.']);
        }

        // 3. Ulasan Unik (satu ulasan per user per event)
        $hasReviewed = Review::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($hasReviewed) {
            return back()->withErrors(['review' => 'Anda sudah memberikan ulasan untuk acara ini.']);
        }

        Review::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil disimpan.');
    }
}
