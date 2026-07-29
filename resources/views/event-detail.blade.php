@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
    @if(session('error'))
        <div class="lg:col-span-3 mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl font-bold text-sm">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="lg:col-span-3 mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif
    <!-- Left: Poster -->
    <div class="lg:col-span-1">
        <div class="sticky top-32">
            <img src="{{ $event->poster_path ? asset('storage/' . $event->poster_path) : 'https://placehold.co/400x500?text=No+Poster' }}" 
                 alt="{{ $event->title }}"
                 class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white">
            <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                <h4 class="font-bold mb-4">Penyelenggara</h4>
                <div class="flex items-center gap-4">
                    @if($event->organization)
                        @if($event->organization->logo && Storage::disk('public')->exists($event->organization->logo))
                            <img src="{{ asset('storage/' . $event->organization->logo) }}" alt="{{ $event->organization->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm">
                                {{ strtoupper(substr($event->organization->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-slate-800">
                                <a href="{{ route('organization.profile', $event->organization->id) }}" class="hover:underline text-indigo-600 font-semibold">{{ $event->organization->name }}</a>
                            </p>
                            <p class="text-xs text-slate-500">Organisasi Penyelenggara</p>
                        </div>
                    @elseif($event->partner)
                        <img src="{{ str_starts_with($event->partner->logo_url, 'http') ? $event->partner->logo_url : asset('storage/' . $event->partner->logo_url) }}" alt="{{ $event->partner->name }}" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <p class="font-bold text-slate-800">
                                <a href="{{ route('organizer.show', $event->partner->id) }}" class="hover:underline text-indigo-600 font-semibold">{{ $event->partner->name }}</a>
                            </p>
                            <p class="text-xs text-slate-500">Verified Organizer</p>
                        </div>
                    @else
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                            AH
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">AmikomEventHub</p>
                            <p class="text-xs text-slate-500">Penyelenggara Resmi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Details -->
    <div class="lg:col-span-2 space-y-12">
        <div class="space-y-4">
            {{-- KATEGORI ACARA --}}
            <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
                {{ $event->category->name ?? 'Event' }}
            </span>
            
            {{-- JUDUL ACARA --}}
            <h1 class="text-4xl md:text-5xl font-black leading-tight">
                {{ $event->title }}
            </h1>
            
            @php
                $averageRating = $event->reviews->count() > 0 ? round($event->reviews->avg('rating'), 1) : 0;
                $reviewCount = $event->reviews->count();
            @endphp
            @if($reviewCount > 0)
                <div class="flex items-center gap-2 mt-2 bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-2 w-fit">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $averageRating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm font-black text-slate-800">{{ $averageRating }}</span>
                    <span class="text-xs text-slate-400 font-bold">({{ $reviewCount }} Ulasan)</span>
                </div>
            @endif
            
            <div class="flex flex-wrap gap-6 text-slate-500 font-medium">
                {{-- TANGGAL --}}
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                </div>
                
                {{-- LOKASI --}}
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ $event->location }}</span>
                </div>
            </div>
        </div>

        {{-- DESKRIPSI --}}
        <div class="prose prose-slate max-w-none">
            <h3 class="text-2xl font-bold mb-4">Deskripsi Event</h3>
            <p class="text-lg text-slate-600 leading-relaxed">
                {{ $event->description }}
            </p>
        </div>

        {{-- HARGA & CHECKOUT --}}
        <div class="bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                <div>
                    <p class="text-indigo-200 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                    <h2 class="text-5xl font-black">
                        Rp {{ number_format($event->price, 0, ',', '.') }}
                        <span class="text-lg font-medium text-indigo-200">/ orang</span>
                    </h2>
                    <p class="mt-4 text-indigo-100 flex items-center gap-2">
                        @if(now()->gt($event->date))
                            <svg class="w-5 h-5 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-bold text-rose-300">Acara ini telah selesai dilaksanakan.</span>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sisa stok: <span class="font-bold underline">{{ $event->stock }} Tiket lagi!</span>
                        @endif
                    </p>
                </div>
                <div>
                    @if(now()->gt($event->date))
                        <span class="inline-block px-10 py-5 bg-slate-500/50 text-slate-300 rounded-2xl font-black text-xl cursor-not-allowed border border-slate-400/20">
                            Pesan Ditutup
                        </span>
                    @else
                        {{-- LINK CHECKOUT DINAMIS --}}
                        <a href="{{ url('checkout/' . $event->id) }}"
                            class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xl hover:scale-105 transition-transform shadow-xl">
                            Pesan Sekarang
                        </a>
                    @endif
                </div>
            </div>
            <!-- Decoration -->
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
            <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400 opacity-20 rounded-full"></div>
        </div>

        <div class="space-y-4">
            <h3 class="text-xl font-bold">Kebijakan Tiket</h3>
            <ul class="space-y-3 text-slate-500">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Tiket dapat discan di pintu masuk (Check-in).
                </li>
                <li class="flex items-start gap-2 text-rose-500">
                    <svg class="w-5 h-5 text-rose-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tiket yang sudah dibeli tidak dapat direfund.
                </li>
            </ul>
        </div>

        <!-- Testimonials/Reviews Feed -->
        <div class="space-y-8 pt-8 border-t border-slate-100">
            <h3 class="text-2xl font-extrabold text-slate-900">💬 Testimoni Peserta</h3>
            @if($event->reviews->isEmpty())
                <div class="bg-slate-50 rounded-3xl p-8 text-center text-slate-400 font-medium">
                    Belum ada ulasan untuk acara ini. Jadilah yang pertama memberikan ulasan!
                </div>
            @else
                <div class="space-y-6">
                    @foreach($event->reviews as $review)
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $review->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=random' }}" alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full border border-slate-100">
                                    <div>
                                        <h5 class="font-bold text-slate-800 text-sm">{{ $review->user->name }}</h5>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed">{{ $review->review }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Review Submission Form (Conditional) -->
        @auth
            @php
                $hasPurchased = \App\Models\Transaction::where('event_id', $event->id)
                    ->where('customer_email', auth()->user()->email)
                    ->whereIn('status', ['success', 'settlement'])
                    ->exists();
                $eventFinished = now()->gt($event->date);
                $hasReviewed = \App\Models\Review::where('event_id', $event->id)
                    ->where('user_id', auth()->user()->id)
                    ->exists();
            @endphp

            @if($hasPurchased && $eventFinished && !$hasReviewed)
                <div class="bg-indigo-50/50 border border-indigo-100 rounded-[2.5rem] p-8 md:p-12 mt-8">
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Tulis Ulasan Anda</h3>
                    <p class="text-slate-500 text-sm mb-6">Bagikan pengalaman Anda mengikuti acara ini untuk membantu calon pembeli lainnya.</p>

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl font-bold text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('events.reviews.store', $event->id) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Rating (Bintang)</label>
                            <select name="rating" class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                                <option value="5">⭐⭐⭐⭐⭐ (5 Bintang - Sangat Puas)</option>
                                <option value="4">⭐⭐⭐⭐ (4 Bintang - Puas)</option>
                                <option value="3">⭐⭐⭐ (3 Bintang - Cukup)</option>
                                <option value="2">⭐⭐ (2 Bintang - Kurang)</option>
                                <option value="1">⭐ (1 Bintang - Sangat Kurang)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Ulasan Anda</label>
                            <textarea name="review" rows="4" placeholder="Tulis testimoni minimal 5 karakter..." class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required minlength="5"></textarea>
                        </div>
                        <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</main>
@endsection