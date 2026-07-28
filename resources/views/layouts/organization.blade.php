<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Organization Dashboard') - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-955 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-8 sticky top-0 h-screen shadow-xl">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-extrabold text-xl shadow-md">AH</div>
            <div class="flex flex-col">
                <span class="text-lg font-black text-white tracking-tight leading-none">EventHub</span>
                <span class="text-[9px] text-indigo-300 font-bold uppercase tracking-wider mt-0.5">Organisasi</span>
            </div>
        </div>

        <nav class="flex-1 space-y-2">
            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400 mb-4 px-2">Menu Utama</p>
            
            <a href="{{ route('organization.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organization.dashboard') ? 'bg-indigo-800 text-white shadow-lg shadow-indigo-950/50' : 'hover:bg-indigo-800/60' }} rounded-xl font-bold transition duration-200">
                <svg class="w-5 h-5 {{ request()->routeIs('organization.dashboard') ? 'text-indigo-300' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('organization.events.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organization.events.*') ? 'bg-indigo-800 text-white shadow-lg shadow-indigo-950/50' : 'hover:bg-indigo-800/60' }} rounded-xl font-bold transition duration-200">
                <svg class="w-5 h-5 {{ request()->routeIs('organization.events.*') ? 'text-indigo-300' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Kelola Event
            </a>
            
            <a href="{{ route('organization.transactions.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('organization.transactions.*') ? 'bg-indigo-800 text-white shadow-lg shadow-indigo-950/50' : 'hover:bg-indigo-800/60' }} rounded-xl font-bold transition duration-200">
                <svg class="w-5 h-5 {{ request()->routeIs('organization.transactions.*') ? 'text-indigo-300' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Laporan Transaksi
            </a>
        </nav>

        <div class="pt-6 border-t border-indigo-800/60">
            <form action="{{ route('organization.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 text-indigo-300 hover:text-white hover:bg-rose-600/10 hover:text-rose-400 rounded-xl transition duration-200 font-bold text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto w-full">
        <!-- Header -->
        <header class="flex justify-between items-center mb-10 w-full col-span-full">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-800">@yield('page_title', 'Dashboard')</h1>
                <p class="text-slate-500 font-medium mt-1">@yield('page_subtitle', 'Selamat datang di panel organisasi Anda!')</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="font-black text-slate-800 text-sm">{{ session('organization_name') }}</p>
                    <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider">Mitra Organisasi</p>
                </div>
                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center p-1">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(session('organization_name', 'Org')) }}&background=6366f1&color=fff&bold=true" class="w-10 h-10 rounded-xl" alt="Org Avatar">
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl mb-6 font-bold text-sm flex items-center gap-3 shadow-sm animate-pulse">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl mb-6 font-bold text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="w-full">
            @yield('content')
        </div>
    </main>
</body>

</html>
