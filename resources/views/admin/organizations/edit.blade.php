@extends('layouts.admin')
@section('title', 'Edit Organisasi - Admin')
@section('page_title', 'Edit Organisasi')
@section('page_subtitle', 'Perbarui detail data organisasi penyelenggara event.')

@section('content')
<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl">
    <form action="{{ route('admin.organizations.update', $organization->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Organisasi</label>
                <input type="text" name="name" value="{{ old('name', $organization->name) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama PIC (Penanggung Jawab)</label>
                <input type="text" name="pic_name" value="{{ old('pic_name', $organization->pic_name) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                @error('pic_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email</label>
                <input type="email" name="email" value="{{ old('email', $organization->email) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">
                @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone', $organization->phone) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                @error('phone') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Status</label>
                <select name="status" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
                    <option value="active" {{ old('status', $organization->status) == 'active' ? 'selected' : '' }}>Aktif (Active)</option>
                    <option value="inactive" {{ old('status', $organization->status) == 'inactive' ? 'selected' : '' }}>Nonaktif (Inactive)</option>
                </select>
                @error('status') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Deskripsi Singkat</label>
            <textarea name="description" rows="4" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">{{ old('description', $organization->description) }}</textarea>
            @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Logo Organisasi</label>
            
            @if($organization->logo && Storage::disk('public')->exists($organization->logo))
                <div class="mb-4 flex items-center gap-4 p-4 border border-slate-100 rounded-2xl bg-slate-50 max-w-sm">
                    <img src="{{ asset('storage/' . $organization->logo) }}" class="w-16 h-16 object-contain rounded-xl border bg-white p-1" alt="Logo {{ $organization->name }}">
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Logo Saat Ini</p>
                        <p class="text-sm font-semibold text-slate-700">Telah diunggah</p>
                    </div>
                </div>
            @endif

            <input type="file" name="logo" accept="image/*" class="w-full px-5 py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition cursor-pointer">
            <span class="text-xs text-slate-400 mt-2 block">Pilih file logo baru jika ingin mengganti logo yang saat ini digunakan.</span>
            @error('logo') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="pt-6 flex justify-end gap-4 border-t border-slate-100">
            <a href="{{ route('admin.organizations.index') }}" class="px-6 py-4 text-slate-500 font-bold hover:text-slate-800 transition">Batal</a>
            <button type="submit" class="px-8 py-4 bg-indigo-700 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-800 transition">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
