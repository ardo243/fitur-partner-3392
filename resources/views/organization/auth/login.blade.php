<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Organisasi - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-indigo-900 text-white min-h-screen py-12 px-4 relative">
    <!-- Background elements -->
    <div class="fixed -left-20 -bottom-20 w-80 h-80 bg-indigo-800 rounded-full opacity-35 blur-3xl pointer-events-none"></div>
    <div class="fixed -right-20 -top-20 w-80 h-80 bg-indigo-700 rounded-full opacity-30 blur-3xl pointer-events-none"></div>

    <div class="max-w-md mx-auto bg-white text-slate-900 rounded-[2.5rem] p-8 md:p-10 shadow-2xl relative z-10 animate-fade-in border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-extrabold text-2xl mx-auto mb-4 shadow-lg shadow-indigo-200">
                AH
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800">Login Organisasi</h1>
            <p class="text-xs text-slate-400 font-medium mt-1">Masuk ke panel penyelenggara AmikomEventHub</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 rounded-2xl mb-6 font-bold text-xs text-center shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-100 text-rose-800 p-4 rounded-2xl mb-6 font-bold text-xs text-center shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-800 p-4 rounded-2xl mb-6 font-bold text-xs text-center shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('organization.login.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Resmi</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-slate-400" 
                       placeholder="nama@organisasi.org" required>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kata Sandi</label>
                <input type="password" name="password" 
                       class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-700 placeholder-••••••••" 
                       placeholder="••••••••" required>
            </div>
            <button type="submit" 
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-lg shadow-lg shadow-indigo-100 hover:shadow-none transition duration-200 mt-2">
                Masuk Sekarang
            </button>
        </form>

        <div class="relative my-6 flex items-center justify-center">
            <hr class="w-full border-slate-100">
            <span class="absolute bg-white px-4 text-[10px] text-slate-400 font-bold uppercase tracking-wider">Belum Punya Akun?</span>
        </div>

        <div class="text-center">
            <a href="{{ route('organization.register') }}" class="inline-block text-xs font-bold text-indigo-600 hover:text-indigo-500 hover:underline transition">
                Daftarkan Organisasi Anda
            </a>
        </div>
    </div>
</body>

</html>