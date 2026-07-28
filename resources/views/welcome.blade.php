@extends('layouts.app')

@section('content')

   <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span
                class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">#1
                Event Platform</span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan
                Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#events"
                    class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                    Mulai Jelajah
                </a>
                <a href="#"
                    class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">
                    Cara Pesan
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div
                class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
            <img src="assets/concert.png" alt="Concert"
                class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">

            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase">Terverifikasi</p>
                        <p class="font-bold">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Grid -->
    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
             <!-- Blok Navigasi Filter Kategori -->
   <div class="mb-8 flex gap-4 justify-center">
        <!-- Rujukan awal navigasi bebas bawaan -->
        <a href="/" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-black transition">Semua Kategori</a><!-- Melakukan iterasi nama Tab Kategori dinamis saat jumlah data bertambah  -->
        @foreach($categories as $cat)
            <a href="/?category={{ $cat->slug }}" 
               class="px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded shadow-sm transition">
                {{ $cat->name }}
            </a>
        @endforeach
   </div>

 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($events as $event)
        <div
            class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="relative overflow-hidden aspect-[3/4]">
                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                ? asset('storage/' . $event->poster_path)
                     : 'https://placehold.co/200x600' }}" alt="{{ $event->title }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div
                    class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                    {{ $event->category->name }}</div>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($event->date)->format('d-m-Y H:i') }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="text-2xl font-black text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    <a href="{{ route('events.show', $event->id) }}" class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">Lihat Detail</a>
                </div>
            </div>
        </div>
        @endforeach

        </div>
    </section>

    <!-- Event Organizers Section (Penyelenggara Event Terpercaya) -->
    <section class="w-full bg-gradient-to-br from-[#6366f1] via-[#8b5cf6] to-[#4f46e5] text-white py-24 px-6 relative overflow-hidden">
        <!-- Floating decorative blobs -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2"></div>

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6 tracking-tight">
                Penyelenggara Event Terpercaya
            </h2>
            <p class="text-base md:text-lg text-white/80 max-w-2xl mx-auto mb-16 leading-relaxed">
                Bergabunglah dalam berbagai event yang diselenggarakan oleh organisasi, komunitas, dan lembaga terpercaya. Temukan pengalaman terbaik bersama penyelenggara pilihan.
            </p>

            <!-- Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 justify-center items-stretch max-w-5xl mx-auto">
                @foreach($organizations as $org)
                    <a href="{{ route('organization.profile', $org->id) }}" class="bg-white rounded-3xl p-8 flex flex-col items-center justify-center shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group aspect-square">
                        <div class="w-20 h-20 flex items-center justify-center mb-6">
                            @if($org->logo && Storage::disk('public')->exists($org->logo))
                                <img src="{{ asset('storage/' . $org->logo) }}" alt="{{ $org->name }}" class="max-w-full max-h-full object-contain">
                            @else
                                <!-- Default avatar with initial and gradient -->
                                <div class="w-full h-full rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold uppercase shadow-inner">
                                    {{ substr($org->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <span class="text-lg font-bold text-slate-800 tracking-tight group-hover:text-indigo-600 transition duration-300">
                            {{ $org->name }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Reflection Effect -->
            <div class="hidden md:grid grid-cols-2 md:grid-cols-4 gap-8 justify-center items-stretch max-w-5xl mx-auto opacity-10 pointer-events-none select-none transform scale-y-[-1] origin-top mt-4 h-24 overflow-hidden">
                @foreach($organizations as $org)
                    <div class="bg-white rounded-3xl p-8 flex flex-col items-center justify-center aspect-square w-full">
                        <div class="w-20 h-20 flex items-center justify-center mb-6">
                            @if($org->logo && Storage::disk('public')->exists($org->logo))
                                <img src="{{ asset('storage/' . $org->logo) }}" alt="{{ $org->name }}" class="max-w-full max-h-full object-contain">
                            @else
                                <div class="w-full h-full rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold uppercase">
                                    {{ substr($org->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <span class="text-lg font-bold text-slate-800 tracking-tight">
                            {{ $org->name }}
                        </span>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- Trusted Partners Section -->
    <section class="max-w-7xl mx-auto px-6 py-20 border-t border-slate-100">
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-3">Kolaborasi Terbaik</span>
            <h2 class="text-4xl font-extrabold text-slate-800 leading-tight">Partner AmikomEventHub</h2>
            <p class="text-sm text-slate-500 mt-2">Platform kami didukung oleh berbagai instansi, perusahaan, dan komunitas terpercaya di Indonesia.</p>
        </div>

        <!-- Grid Partner Logos -->
        <div class="flex flex-wrap justify-center gap-6 md:gap-8 items-center">
            @foreach($partners as $partner)
                <div class="w-[130px] sm:w-[150px] aspect-square bg-slate-50 rounded-3xl p-6 flex flex-col items-center justify-center border border-slate-100 hover:border-indigo-200 hover:shadow-lg hover:-translate-y-1 transition duration-300 group">
                    <img src="{{ str_starts_with($partner->logo_url, 'http') ? $partner->logo_url : asset('storage/' . $partner->logo_url) }}" alt="{{ $partner->name }}" class="max-w-full max-h-[60px] object-contain filter grayscale group-hover:grayscale-0 transition duration-300 rounded" onerror="this.onerror=null; this.src='https://placehold.co/100x100?text={{ urlencode($partner->name) }}'">
                    <span class="text-[10px] font-black text-slate-400 group-hover:text-slate-800 text-center mt-3 uppercase tracking-wider truncate w-full">{{ $partner->name }}</span>
                </div>
            @endforeach
        </div>
    </section>

@endsection