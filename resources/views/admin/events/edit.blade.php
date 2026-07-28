@extends('layouts.admin')
@section('title', 'Edit Event - Admin')
@section('page_title', 'Edit Event')
@section('page_subtitle', 'Ubah detail acara.')

@section('content')
<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl">
    <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Judul Event</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
            @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kategori</label>
            <select name="category_id" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">{{ old('description', $event->description) }}</textarea>
            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Tanggal & Waktu</label>
                <input type="datetime-local" name="date" value="{{ old('date', $event->date->format('Y-m-d\TH:i')) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                @error('date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Lokasi</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                @error('location') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $event->price) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required min="0">
                @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kapasitas (Stok)</label>
                <input type="number" name="stock" value="{{ old('stock', $event->stock) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required min="1">
                @error('stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Poster Event (Opsional)</label>
            <input type="file" name="poster" accept="image/*" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">
            @if($event->poster_path)
                <p class="text-sm text-slate-500 mt-2">Poster saat ini: <a href="{{ asset('storage/' . $event->poster_path) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat</a></p>
            @endif
            @error('poster') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- ========== TIER HARGA DINAMIS ========== --}}
        <div class="pt-6 border-t border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-black text-slate-800 text-base">Tier Harga Dinamis</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Opsional — Pisahkan tiket menjadi Early Bird, Presale, Regular, dll.</p>
                </div>
                <button type="button" id="addTierBtn"
                   class="flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-700 rounded-xl font-bold text-sm border border-slate-200 hover:bg-slate-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Tier
                </button>
            </div>

            <div id="tierContainer" class="space-y-4">
                {{-- Tier yang sudah ada --}}
                @foreach($event->ticketTiers as $idx => $tier)
                <div class="relative bg-slate-50 border-2 border-slate-100 rounded-2xl p-5 space-y-4 existing-tier">
                    <input type="hidden" name="existing_tiers[{{ $idx }}][id]" value="{{ $tier->id }}">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-1 bg-slate-200 text-slate-700 rounded-lg text-xs font-black">Tier Tersimpan</span>
                        <span class="text-xs text-slate-400 font-medium">Terjual: {{ $tier->sold_count }} / {{ $tier->quota }}</span>
                        <button type="button" onclick="deleteTier(this, {{ $tier->id }})"
                            class="ml-auto p-1.5 text-slate-400 hover:text-rose-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Nama Tier</label>
                            <input type="text" name="existing_tiers[{{ $idx }}][name]" value="{{ $tier->name }}"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-bold text-slate-800 text-sm transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Harga (Rp)</label>
                            <input type="number" name="existing_tiers[{{ $idx }}][price]" value="{{ $tier->price }}" min="0"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-bold text-slate-800 text-sm transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Kuota</label>
                            <input type="number" name="existing_tiers[{{ $idx }}][quota]" value="{{ $tier->quota }}" min="{{ $tier->sold_count }}"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-bold text-slate-800 text-sm transition" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Mulai Dijual</label>
                            <input type="datetime-local" name="existing_tiers[{{ $idx }}][sale_start]"
                                value="{{ $tier->sale_start ? $tier->sale_start->format('Y-m-d\TH:i') : '' }}"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-medium text-slate-700 text-sm transition">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Berakhir Dijual</label>
                            <input type="datetime-local" name="existing_tiers[{{ $idx }}][sale_end]"
                                value="{{ $tier->sale_end ? $tier->sale_end->format('Y-m-d\TH:i') : '' }}"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-medium text-slate-700 text-sm transition">
                        </div>
                    </div>
                    <input type="hidden" name="existing_tiers[{{ $idx }}][sort_order]" value="{{ $tier->sort_order }}">
                </div>
                @endforeach
            </div>

            {{-- Daftar tier yang dihapus --}}
            <div id="deletedTiers"></div>
        </div>

        <div class="pt-4 flex justify-end gap-4 border-t border-slate-100">
            <a href="{{ route('admin.events.index') }}" class="px-6 py-4 text-slate-500 font-bold hover:text-slate-800 transition">Batal</a>
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
let tierIndex = 100; // Mulai dari 100 agar tidak bentrok dengan index existing
const tierNames = ['Early Bird', 'Presale 1', 'Presale 2', 'Regular', 'VIP'];
let newTierCount = 0;

document.getElementById('addTierBtn').addEventListener('click', function () {
    const container = document.getElementById('tierContainer');
    const suggestedName = tierNames[newTierCount] ?? ('Tier ' + (newTierCount + 1));
    const row = document.createElement('div');
    row.className = 'relative bg-slate-50 border-2 border-slate-200 rounded-2xl p-5 space-y-4';
    row.innerHTML = `
        <div class="flex items-center gap-2 mb-2">
            <span class="px-2.5 py-1 bg-slate-200 text-slate-700 rounded-lg text-xs font-black">Tier Baru</span>
            <button type="button" onclick="this.closest('div.relative').remove()"
                class="ml-auto p-1.5 text-slate-400 hover:text-rose-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Nama Tier</label>
                <input type="text" name="tiers[${tierIndex}][name]" value="${suggestedName}"
                    class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-bold text-slate-800 text-sm transition" required>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Harga (Rp)</label>
                <input type="number" name="tiers[${tierIndex}][price]" placeholder="75000" min="0"
                    class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-bold text-slate-800 text-sm transition" required>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Kuota</label>
                <input type="number" name="tiers[${tierIndex}][quota]" placeholder="50" min="1"
                    class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-bold text-slate-800 text-sm transition" required>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Mulai Dijual</label>
                <input type="datetime-local" name="tiers[${tierIndex}][sale_start]"
                    class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-medium text-slate-700 text-sm transition">
            </div>
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Berakhir Dijual</label>
                <input type="datetime-local" name="tiers[${tierIndex}][sale_end]"
                    class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none font-medium text-slate-700 text-sm transition">
            </div>
        </div>
        <input type="hidden" name="tiers[${tierIndex}][sort_order]" value="${tierIndex}">
    `;
    container.appendChild(row);
    tierIndex++;
    newTierCount++;
});

function deleteTier(btn, tierId) {
    if (!confirm('Hapus tier ini?')) return;
    const container = document.getElementById('deletedTiers');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'delete_tiers[]';
    input.value = tierId;
    container.appendChild(input);
    btn.closest('div.relative').remove();
}
</script>
@endsection
