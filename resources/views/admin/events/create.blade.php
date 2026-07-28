@extends('layouts.admin')
@section('title', 'Tambah Event Baru - Admin')
@section('page_title', 'Tambah Event Baru')
@section('page_subtitle', 'Masukkan detail acara baru yang akan diselenggarakan.')

@section('content')
<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl">
    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mt-2">
        @csrf
        
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Judul Event</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
            @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kategori</label>
            <select name="category_id" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Tanggal & Waktu</label>
                <input type="datetime-local" name="date" value="{{ old('date') }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                @error('date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                @error('location') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', 0) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required min="0">
                @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kapasitas (Stok)</label>
                <input type="number" name="stock" value="{{ old('stock', 1) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required min="1">
                @error('stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

    <div class="mb-6">
        <label class="block mb-2 font-medium text-gray-700">Poster Event (Opsional)</label>
        <input type="file" name="poster" accept="image/*" class="w-full border border-gray-300 p-2.5 rounded">
    </div>

        {{-- ========== TIER HARGA DINAMIS ========== --}}
        <div class="mt-4 pt-6 border-t border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-black text-slate-800 text-base">Tier Harga Dinamis</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Opsional — Pisahkan tiket menjadi Early Bird, Presale, Regular, dll. Jika dikosongkan, harga di atas akan digunakan.</p>
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
                {{-- Baris tier akan ditambah oleh JS --}}
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-4 border-t border-slate-100 mt-4">
            <a href="{{ route('admin.events.index') }}" class="px-6 py-4 text-slate-500 font-bold hover:text-slate-800 transition">Batal</a>
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">Simpan Event</button>
        </div>
    </form>
</div>

<script>
let tierIndex = 0;
const tierNames = ['Early Bird', 'Presale 1', 'Presale 2', 'Regular', 'VIP'];

document.getElementById('addTierBtn').addEventListener('click', function () {
    const container = document.getElementById('tierContainer');
    const suggestedName = tierNames[tierIndex] ?? ('Tier ' + (tierIndex + 1));
    const row = document.createElement('div');
    row.className = 'relative bg-slate-50 border-2 border-slate-100 rounded-2xl p-5 space-y-4';
    row.innerHTML = `
        <button type="button" onclick="this.closest('div.relative').remove()"
            class="absolute top-3 right-3 p-1.5 text-slate-400 hover:text-rose-500 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Nama Tier</label>
                <input type="text" name="tiers[${tierIndex}][name]" value="${suggestedName}" placeholder="Early Bird"
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
});
</script>
@endsection