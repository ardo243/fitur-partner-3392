@extends('layouts.organization')

@section('title', 'Kelola Event - Organisasi')
@section('page_title', 'Kelola Event')
@section('page_subtitle', 'Buat, ubah, dan atur acara seru Anda di sini.')

@section('content')
<div class="mb-6 flex justify-between items-center animate-fade-in">
    <h3 class="text-xl font-extrabold text-slate-800">Daftar Event Anda</h3>
    <a href="{{ route('organization.events.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Event Baru
    </a>
</div>

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden animate-fade-in">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5 w-16">No</th>
                    <th class="px-8 py-5">Poster</th>
                    <th class="px-8 py-5">Detail Event</th>
                    <th class="px-8 py-5">Harga & Stok</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($events as $index => $event)
                <tr class="hover:bg-slate-50/30 transition">
                    <td class="px-8 py-6 font-bold text-slate-400 text-sm">
                        {{ $events->firstItem() + $index }}
                    </td>
                    <td class="px-8 py-6">
                        @if($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                            <img src="{{ asset('storage/' . $event->poster_path) }}" class="w-16 h-20 rounded-xl object-cover shadow-sm border border-slate-100">
                        @else
                            <div class="w-16 h-20 bg-slate-50 rounded-xl flex flex-col items-center justify-center text-slate-400 border border-slate-200/50">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-[8px] font-bold uppercase mt-1 tracking-wider text-slate-400">Poster</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-extrabold text-slate-800 text-base leading-snug hover:text-indigo-600 transition">{{ $event->title }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="inline-block bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2.5 py-1 rounded-md border border-indigo-100/50">
                                {{ $event->category->name ?? 'Uncategorized' }}
                            </span>
                            @if($event->date && \Carbon\Carbon::parse($event->date)->lt(now()))
                                <span class="inline-block bg-slate-100 text-slate-600 text-[10px] font-bold px-2.5 py-1 rounded-md border border-slate-200">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-block bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-md border border-emerald-100">
                                    Aktif
                                </span>
                            @endif
                            <span class="text-xs text-slate-400 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') : '-' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-black text-indigo-600 text-base">
                            {{ $event->price > 0 ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'Gratis' }}
                        </p>
                        <span class="inline-flex items-center gap-1 text-xs text-slate-400 mt-1 font-medium">
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Stok: {{ $event->stock }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex gap-2 justify-end">
                            <a href="{{ route('organization.events.edit', $event->id) }}" 
                               class="p-2.5 bg-slate-50 text-slate-600 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 border border-slate-100 hover:border-indigo-100 transition duration-200"
                               title="Edit Event">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <form action="{{ route('organization.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2.5 bg-slate-50 text-slate-600 rounded-xl hover:bg-rose-50 hover:text-rose-600 border border-slate-100 hover:border-rose-100 transition duration-200"
                                        title="Hapus Event">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-16 text-center">
                        <div class="max-w-sm mx-auto flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4 border border-slate-100">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h4 class="font-extrabold text-slate-800 text-lg">Belum Ada Event</h4>
                            <p class="text-xs text-slate-500 font-medium mt-1.5 leading-relaxed">
                                Anda belum menambahkan event apa pun ke dalam sistem. Klik tombol di bawah untuk membuat event pertama Anda.
                            </p>
                            <a href="{{ route('organization.events.create') }}" class="mt-5 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md transition duration-200">
                                + Buat Event Baru
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($events->hasPages())
    <div class="px-8 py-5 bg-slate-50 border-t border-slate-100">
        {{ $events->links() }}
    </div>
    @endif
</div>
@endsection
