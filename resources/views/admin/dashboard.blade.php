@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Ringkasan')
@section('page_subtitle', 'Selamat datang kembali, Admin!')

@section('content')
<div class="space-y-8">
    <!-- Top 6 Metric Cards Row -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- 1. Total Pendapatan -->
        <a href="{{ route('admin.transactions.index') }}" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-indigo-200 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer group">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-3 shrink-0 group-hover:bg-indigo-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">TOTAL PENDAPATAN</p>
                <h3 class="text-lg font-black text-slate-800 leading-tight group-hover:text-indigo-600 transition">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </a>

        <!-- 2. Tiket Terjual -->
        <a href="{{ route('admin.transactions.index') }}" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-purple-200 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer group">
            <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-3 shrink-0 group-hover:bg-purple-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">TIKET TERJUAL</p>
                <h3 class="text-lg font-black text-slate-800 leading-tight group-hover:text-purple-600 transition">{{ number_format($ticketsSold, 0, ',', '.') }} Tiket</h3>
            </div>
        </a>

        <!-- 3. Event Aktif -->
        <a href="{{ route('admin.events.index') }}" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-amber-200 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer group">
            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-3 shrink-0 group-hover:bg-amber-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">EVENT AKTIF</p>
                <h3 class="text-lg font-black text-slate-800 leading-tight group-hover:text-amber-600 transition">{{ $activeEvents }} Event</h3>
            </div>
        </a>

        <!-- 4. Pesanan Pending -->
        <a href="{{ route('admin.transactions.index') }}" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-rose-200 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer group">
            <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-3 shrink-0 group-hover:bg-rose-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">PESANAN PENDING</p>
                <h3 class="text-lg font-black text-slate-800 leading-tight group-hover:text-rose-600 transition">{{ $pendingOrders }} Pesanan</h3>
            </div>
        </a>

        <!-- 5. Total User -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-3 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5-3.512M9 20H4v-2a3 3 0 015-3.512M12 11a4 4 0 100-8 4 4 0 000 8z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">TOTAL USER</p>
                <h3 class="text-lg font-black text-slate-800 leading-tight">{{ number_format($totalUsers, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- 6. Total Organisasi -->
        <a href="{{ route('admin.organizations.index') }}" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-emerald-200 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer group">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-3 shrink-0 group-hover:bg-emerald-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1">TOTAL ORGANISASI</p>
                <h3 class="text-lg font-black text-slate-800 leading-tight group-hover:text-emerald-600 transition">{{ number_format($totalOrganizations, 0, ',', '.') }}</h3>
            </div>
        </a>
    </div>

    <!-- Middle 3 Analytics Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Chart 1: Pertumbuhan Pengguna -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-extrabold text-slate-800 text-lg">Pertumbuhan Pengguna</h4>
                    <p class="text-xs text-slate-400 font-medium">Statistik 6 bulan terakhir</p>
                </div>
                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full border border-indigo-100">
                    +12% vs Bln Lalu
                </span>
            </div>
            <div class="h-48 relative">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Penyelenggara Event -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-extrabold text-slate-800 text-lg">Penyelenggara Event</h4>
                    <p class="text-xs text-slate-400 font-medium">Statistik 6 bulan terakhir</p>
                </div>
                <span class="px-2.5 py-1 bg-purple-50 text-purple-600 text-[10px] font-black rounded-full border border-purple-100">
                    +5% Target
                </span>
            </div>
            <div class="h-48 relative">
                <canvas id="orgGrowthChart"></canvas>
            </div>
        </div>

        <!-- Chart 3: Pertumbuhan Transaksi -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-extrabold text-slate-800 text-lg">Pertumbuhan Transaksi</h4>
                    <p class="text-xs text-slate-400 font-medium">Revenue dalam 6 bulan</p>
                </div>
                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full border border-emerald-100">
                    +8.2% Rev
                </span>
            </div>
            <div class="h-48 relative">
                <canvas id="revenueGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Recent Transactions Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-extrabold text-xl text-slate-800">Transaksi Terakhir</h3>
            <a href="{{ route('admin.transactions.index') }}" class="text-xs font-black text-indigo-600 hover:text-indigo-800 transition">
                Lihat Semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">TGL TRANSAKSI</th>
                        <th class="px-8 py-5">PEMBELI</th>
                        <th class="px-8 py-5">EVENT</th>
                        <th class="px-8 py-5">STATUS</th>
                        <th class="px-8 py-5 text-right">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentTransactions as $trx)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-6 text-sm text-slate-600 font-medium">
                            <p class="font-bold text-slate-800">{{ $trx->created_at->format('d M y - H:i') }}</p>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $trx->order_id }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-extrabold uppercase tracking-wide text-xs text-slate-800">{{ $trx->customer_name }}</p>
                            <p class="text-xs text-slate-400 font-medium">{{ $trx->customer_email }}</p>
                        </td>
                        <td class="px-8 py-6 font-bold text-slate-700 text-sm max-w-xs truncate">
                            {{ $trx->event->title ?? '-' }}
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            @if($trx->status === 'settlement' || $trx->status === 'success')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-wider border border-emerald-100">
                                    SUCCESS
                                </span>
                            @elseif($trx->status === 'pending')
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider border border-amber-100">
                                    PENDING
                                </span>
                            @else
                                <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-full text-[10px] font-black uppercase tracking-wider border border-rose-100">
                                    {{ strtoupper($trx->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 font-black text-indigo-600 whitespace-nowrap text-right text-base">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Setup Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const months = {!! json_encode($months) !!};
    const userGrowth = {!! json_encode($userGrowth) !!};
    const orgGrowth = {!! json_encode($orgGrowth) !!};
    const revenueGrowth = {!! json_encode($revenueGrowth) !!};

    // 1. User Growth Line Chart
    new Chart(document.getElementById('userGrowthChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Pengguna',
                data: userGrowth,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.12)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#6366f1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // 2. Organization Growth Bar Chart
    new Chart(document.getElementById('orgGrowthChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Organisasi',
                data: orgGrowth,
                backgroundColor: '#818cf8',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // 3. Revenue Growth Bar Chart
    new Chart(document.getElementById('revenueGrowthChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue',
                data: revenueGrowth,
                backgroundColor: '#10b981',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
});
</script>
@endsection