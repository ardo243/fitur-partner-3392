@extends('layouts.admin')
@section('title', 'Manajemen Voucher')
@section('page_title', 'Manajemen Voucher')
@section('page_subtitle', 'Buat dan kelola kode diskon untuk pembeli')

@section('content')
<div class="space-y-6">

    {{-- Header Action --}}
    <div class="flex justify-between items-center">
        <div>
            <p class="text-slate-500 text-sm font-medium">Total {{ $vouchers->total() }} voucher terdaftar</p>
        </div>
        <a href="{{ route('admin.vouchers.create') }}"
            class="flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-sm hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Voucher
        </a>
    </div>

    {{-- Tabel Voucher --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Kode Voucher</th>
                        <th class="px-8 py-5">Diskon</th>
                        <th class="px-8 py-5">Kuota / Sisa</th>
                        <th class="px-8 py-5">Masa Berlaku</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($vouchers as $voucher)
                    <tr class="hover:bg-slate-50/60 transition">
                        {{-- Kode --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 tracking-widest text-sm font-mono">{{ $voucher->code }}</p>
                                    @if($voucher->description)
                                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $voucher->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Diskon --}}
                        <td class="px-8 py-5">
                            @if($voucher->discount_type === 'percent')
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-xl font-black text-sm border border-purple-100">
                                    {{ $voucher->discount_value }}%
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-xl font-black text-sm border border-blue-100">
                                    Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                                </span>
                            @endif
                        </td>

                        {{-- Kuota / Sisa --}}
                        <td class="px-8 py-5">
                            @php $sisa = $voucher->quota - $voucher->used_count; @endphp
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs font-bold text-slate-500 mb-1">
                                    <span>Sisa {{ $sisa }} / {{ $voucher->quota }}</span>
                                    <span>{{ $voucher->used_count }}x dipakai</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $sisa === 0 ? 'bg-rose-400' : 'bg-indigo-500' }} transition-all"
                                         style="width: {{ $voucher->quota > 0 ? ($voucher->used_count / $voucher->quota * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </td>

                        {{-- Masa Berlaku --}}
                        <td class="px-8 py-5 text-sm text-slate-600">
                            @if($voucher->valid_from || $voucher->valid_until)
                                <p class="font-bold text-slate-700">
                                    {{ $voucher->valid_from ? $voucher->valid_from->format('d M Y') : '∞' }}
                                </p>
                                <p class="text-xs text-slate-400 font-medium">
                                    s/d {{ $voucher->valid_until ? $voucher->valid_until->format('d M Y') : 'Selamanya' }}
                                </p>
                            @else
                                <span class="text-slate-400 font-medium text-xs">Tidak Terbatas</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-8 py-5">
                            @if($voucher->isValid())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-xl font-black text-xs border border-emerald-100">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                    BERLAKU
                                </span>
                            @elseif(!$voucher->is_active)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-500 rounded-xl font-black text-xs border border-slate-200">
                                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                    NONAKTIF
                                </span>
                            @elseif($voucher->used_count >= $voucher->quota)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 rounded-xl font-black text-xs border border-rose-100">
                                    KUOTA HABIS
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-600 rounded-xl font-black text-xs border border-amber-100">
                                    KADALUARSA
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2 justify-end">
                                {{-- Toggle --}}
                                <form action="{{ route('admin.vouchers.toggle', $voucher) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $voucher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        class="p-2 rounded-xl {{ $voucher->is_active ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }} transition">
                                        @if($voucher->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        @endif
                                    </button>
                                </form>

                                {{-- Edit --}}
                                <a href="{{ route('admin.vouchers.edit', $voucher) }}"
                                    class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST"
                                    onsubmit="return confirm('Hapus voucher {{ $voucher->code }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-400 font-bold">Belum ada voucher</p>
                                <a href="{{ route('admin.vouchers.create') }}" class="text-indigo-600 text-sm font-bold hover:underline">
                                    + Buat Voucher Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vouchers->hasPages())
        <div class="px-8 py-5 border-t border-slate-100">
            {{ $vouchers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
