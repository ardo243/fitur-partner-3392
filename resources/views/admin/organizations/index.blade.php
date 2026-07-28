@extends('layouts.admin')
@section('title', 'Kelola Organisasi - Admin')
@section('page_title', 'Kelola Organisasi')
@section('page_subtitle', 'Manajemen data organisasi penyelenggara event.')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
    <form action="{{ route('admin.organizations.index') }}" method="GET" class="flex-1 sm:max-w-md">
        <div class="relative">
            <input
                type="text"
                name="search"
                placeholder="Cari nama atau email organisasi..."
                value="{{ request('search') }}"
                class="w-full pl-10 pr-4 py-3 bg-white border border-slate-100 shadow-sm rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
            >
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="flex flex-col sm:flex-row justify-between items-center px-8 py-6 border-b border-slate-100 gap-4">
        <h3 class="text-xl font-bold text-slate-800">Daftar Organisasi</h3>
        <a href="{{ route('admin.organizations.create') }}" class="px-6 py-3 bg-indigo-700 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-800 active:scale-95 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Organisasi
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 w-16">No</th>
                    <th class="px-8 py-4 w-24">Logo</th>
                    <th class="px-8 py-4">Nama Organisasi</th>
                    <th class="px-8 py-4">Email</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t border-slate-100">
                @forelse($organizations as $index => $org)
                <tr class="hover:bg-slate-50/30 transition">
                    <td class="px-8 py-6 font-bold text-slate-400">
                        {{ $organizations->firstItem() + $index }}
                    </td>
                    <td class="px-8 py-6">
                        @if($org->logo && Storage::disk('public')->exists($org->logo))
                            <div class="w-10 h-10 bg-slate-50 rounded-xl p-1 flex items-center justify-center border shadow-sm shrink-0">
                                <img src="{{ asset('storage/' . $org->logo) }}" class="max-w-full max-h-full object-contain" alt="{{ $org->name }}">
                            </div>
                        @else
                            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center shadow-sm shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-8 py-6 font-semibold text-slate-800">
                        {{ $org->name }}
                    </td>
                    <td class="px-8 py-6 text-slate-500 text-sm">
                        {{ $org->email }}
                    </td>
                    <td class="px-8 py-6">
                        @if($org->status === 'active')
                            <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold">
                                Aktif
                            </span>
                        @else
                            <span class="inline-block px-4 py-1.5 bg-rose-50 text-rose-600 rounded-full text-xs font-bold">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-end items-center gap-4">
                            <a href="{{ route('admin.organizations.edit', $org->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition" title="Edit Organisasi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.organizations.destroy', $org->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus organisasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Hapus Organisasi">
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
                    <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium">
                        Belum ada organisasi yang ditambahkan atau pencarian tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($organizations->hasPages())
    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
        {{ $organizations->links() }}
    </div>
    @endif
</div>
@endsection
