<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #2c2e80;
        }
        .tab-indicator {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-between p-6 text-slate-300">
    <!-- Empty top spacer to center card vertically but push footer down -->
    <div></div>

    <!-- Main Login Card -->
    <div class="w-full max-w-[420px] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col border border-slate-100">
        <!-- Tabs Header -->
        <div class="relative flex border-b border-slate-100 text-sm font-semibold">
            <button id="tab-user" onclick="switchTab('user')" class="flex-1 py-4 text-center text-indigo-600 transition-colors duration-200">
                User
            </button>
            <button id="tab-org" onclick="switchTab('org')" class="flex-1 py-4 text-center text-slate-400 hover:text-slate-600 transition-colors duration-200">
                Organization / Admin
            </button>
            <!-- Sliding Underline Indicator -->
            <div id="tab-indicator" class="absolute bottom-0 left-0 w-1/2 h-[2px] bg-indigo-600 tab-indicator"></div>
        </div>

        <!-- Form Body -->
        <div class="p-8 md:p-10 flex flex-col text-slate-900">
            <!-- Header Text -->
            <div class="text-center mb-8">
                <h2 id="login-title" class="text-2xl font-extrabold text-slate-900">User Login</h2>
                <p id="login-subtitle" class="text-sm text-slate-400 mt-1.5 font-medium">Welcome back! Please enter your details.</p>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-3.5 rounded-2xl mb-5 font-bold text-xs text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-100 text-rose-800 p-3.5 rounded-2xl mb-5 font-bold text-xs text-center">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-800 p-3.5 rounded-2xl mb-5 font-bold text-xs text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Unified Form -->
            <form id="login-form" action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email / Username Input -->
                <div>
                    <label id="label-email" class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Email or Username</label>
                    <input type="email" name="email" id="input-email"
                           class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-800 placeholder-slate-400 text-sm" 
                           placeholder="Enter your email or username" required>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" id="input-password"
                               class="w-full pl-4 pr-11 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium text-slate-800 placeholder-slate-400 text-sm" 
                               placeholder="••••••••" required>
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition">
                            <!-- Eye icon -->
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:shadow-none transition duration-200 mt-2 active:scale-[0.98]">
                    Login
                </button>
            </form>

            <!-- Social/Register Area (User tab only by default) -->
            <div id="social-divider" class="relative my-6 flex items-center justify-center">
                <hr class="w-full border-slate-100">
                <span class="absolute bg-white px-4 text-[10px] text-slate-400 font-bold uppercase tracking-wider">or</span>
            </div>

            <div id="google-login-btn">
                <a href="{{ route('auth.google') }}" class="w-full py-3.5 border border-slate-200 hover:bg-slate-50 rounded-2xl flex items-center justify-center gap-2.5 font-bold text-slate-700 text-xs transition duration-200 active:scale-[0.98]">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    Masuk dengan Google
                </a>
            </div>

            <!-- Organization Registration Link (hidden in User tab by default) -->
            <div id="org-register-link" class="hidden text-center mt-6">
                <p class="text-xs text-slate-400 font-semibold">
                    Belum memiliki akun organisasi? 
                    <a href="{{ route('organization.register') }}" class="text-indigo-600 hover:text-indigo-500 hover:underline ml-1">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Footer Copyright -->
    <div class="text-center text-[10px] text-slate-500 font-semibold my-6">
        <p>&copy; 2026 AmikomEventHub. All rights reserved.</p>
        <p class="mt-1"><a href="{{ route('admin.login') }}" class="hover:text-indigo-400 transition">Admin Login</a></p>
    </div>

    <!-- Switcher Javascript -->
    <script>
        function switchTab(type) {
            const tabUser = document.getElementById('tab-user');
            const tabOrg = document.getElementById('tab-org');
            const tabIndicator = document.getElementById('tab-indicator');
            const loginTitle = document.getElementById('login-title');
            const loginSubtitle = document.getElementById('login-subtitle');
            const labelEmail = document.getElementById('label-email');
            const inputEmail = document.getElementById('input-email');
            const loginForm = document.getElementById('login-form');
            const socialDivider = document.getElementById('social-divider');
            const googleLoginBtn = document.getElementById('google-login-btn');
            const orgRegisterLink = document.getElementById('org-register-link');

            if (type === 'user') {
                // Style Active Tab
                tabUser.classList.remove('text-slate-400', 'hover:text-slate-600');
                tabUser.classList.add('text-indigo-600');
                tabOrg.classList.remove('text-indigo-600');
                tabOrg.classList.add('text-slate-400', 'hover:text-slate-600');
                tabIndicator.style.transform = 'translateX(0)';

                // Update Form Content
                loginTitle.textContent = 'User Login';
                loginSubtitle.textContent = 'Welcome back! Please enter your details.';
                labelEmail.textContent = 'Email or Username';
                inputEmail.placeholder = 'Enter your email or username';
                loginForm.action = "{{ route('login.post') }}";

                // Show/Hide social elements
                socialDivider.classList.remove('hidden');
                googleLoginBtn.classList.remove('hidden');
                orgRegisterLink.classList.add('hidden');
            } else {
                // Style Active Tab
                tabOrg.classList.remove('text-slate-400', 'hover:text-slate-600');
                tabOrg.classList.add('text-indigo-600');
                tabUser.classList.remove('text-indigo-600');
                tabUser.classList.add('text-slate-400', 'hover:text-slate-600');
                tabIndicator.style.transform = 'translateX(100%)';

                // Update Form Content
                loginTitle.textContent = 'Organization & Admin Login';
                loginSubtitle.textContent = 'Welcome back! Please enter your details.';
                labelEmail.textContent = 'Email or Username';
                inputEmail.placeholder = 'Enter your email or username';
                loginForm.action = "{{ route('organization.login.store') }}";

                // Show/Hide social elements
                socialDivider.classList.add('hidden');
                googleLoginBtn.classList.add('hidden');
                orgRegisterLink.classList.remove('hidden');
            }
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('input-password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Change eye icon to "slashed eye"
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                // Change eye icon back to normal
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</body>

</html>
