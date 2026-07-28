<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Organisasi - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-indigo-900 text-white min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-800 rounded-full opacity-35 blur-3xl"></div>
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-700 rounded-full opacity-30 blur-3xl"></div>

    <div class="max-w-2xl w-full bg-white text-slate-900 rounded-[2.5rem] p-8 md:p-10 shadow-2xl relative z-10 animate-fade-in border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-extrabold text-2xl mx-auto mb-4 shadow-lg shadow-indigo-200">
                AH
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800">Registrasi Organisasi</h1>
            <p class="text-xs text-slate-400 font-medium mt-1">Daftarkan organisasi Anda untuk mempublikasikan event seru</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-800 p-4 rounded-2xl mb-6 font-bold text-xs text-center shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('organization.register.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Organisasi</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-5 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-slate-400" 
                           placeholder="Contoh: BEM Amikom" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama PIC (Penanggung Jawab)</label>
                    <input type="text" name="pic_name" value="{{ old('pic_name') }}"
                           class="w-full px-5 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-slate-400" 
                           placeholder="Nama lengkap PIC..." required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Organisasi</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-5 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-slate-400" 
                           placeholder="email@organisasi.com" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">No. HP / WhatsApp PIC</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-5 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-slate-400" 
                           placeholder="Contoh: 08123456789" required>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kata Sandi</label>
                <input type="password" name="password" 
                       class="w-full px-5 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-••••••••" 
                       placeholder="Minimal 6 karakter" required>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi Singkat Organisasi</label>
                <textarea name="description" rows="3"
                          class="w-full px-5 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-slate-400" 
                          placeholder="Jelaskan secara singkat mengenai organisasi Anda...">{{ old('description') }}</textarea>
            </div>

            <button type="submit" 
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-lg shadow-lg shadow-indigo-100 hover:shadow-none transition duration-200 mt-2">
                Daftar Sekarang
            </button>
        </form>

        <div class="relative my-6 flex items-center justify-center">
            <hr class="w-full border-slate-100">
            <span class="absolute bg-white px-4 text-[10px] text-slate-400 font-bold uppercase tracking-wider">Sudah Punya Akun?</span>
        </div>

        <div class="text-center">
            <a href="{{ route('organization.login') }}" class="inline-block text-xs font-bold text-indigo-600 hover:text-indigo-500 hover:underline transition">
                Masuk ke Akun Organisasi
            </a>
        </div>
    </div>
</body>

</html>