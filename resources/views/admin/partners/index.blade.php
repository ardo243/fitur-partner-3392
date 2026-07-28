@extends('layouts.admin')
@section('title', 'Kelola Partner - Admin')
@section('page_title', 'Kelola Partner')
@section('page_subtitle', 'Buat, edit, atau hapus partner dengan mudah.')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <form action="{{ route('admin.partners.index') }}" method="GET">
        <input
            type="text"
            name="search"
            placeholder="Cari partner..."
            value="{{ request('search') }}"
            class="px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
    </form>

    <a href="{{ route('admin.partners.create') }}" class="inline-block px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold shadow-lg shadow-slate-200 hover:bg-slate-800 active:scale-95 transition">
        + Tambah Partner Baru
    </a>
</div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 w-16">No</th>
                    <th class="px-8 py-4">Partner</th>
                    <th class="px-8 py-4">Logo</th>
                    <th class="px-8 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($partners as $index => $partner)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-8 py-6 font-bold text-slate-400">{{ $partners->firstItem() + $index }}</td>
                    <td class="px-8 py-6">
                        <p class="font-black text-slate-800">{{ $partner->name }}</p>
                    </td>
                    <td class="px-8 py-6">
                        <img src="{{ str_starts_with($partner->logo_url, 'http') ? $partner->logo_url : asset('storage/' . $partner->logo_url) }}" class="w-20 h-20 rounded-xl object-cover shadow-sm" alt="Logo {{ $partner->name }}">
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.partners.edit', $partner->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus partner ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-10 text-center text-slate-500">Belum ada partner yang ditambahkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-8 py-6 bg-slate-50/50 border-t items-center">
        {{ $partners->links() }}
    </div>
    
</div>
@endsection