@php
    $user = Auth::user();
    $hour = (int) date('H');
    if ($hour >= 4 && $hour < 11) {
        $greeting = 'Selamat Pagi';
    } elseif ($hour >= 11 && $hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat Sore';
    } else {
        $greeting = 'Selamat Malam';
    }

    $photo = asset('assets/img/avatars/1.png');
    if ($user && $user->username) {
        $karyawan = DB::table('hrd_karyawan')->where('nik', $user->username)->select('foto')->first();
        if ($karyawan && !empty($karyawan->foto) && function_exists('getfotoKaryawan')) {
            $photo = getfotoKaryawan($karyawan->foto);
        }
    }
@endphp

<!-- CLEAN WHITE MODERN WELCOME CARD -->
<div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 sm:p-5 shadow-2xs transition-colors duration-200">
    
    <!-- Crisp Geometric Line Ornaments in Background (Subtle Blueprint Pattern) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
        <!-- Concentric Radial Vector Rings -->
        <svg class="absolute -right-8 -bottom-14 w-72 h-72 text-slate-400/[0.08] dark:text-white/[0.04] stroke-current" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="100" cy="100" r="28" stroke-width="1.2" stroke-dasharray="3 3"/>
            <circle cx="100" cy="100" r="52" stroke-width="1.2"/>
            <circle cx="100" cy="100" r="76" stroke-width="1.2" stroke-dasharray="4 4"/>
            <circle cx="100" cy="100" r="100" stroke-width="1.2"/>
            <circle cx="100" cy="100" r="124" stroke-width="1"/>
            <line x1="10" y1="100" x2="190" y2="100" stroke-width="1"/>
            <line x1="100" y1="10" x2="100" y2="190" stroke-width="1"/>
        </svg>

        <!-- Delicate Micro-Dots Grid Pattern -->
        <svg class="absolute top-3 right-48 w-32 h-16 text-slate-400/[0.08] dark:text-white/[0.04]" fill="currentColor" viewBox="0 0 100 50">
            <pattern id="welcome-dot-pattern" x="0" y="0" width="10" height="10" patternUnits="userSpaceOnUse">
                <circle cx="2" cy="2" r="1" fill="currentColor"/>
            </pattern>
            <rect width="100" height="50" fill="url(#welcome-dot-pattern)"/>
        </svg>
    </div>

    <!-- Content (Relative & Above Ornaments) -->
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Left: Avatar + Identity + Subtitle -->
        <div class="flex items-center gap-3.5 min-w-0">
            <div class="relative shrink-0">
                <img src="{{ $photo }}" alt="{{ $user->name ?? 'User' }}" class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl object-cover ring-2 ring-brand-500/20 dark:ring-brand-400/20 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 shadow-sm" onerror="this.src='{{ asset('assets/img/avatars/1.png') }}'">
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white dark:border-slate-900 rounded-full" title="Online"></span>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ $greeting }}, {{ $user->name ?? 'Admin' }}
                    </h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 border border-brand-200/60 dark:border-brand-800/60 uppercase tracking-wider">
                        {{ $level_user ?? 'Staff' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate font-normal">
                    Ringkasan operasional &bull; Pantau penjualan, AUP piutang, dan persediaan barang.
                </p>
            </div>
        </div>

        <!-- Right: Real Date & Cabang Info Badges -->
        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
            <!-- Calendar Date Badge -->
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100/80 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 text-xs font-semibold text-slate-600 dark:text-slate-300 transition-colors duration-150">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 shrink-0"></i>
                <span class="leading-none">{{ Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
            </div>

            <!-- Cabang Badge -->
            @if(!empty(auth()->user()->kode_cabang))
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-50 dark:bg-brand-950/60 hover:bg-brand-100/60 dark:hover:bg-brand-900/60 border border-brand-200/60 dark:border-brand-800/60 text-xs font-bold text-brand-700 dark:text-brand-300 transition-colors duration-150 shadow-2xs">
                <i data-lucide="building-2" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400 shrink-0"></i>
                <span class="leading-none">Cabang {{ auth()->user()->kode_cabang }}</span>
            </div>
            @endif
        </div>

    </div>
</div>
