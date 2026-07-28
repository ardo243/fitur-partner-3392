<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $organizationId = session('organization_id');

        // 1. Menjumlahkan semua nominal total_price dari kolom Transaksi Lunas untuk event organisasi ini
        $totalRevenue = \App\Models\Transaction::whereHas('event', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->whereIn('status', ['settlement', 'success'])->sum('total_price');
        
        // 2. Menghitung Berapa tiket yang sudah Lunas untuk event organisasi ini
        $ticketsSold = \App\Models\Transaction::whereHas('event', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->whereIn('status', ['settlement', 'success'])->count();
        
        // 3. Menghitung Jumlah Acara Mendatang yang aktif diselenggarakan oleh organisasi ini
        $activeEvents = Event::where('organization_id', $organizationId)->where('date', '>=', now())->count();
        
        // 4. Menghitung Transaksi pending untuk event organisasi ini
        $pendingOrders = \App\Models\Transaction::whereHas('event', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->where('status', 'pending')->count();
        
        // 5. Menyertakan 5 daftar riwayat pesanan (History) paling mutakhir di panel untuk event organisasi ini
        $recentTransactions = \App\Models\Transaction::whereHas('event', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->with('event')->latest()->take(5)->get();

        return view('organization.dashboard', compact('totalRevenue', 'ticketsSold', 'activeEvents', 'pendingOrders', 'recentTransactions'));
    }
}