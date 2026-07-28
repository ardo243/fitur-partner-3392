@extends('layouts.app')
@section('title', 'Checkout - ' . $event->title)
@section('content')
<main class="max-w-3xl mx-auto px-6 py-20">
    <div class="mb-12">
        <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 font-bold flex items-center gap-2 mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Event
        </a>
        <h1 class="text-4xl font-extrabold">Checkout</h1>
        <p class="text-slate-500 mt-2">Lengkapi data Anda untuk mendapatkan tiket.</p>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 gap-8">

        {{-- ====== TIER HARGA AKTIF ====== --}}
        @if($tiers->count() > 0)
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-2">Informasi Harga Tiket</h3>
            <p class="text-slate-400 text-sm mb-5 font-medium">Harga berubah secara dinamis sesuai periode & kuota penjualan.</p>
            <div class="space-y-3">
                @foreach($tiers as $tier)
                @php
                    $isActive = $activeTier && $activeTier->id === $tier->id;
                    $isSoldOut = $tier->sold_count >= $tier->quota;
                @endphp
                <div class="flex items-center justify-between p-4 rounded-2xl border-2 transition-all
                    {{ $isActive ? 'border-indigo-500 bg-indigo-50 ring-4 ring-indigo-500/10' : 'border-slate-100 bg-slate-50 opacity-60' }}">
                    <div class="flex items-center gap-3">
                        @if($isActive)
                            <div class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @else
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        @endif
                        <div>
                            <p class="font-black text-slate-800 text-sm {{ $isActive ? 'text-indigo-900' : '' }}">{{ $tier->name }}</p>
                            @if($isSoldOut)
                                <p class="text-xs text-rose-500 font-bold">Habis Terjual</p>
                            @elseif(!$isActive && $activeTier && $tier->sort_order > $activeTier->sort_order)
                                <p class="text-xs text-slate-400 font-medium">Menunggu tahap sebelumnya habis</p>
                            @elseif(!$isActive && $tier->sale_start && now()->lt($tier->sale_start))
                                <p class="text-xs text-slate-400 font-medium">Mulai {{ $tier->sale_start->format('d M Y') }}</p>
                            @else
                                <p class="text-xs text-slate-400 font-medium">Sisa {{ $tier->quota - $tier->sold_count }} tiket</p>
                            @endif
                        </div>
                    </div>
                    <span class="font-black text-base {{ $isActive ? 'text-indigo-700' : 'text-slate-500 line-through' }}">Rp {{ number_format($tier->price, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ====== SUMMARY CARD ====== --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 border-b pb-4">Ringkasan Pesanan</h3>
            <div class="flex gap-6 items-start">
                <img src="{{ ($event->poster_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->poster_path))
                 ? asset('storage/' . $event->poster_path)
                 : 'https://placehold.co/200x200' }}"
                    alt="Event" class="w-24 h-24 rounded-2xl object-cover">
                <div>
                    <h4 class="font-extrabold text-lg">{{ $event->title }}</h4>
                    <p class="text-slate-500">{{ $event->date->format('d M Y') }} • {{ $event->location }}</p>
                    <p class="text-indigo-600 font-bold mt-2" id="tierNameLabel">
                        1 x
                        @if($activeTier)
                            {{ $activeTier->name }} — Rp {{ number_format($activeTier->price, 0, ',', '.') }}
                        @else
                            Tiket Reguler — Rp {{ number_format($event->price, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
            </div>

            @php
                $basePrice = $activeTier ? $activeTier->price : $event->price;
                $adminFee = $basePrice == 0 ? 0 : 5000;
                $totalPrice = $basePrice + $adminFee;
            @endphp
            {{-- Ringkasan Harga --}}
            <div class="mt-8 pt-6 border-t space-y-3">
                <div class="flex justify-between text-slate-500">
                    <span>Harga Tiket</span>
                    <span id="basePriceDisplay">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                </div>
                {{-- Baris diskon — tersembunyi dulu --}}
                <div class="flex justify-between text-emerald-600 font-bold hidden" id="discountRow">
                    <span id="discountLabel">Diskon Voucher</span>
                    <span id="discountAmountDisplay">-Rp 0</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Biaya Layanan</span>
                    <span id="adminFeeDisplay">Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t">
                    <span>Total Bayar</span>
                    <span class="text-indigo-600" id="totalPriceDisplay">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- ====== INPUT VOUCHER ====== --}}
            <div class="mt-6 pt-6 border-t">
                <label class="block text-sm font-black text-slate-700 mb-3 uppercase tracking-wider">
                    🎟️ Kode Voucher
                </label>
                <div class="flex gap-3">
                    <input type="text" id="voucherInput" placeholder="contoh: MAHASISWA50"
                        class="flex-1 px-5 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-bold tracking-widest text-slate-800 uppercase placeholder:normal-case placeholder:font-normal placeholder:tracking-normal text-sm">
                    <button type="button" id="applyVoucherBtn"
                        class="px-6 py-3.5 bg-indigo-600 text-white rounded-2xl font-bold text-sm hover:bg-indigo-700 active:scale-95 transition-all whitespace-nowrap shadow-md shadow-indigo-200">
                        Gunakan
                    </button>
                </div>
                {{-- Feedback Voucher --}}
                <div id="voucherFeedback" class="hidden mt-3 p-4 rounded-2xl flex items-center gap-3">
                    <span id="voucherIcon"></span>
                    <p id="voucherMessage" class="font-bold text-sm"></p>
                </div>
            </div>
        </div>

        {{-- ====== FORM DATA PEMESAN ====== --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            @guest
            <div class="mb-8 p-5 bg-indigo-50 border border-indigo-100 rounded-3xl flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <h4 class="font-extrabold text-indigo-950 text-sm">Isi data lebih cepat!</h4>
                    <p class="text-xs text-indigo-700/80 font-medium mt-1">Masuk dengan Google untuk mengisi data pemesanan secara otomatis.</p>
                </div>
                <a href="{{ route('auth.google') }}" class="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-700 hover:bg-slate-50 transition shadow-sm active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    Continue with Google
                </a>
            </div>
            @endguest

            <h3 class="text-xl font-bold mb-6 italic text-indigo-600 underline underline-offset-8">📦 Data Pemesan</h3>
            <form id="checkoutForm" action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                @csrf

                {{-- Hidden fields untuk voucher & tier yang dipilih --}}
                <input type="hidden" name="voucher_code" id="appliedVoucherCode" value="">

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="customer_name" placeholder="Masukkan nama sesuai identitas"
                        class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                        required value="{{ old('customer_name', auth()->user()->name ?? '') }}">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email Aktif</label>
                        <input type="email" name="customer_email" placeholder="contoh@gmail.com"
                            class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                            required value="{{ old('customer_email', auth()->user()->email ?? '') }}">
                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">*E-Ticket akan dikirim ke email ini</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">No. WhatsApp</label>
                        <input type="tel" name="customer_phone" placeholder="08xxxxxxx"
                            class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                            required value="{{ old('customer_phone') }}">
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                    Lanjut Pembayaran
                </button>
                <p class="text-center text-xs text-slate-400">Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.</p>
            </form>
        </div>

    </div>
</main>

<script>
// ============================================================
// Data awal dari server
// ============================================================
const eventBasePrice    = {{ $event->price }};
const activeTierId      = {{ $activeTier ? $activeTier->id : 'null' }};
const activeTierPrice   = {{ $activeTier ? $activeTier->price : $event->price }};
const csrfToken         = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
const applyVoucherUrl   = '{{ route("checkout.voucher.apply") }}';

let currentBasePrice    = activeTierPrice; // harga dasar yang sedang aktif
let currentDiscount     = 0;
let appliedVoucherCode  = '';

// ============================================================
// Fungsi Pembantu
// ============================================================
function formatRupiah(n) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
}

function updateSummaryDisplay() {
    const adminFee = (currentBasePrice == 0) ? 0 : 5000;
    const total = Math.max(0, currentBasePrice - currentDiscount) + adminFee;
    document.getElementById('basePriceDisplay').textContent = formatRupiah(currentBasePrice);
    document.getElementById('adminFeeDisplay').textContent = formatRupiah(adminFee);
    document.getElementById('totalPriceDisplay').textContent = formatRupiah(total);

    if (currentDiscount > 0) {
        document.getElementById('discountRow').classList.remove('hidden');
        document.getElementById('discountAmountDisplay').textContent = '- ' + formatRupiah(currentDiscount);
    } else {
        document.getElementById('discountRow').classList.add('hidden');
    }
}

// ============================================================
// Apply Voucher AJAX
// ============================================================
document.getElementById('applyVoucherBtn').addEventListener('click', async function () {
    const code = document.getElementById('voucherInput').value.trim().toUpperCase();
    if (!code) {
        showFeedback(false, 'Masukkan kode voucher terlebih dahulu.');
        return;
    }

    this.textContent = '...';
    this.disabled = true;

    try {
        const res = await fetch(applyVoucherUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ code: code, base_price: currentBasePrice }),
        });

        const data = await res.json();

        if (data.valid) {
            currentDiscount    = data.discount_amount;
            appliedVoucherCode = code;
            document.getElementById('appliedVoucherCode').value = code;

            const discLabel = data.discount_type === 'percent'
                ? `Diskon ${data.discount_value}% (Voucher ${code})`
                : `Diskon Rp ${new Intl.NumberFormat('id-ID').format(data.discount_value)} (Voucher ${code})`;
            document.getElementById('discountLabel').textContent = discLabel;

            updateSummaryDisplay();
            showFeedback(true, `✅ ${data.message} Hemat ${formatRupiah(data.discount_amount)}!`);
        } else {
            showFeedback(false, `❌ ${data.message}`);
        }
    } catch (e) {
        showFeedback(false, 'Terjadi kesalahan jaringan. Coba lagi.');
    }

    this.textContent = 'Gunakan';
    this.disabled = false;
});

function showFeedback(success, msg) {
    const el = document.getElementById('voucherFeedback');
    const msgEl = document.getElementById('voucherMessage');
    el.className = `mt-3 p-4 rounded-2xl flex items-center gap-3 ${success ? 'bg-emerald-50 border border-emerald-100 text-emerald-700' : 'bg-rose-50 border border-rose-100 text-rose-600'}`;
    msgEl.textContent = msg;
    el.classList.remove('hidden');
}

function hideFeedback() {
    document.getElementById('voucherFeedback').classList.add('hidden');
}
</script>
@endsection