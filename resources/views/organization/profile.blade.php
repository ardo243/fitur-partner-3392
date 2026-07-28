@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12 space-y-12">

    <!-- Header Card -->
    <div class="bg-slate-900 bg-gradient-to-br from-[#0f172a] via-[#1e1b4b] to-[#0f172a] text-white rounded-[2.5rem] p-8 md:p-12 shadow-2xl relative overflow-hidden">
        <!-- Decorative Glow Blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-20 -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500 rounded-full mix-blend-screen filter blur-3xl opacity-10 translate-y-1/2 -translate-x-1/2"></div>

        <div class="relative z-10">
            <!-- Top Organizer Info -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Logo -->
                <div class="w-28 h-28 bg-white rounded-3xl p-3 flex items-center justify-center shadow-xl shrink-0 relative overflow-hidden">
                    @if($organization->logo && Storage::disk('public')->exists($organization->logo))
                        <img src="{{ asset('storage/' . $organization->logo) }}" alt="{{ $organization->name }}" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-black uppercase">
                            {{ strtoupper(substr($organization->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <!-- Info Details -->
                <div class="flex-1 text-center md:text-left space-y-3">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                        <span class="inline-block px-3.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-xs font-bold uppercase tracking-wider">
                            Verified Partner
                        </span>
                        <span class="inline-block px-3.5 py-1 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-full text-xs font-bold uppercase tracking-wider">
                            Penyelenggara Resmi Amikom
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white">
                        {{ $organization->name }}
                    </h1>
                    <p class="text-slate-300 font-medium text-base md:text-lg max-w-2xl leading-relaxed">
                        {{ $organization->description ?? 'Penyelenggara Event terpercaya di AmikomEventHub.' }}
                    </p>
                </div>
            </div>

            <!-- Divider Line -->
            <hr class="border-white/10 my-8 md:my-10">

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-8 justify-items-center md:justify-items-start">
                <div class="space-y-1 text-center md:text-left">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Rata-rata Rating</p>
                    <div class="flex items-center justify-center md:justify-start gap-2">
                        <span class="text-3xl font-black text-amber-400">{{ number_format($averageRating, 1) }}</span>
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $averageRating ? 'text-amber-400' : 'text-slate-700' }} fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase">{{ $reviewCount }} Ulasan</p>
                </div>

                <div class="space-y-1 text-center md:text-left">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Acara</p>
                    <span class="text-3xl font-black text-white flex items-center gap-1.5 justify-center md:justify-start">
                        {{ $events->count() }} <span class="text-lg font-medium text-slate-400">Event</span>
                    </span>
                </div>

                <div class="space-y-1 text-center md:text-left col-span-2 md:col-span-1">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Tiket Terjual</p>
                    <span class="text-3xl font-black text-emerald-400 flex items-center gap-1.5 justify-center md:justify-start">
                        {{ $ticketsSold }} <span class="text-lg font-medium text-slate-400">Tiket</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Columns Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

        <!-- Left Column: Reviews & Testimonials -->
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h3 class="text-2xl font-extrabold text-slate-800 flex items-center gap-2">
                    ☀️ Rekam Jejak Ulasan & Rating
                </h3>
                <p class="text-slate-500 text-sm mt-1">Testimoni asli dari para peserta acara sebelumnya.</p>
            </div>

            <!-- Rating Summary Card -->
            <div class="flex flex-col md:flex-row gap-8 items-center bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <!-- Big Score -->
                <div class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-2xl shrink-0 min-w-[160px] border border-slate-100">
                    <span class="text-4xl font-black text-slate-800">{{ number_format($averageRating, 1) }} / 5.0</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-full px-3 py-1 mt-3">
                        Kepuasan Peserta
                    </span>
                </div>

                <!-- Stars Bars -->
                <div class="flex-1 w-full space-y-3">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Distribusi Penilaian Bintang</p>
                    @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center gap-3 text-sm">
                            <span class="w-8 text-right font-black text-slate-500">{{ $i }} ★</span>
                            <div class="flex-1 bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-amber-400 h-full rounded-full" style="width: {{ $starPercentages[$i] }}%"></div>
                            </div>
                            <span class="w-10 text-right text-slate-400 font-bold text-xs">{{ $starPercentages[$i] }}%</span>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Reviews Feed -->
            <div class="space-y-6">
                @if($reviews->isEmpty())
                    <div class="bg-slate-50 rounded-3xl p-12 text-center text-slate-400 font-medium border border-dashed border-slate-200">
                        Belum ada ulasan untuk penyelenggara ini.
                    </div>
                @else
                    @foreach($reviews as $review)
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $review->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=random' }}" alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full border border-slate-100">
                                    <div>
                                        <h5 class="font-bold text-slate-800 text-sm">{{ $review->user->name }}</h5>
                                        <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider">
                                            Acara: {{ $review->event_title }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-0.5 justify-end">
                                        @for($j = 1; $j <= 5; $j++)
                                            <svg class="w-4 h-4 {{ $j <= $review->rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">
                                        {{ $review->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed">{{ $review->review }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right Column: Events List -->
        <div class="lg:col-span-1 space-y-8">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2">
                    🗓️ Acara Diselenggarakan
                </h3>
            </div>

            <!-- Active & Upcoming Events -->
            <div class="space-y-4">
                <span class="inline-block text-xs font-black tracking-widest text-indigo-600 uppercase">
                    🚀 Acara Aktif & Mendatang ({{ $activeEvents->count() }})
                </span>
                
                @if($activeEvents->isEmpty())
                    <div class="bg-slate-50 border border-dashed rounded-3xl p-6 text-center text-slate-400 font-medium text-sm">
                        Tidak ada acara mendatang saat ini.
                    </div>
                @else
                    @foreach($activeEvents as $event)
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition space-y-4">
                            <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                {{ $event->category->name ?? 'Event' }}
                            </span>
                            <h4 class="font-bold text-slate-800 text-lg leading-tight hover:text-indigo-600 transition">
                                <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                            </h4>
                            <div class="text-slate-400 text-xs font-medium space-y-1">
                                <p class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $event->date->format('d M Y, H:i') }} WIB
                                </p>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                                <span class="font-black text-indigo-600 text-lg">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </span>
                                <a href="{{ url('checkout/' . $event->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                    Pesan Tiket <span class="text-base">→</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Completed Events -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <span class="inline-block text-xs font-black tracking-widest text-slate-500 uppercase">
                    🎬 Acara Selesai ({{ $completedEvents->count() }})
                </span>

                @if($completedEvents->isEmpty())
                    <div class="bg-slate-50 border border-dashed rounded-3xl p-6 text-center text-slate-400 font-medium text-sm">
                        Belum ada acara selesai.
                    </div>
                @else
                    @foreach($completedEvents as $event)
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                    {{ $event->category->name ?? 'Event' }}
                                </span>
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                    Selesai
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-lg leading-tight hover:text-indigo-600 transition">
                                <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                            </h4>
                            <div class="text-slate-400 text-xs font-medium space-y-1">
                                <p class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $event->date->format('d M Y, H:i') }} WIB
                                </p>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                                <span class="font-black text-slate-600 text-lg">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('events.show', $event->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                    Lihat Detail <span class="text-base">→</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>

    </div>

</main>
@endsection
