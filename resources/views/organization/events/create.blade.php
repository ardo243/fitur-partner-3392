@extends('layouts.organization')

@section('title', 'Tambah Event Baru - Organisasi')
@section('page_title', 'Tambah Event Baru')
@section('page_subtitle', 'Masukkan detail acara baru yang akan diselenggarakan oleh organisasi Anda.')

@section('content')
<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl overflow-hidden animate-fade-in">
    <div class="p-8 md:p-10 border-b border-slate-100 bg-slate-50/50">
        <h3 class="text-xl font-extrabold text-slate-800">Formulir Pembuatan Event</h3>
        <p class="text-xs text-slate-400 font-medium mt-1">Pastikan semua data yang dimasukkan sudah benar dan lengkap.</p>
    </div>

    <form action="{{ route('organization.events.store') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10 space-y-6">
        @csrf
        
        <div>
            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Judul Event</label>
            <input type="text" name="title" value="{{ old('title') }}" 
                   class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-slate-400" 
                   placeholder="Masukkan nama atau judul acara..." required>
            @error('title') <span class="text-rose-600 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Kategori</label>
            <select name="category_id" 
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700" required>
                <option value="" disabled selected>Pilih Kategori Event</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-rose-600 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Deskripsi Acara</label>
            <textarea name="description" rows="5" 
                      class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-slate-400" 
                      placeholder="Jelaskan detail acara Anda secara mendetail di sini...">{{ old('description') }}</textarea>
            @error('description') <span class="text-rose-600 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Tanggal & Waktu</label>
                <input type="datetime-local" name="date" value="{{ old('date') }}" 
                       class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700" required>
                @error('date') <span class="text-rose-600 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Lokasi / Tempat</label>
                <input type="text" name="location" value="{{ old('location') }}" 
                       class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-slate-400" 
                       placeholder="Contoh: Gedung Amikom, Zoom Meeting..." required>
                @error('location') <span class="text-rose-600 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Harga Tiket (Rp)</label>
                <input type="number" name="price" value="{{ old('price', 0) }}" 
                       class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700" 
                       required min="0" placeholder="Isi 0 jika gratis">
                @error('price') <span class="text-rose-600 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Kapasitas (Stok Tiket)</label>
                <input type="number" name="stock" value="{{ old('stock', 1) }}" 
                       class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700" 
                       required min="1">
                @error('stock') <span class="text-rose-600 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Poster Event (Opsional)</label>
            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-indigo-400 transition cursor-pointer relative group bg-slate-50">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-indigo-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-slate-600">
                        <label for="poster-upload" class="relative cursor-pointer rounded-md font-bold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            <span>Unggah file poster</span>
                            <input id="poster-upload" name="poster" type="file" accept="image/*" class="sr-only">
                        </label>
                        <p class="pl-1">atau seret dan letakkan</p>
                    </div>
                    <p class="text-xs text-slate-400">PNG, JPG, JPEG hingga 2MB</p>
                </div>
            </div>
            @error('poster') <span class="text-rose-600 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
        </div>

        <div class="pt-6 flex justify-end gap-4 border-t border-slate-100">
            <a href="{{ route('organization.events.index') }}" 
               class="px-6 py-4 text-slate-500 font-bold hover:text-slate-800 rounded-xl transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:shadow-none transition duration-200">
                Simpan Event
            </button>
        </div>
    </form>
</div>

<script>
    // Preview file name when selected
    const fileInput = document.getElementById('poster-upload');
    const dropText = fileInput?.closest('div')?.querySelector('p');
    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            if (fileInput.files.length > 0) {
                const fileName = fileInput.files[0].name;
                if (dropText) dropText.textContent = `File terpilih: ${fileName}`;
            }
        });
    }
</script>
@endsection