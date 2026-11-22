<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Login' }} | Portal Pacific</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/img/favicon/favicon.ico') }}" />

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Compiled Tailwind CSS & JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Theme Initializer (Default to Light unless saved) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="min-h-full font-sans bg-[#f1f4f9] dark:bg-[#0b1120] text-slate-800 dark:text-slate-100 flex flex-col items-center justify-center p-4 sm:p-6 antialiased selection:bg-brand-500 selection:text-white transition-colors duration-200 relative overflow-x-hidden">

    <!-- Subtle Background Glow Effects -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-brand-500/10 dark:bg-brand-500/15 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/15 rounded-full blur-3xl"></div>
    </div>

    <!-- Top Controls (Theme Toggle) -->
    <div class="w-full max-w-md flex justify-end mb-3 relative z-10">
        <button id="themeToggleBtn" type="button" title="Ganti Mode (Light / Dark)" class="p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xs hover:border-brand-300 dark:hover:border-brand-700 transition active:scale-95">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block text-amber-400"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden text-slate-600"></i>
        </button>
    </div>

    <!-- Main Card Container (Ala poslaravel with Royal Blue styling) -->
    <div class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-3xl p-7 sm:p-9 shadow-xl shadow-slate-200/60 dark:shadow-none space-y-6 relative z-10 transition-colors duration-200">
        
        <!-- Brand Header -->
        <div class="text-center space-y-2.5">
            <div class="inline-flex items-center justify-center p-3 rounded-2xl bg-brand-50 dark:bg-brand-950/40 border border-brand-100 dark:border-brand-900 shadow-sm mb-1">
                <img src="{{ asset('logo.png') }}" alt="Logo Pacific" class="w-10 h-10 object-contain" onerror="this.onerror=null; this.src='{{ asset('assets/img/logo/logoportal.png') }}';">
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Portal Pacific</h1>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">Sistem Informasi Manajemen Terpadu</p>
            </div>
        </div>

        {{ $slot ?? '' }}
        @yield('content')
    </div>

    <!-- Footer Copyright -->
    <div class="mt-8 text-center text-xs text-slate-400 dark:text-slate-500 relative z-10">
        &copy; {{ date('Y') }} Pacific. Hak Cipta Dilindungi.
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                lucide.createIcons();
            }

            const toggleBtn = document.getElementById('themeToggleBtn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
