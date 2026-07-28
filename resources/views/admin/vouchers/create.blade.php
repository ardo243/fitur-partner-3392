@extends('layouts.admin')
@section('title', 'Tambah Voucher Baru')
@section('page_title', 'Tambah Voucher')
@section('page_subtitle', 'Buat kode diskon baru untuk kampanye pemasaran')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-black text-slate-800 text-lg">Voucher Baru</h2>
                    <p class="text-sm text-slate-400 font-medium">Isi form berikut untuk membuat kode diskon</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.vouchers.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-700 text-sm font-bold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Kode Voucher --}}
            <div>
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                    Kode Voucher <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="code" value="{{ strtoupper(old('code')) }}"
                    placeholder="contoh: MAHASISWA50"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-black tracking-widest text-slate-800 uppercase placeholder:normal-case placeholder:font-normal placeholder:tracking-normal"
                    required>
                <p class="text-xs text-slate-400 font-medium mt-1.5">Kode akan otomatis dikonversi ke huruf besar</p>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                    Deskripsi (Opsional)
                </label>
                <input type="text" name="description" value="{{ old('description') }}"
                    placeholder="contoh: Diskon khusus untuk mahasiswa AMIKOM"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-medium text-slate-700">
            </div>

            {{-- Tipe & Nilai Diskon --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                        Tipe Diskon <span class="text-rose-500">*</span>
                    </label>
                    <select name="discount_type"
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-bold text-slate-700">
                        <option value="percent" {{ old('discount_type') === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                        <option value="fixed"   {{ old('discount_type') === 'fixed'   ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                        Nilai Diskon <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="discount_value" value="{{ old('discount_value') }}"
                        placeholder="contoh: 50"
                        min="1"
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-bold text-slate-700"
                        required>
                </div>
            </div>

            {{-- Kuota --}}
            <div>
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                    Kuota Penggunaan <span class="text-rose-500">*</span>
                </label>
                <input type="number" name="quota" value="{{ old('quota', 100) }}"
                    min="1"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-bold text-slate-700"
                    required>
                <p class="text-xs text-slate-400 font-medium mt-1.5">Berapa kali voucher ini bisa digunakan secara total</p>
            </div>

            {{-- Masa Berlaku --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                        Berlaku Dari
                    </label>
                    <input type="date" name="valid_from" value="{{ old('valid_from') }}"
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-medium text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                        Berlaku Hingga
                    </label>
                    <input type="date" name="valid_until" value="{{ old('valid_until') }}"
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-medium text-slate-700">
                </div>
            </div>
            <p class="text-xs text-slate-400 font-medium -mt-3">Kosongkan jika tidak ada batas waktu</p>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-5 h-5 text-indigo-600 rounded-lg border-slate-300 focus:ring-indigo-500 cursor-pointer">
                <label for="is_active" class="text-sm font-bold text-slate-700 cursor-pointer">
                    Aktifkan voucher ini segera setelah dibuat
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-200">
                    Simpan Voucher
                </button>
                <a href="{{ route('admin.vouchers.index') }}"
                    class="px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
