@extends('layouts.admin')
@section('title', 'Edit Voucher - ' . $voucher->code)
@section('page_title', 'Edit Voucher')
@section('page_subtitle', 'Perbarui detail kode diskon')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-black text-slate-800 text-lg font-mono tracking-widest">{{ $voucher->code }}</h2>
                    <p class="text-sm text-slate-400 font-medium">Dipakai {{ $voucher->used_count }}x dari {{ $voucher->quota }} kuota</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

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
                <input type="text" name="code" value="{{ strtoupper(old('code', $voucher->code)) }}"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-black tracking-widest text-slate-800 uppercase"
                    required>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                    Deskripsi (Opsional)
                </label>
                <input type="text" name="description" value="{{ old('description', $voucher->description) }}"
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
                        <option value="percent" {{ old('discount_type', $voucher->discount_type) === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                        <option value="fixed"   {{ old('discount_type', $voucher->discount_type) === 'fixed'   ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                        Nilai Diskon <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="discount_value" value="{{ old('discount_value', $voucher->discount_value) }}"
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
                <input type="number" name="quota" value="{{ old('quota', $voucher->quota) }}"
                    min="{{ $voucher->used_count }}"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-bold text-slate-700"
                    required>
                <p class="text-xs text-slate-400 font-medium mt-1.5">Minimal {{ $voucher->used_count }} (sudah terpakai)</p>
            </div>

            {{-- Masa Berlaku --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                        Berlaku Dari
                    </label>
                    <input type="date" name="valid_from"
                        value="{{ old('valid_from', $voucher->valid_from ? $voucher->valid_from->format('Y-m-d') : '') }}"
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-medium text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">
                        Berlaku Hingga
                    </label>
                    <input type="date" name="valid_until"
                        value="{{ old('valid_until', $voucher->valid_until ? $voucher->valid_until->format('Y-m-d') : '') }}"
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-medium text-slate-700">
                </div>
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}
                    class="w-5 h-5 text-indigo-600 rounded-lg border-slate-300 focus:ring-indigo-500 cursor-pointer">
                <label for="is_active" class="text-sm font-bold text-slate-700 cursor-pointer">
                    Voucher aktif dan bisa digunakan pembeli
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-200">
                    Simpan Perubahan
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
