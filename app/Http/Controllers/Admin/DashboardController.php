<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Jalankan lazy cleanup untuk mengembalikan stok transaksi yang expired
        \App\Models\Transaction::releaseAllExpired();

        // 1. Total Pendapatan dari Transaksi Lunas
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success'])->sum('total_price');
        
        // 2. Tiket Terjual
        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success'])->count();
        
        // 3. Event Aktif
        $activeEvents = Event::where('date', '>=', now())->count();
        
        // 4. Pesanan Pending
        $pendingOrders = Transaction::where('status', 'pending')->count();

        // 5. Total User
        $totalUsers = User::count();

        // 6. Total Organisasi
        $totalOrganizations = Organization::count();
        
        // 7. Transaksi Terakhir
        $recentTransactions = Transaction::with('event')->latest()->take(5)->get();

        // 8. Data Grafik Analytics 6 Bulan Terakhir
        $months = [];
        $userGrowth = [];
        $orgGrowth = [];
        $revenueGrowth = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M');
            $year = $date->year;
            $month = $date->month;

            $months[] = $monthName;

            // Pengguna baru per bulan
            $userGrowth[] = User::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            // Organisasi baru per bulan
            $orgGrowth[] = Organization::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            // Pendapatan per bulan
            $revenueGrowth[] = Transaction::whereIn('status', ['settlement', 'success'])
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total_price');
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'totalUsers',
            'totalOrganizations',
            'recentTransactions',
            'months',
            'userGrowth',
            'orgGrowth',
            'revenueGrowth'
        ));
    }
}