@extends('layouts.guest')

@section('content')
    <!-- Status Alert -->
    @if (session('status'))
        <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Validation Errors Alert -->
    @if ($errors->any())
        <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-medium space-y-1">
            <div class="flex items-center gap-2 font-bold">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-500"></i>
                <span>Gagal Masuk:</span>
            </div>
            <ul class="list-disc list-inside pl-1 text-[11px] space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Username / Email Field -->
        <div class="space-y-1.5">
            <label for="id_user" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                Username / Email
            </label>
            <div class="relative">
                <i data-lucide="user" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input 
                    id="id_user" 
                    type="text" 
                    name="id_user" 
                    value="{{ old('id_user') }}" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder="Masukkan username atau email" 
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100/80 dark:hover:bg-slate-900 focus:bg-white dark:focus:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition duration-150"
                >
            </div>
            @error('id_user')
                <p class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>

            <div class="relative">
                <i data-lucide="lock" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="••••••••" 
                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100/80 dark:hover:bg-slate-900 focus:bg-white dark:focus:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-brand-500 rounded-xl text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition duration-150"
                >
                <button 
                    type="button" 
                    id="togglePasswordBtn" 
                    tabindex="-1"
                    title="Tampilkan / Sembunyikan Password" 
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none"
                >
                    <i data-lucide="eye" id="eyeIcon" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password')
                <p class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-600 dark:text-slate-400 select-none">
                <input 
                    id="remember" 
                    type="checkbox" 
                    name="remember" 
                    class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 cursor-pointer"
                >
                <span>Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            id="submitBtn" 
            class="w-full py-3 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 active:scale-[0.99] text-white font-extrabold text-sm shadow-md shadow-brand-600/30 flex items-center justify-center gap-2 transition duration-150 cursor-pointer"
        >
            <i data-lucide="log-in" class="w-4 h-4"></i>
            <span>Masuk ke Akun</span>
        </button>
    </form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Password toggle logic
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                // Swap icon
                if (isPassword) {
                    eyeIcon.setAttribute('data-lucide', 'eye-off');
                } else {
                    eyeIcon.setAttribute('data-lucide', 'eye');
                }
                if (window.lucide) {
                    lucide.createIcons();
                }
            });
        }
    });
</script>
@endsection
