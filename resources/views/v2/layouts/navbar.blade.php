@php
    $user = Auth::user();
    $level_user = $level_user ?? ($user && $user->roles->first() ? $user->roles->first()->name : '');
    $photo = asset('assets/img/avatars/1.png');
    if ($user && $user->username) {
        $karyawan = DB::table('hrd_karyawan')->where('nik', $user->username)->select('foto')->first();
        if ($karyawan && !empty($karyawan->foto) && function_exists('getfotoKaryawan')) {
            $photo = getfotoKaryawan($karyawan->foto);
        }
    }

    $allowedShortcutRoles = [
        'super admin', 'direktur', 'gm marketing', 'gm operasional', 'gm administrasi',
        'operation manager', 'sales marketing manager', 'regional sales manager',
        'regional operation manager', 'manager keuangan', 'spv presensi'
    ];
    $allowedProgramRoles = [
        'super admin', 'direktur', 'gm marketing', 'gm operasional', 'gm administrasi',
        'operation manager', 'sales marketing manager', 'regional sales manager',
        'regional operation manager', 'manager keuangan'
    ];
@endphp

<header class="sticky top-0 z-30 flex items-center justify-between h-16 px-4 sm:px-6 bg-white/85 dark:bg-slate-900/85 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 transition-colors duration-200">
    
    <!-- Left Area: Hamburger + Breadcrumb/Page Title -->
    <div class="flex items-center gap-3 min-w-0">
        <!-- Mobile Sidebar Toggle -->
        <button id="openMobileSidebarBtn" type="button" class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <!-- Navigasi / Titlepage -->
        <div class="min-w-0 flex items-center gap-2">
            <div class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-100 truncate">
                @hasSection('navigasi')
                    @yield('navigasi')
                @else
                    @yield('titlepage', 'Dashboard')
                @endif
            </div>
        </div>
    </div>

    <!-- Right Area: Notifications + Shortcuts + Theme Toggle + User Menu -->
    <div class="flex items-center gap-1 sm:gap-2">

        <!-- 1. Pelanggan Aktif SFA (Salesman Only) -->
        @if (Cookie::get('kodepelanggan') != null && $level_user == 'salesman')
            <a href="/sfa/pelanggan/{{ Cookie::get('kodepelanggan') }}/show" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brand-50 dark:bg-brand-950/60 border border-brand-200 dark:border-brand-800 text-brand-600 dark:text-brand-400 font-bold text-xs hover:bg-brand-100 transition mr-1">
                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                <span>Pelanggan</span>
            </a>
        @endif

        <!-- 2. Internal Memo Link -->
        @if (Route::has('internalmemo.index'))
            <a href="{{ route('internalmemo.index') }}" title="Internal Memo" class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                @if (!empty($notifIM) && $notifIM > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-rose-500 text-white font-mono-num font-bold text-[9px] flex items-center justify-center border border-white dark:border-slate-900 shadow-2xs">
                        {{ $notifIM }}
                    </span>
                @endif
            </a>
        @endif

        <!-- 3. Ticket Support (GM Administrasi & Super Admin) -->
        @if ($level_user == 'gm administrasi' || $level_user == 'super admin')
            @if (Route::has('ticket.index'))
                <a href="{{ route('ticket.index') }}" title="Ticket" class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                    @if (!empty($notifikasi_ticket) && $notifikasi_ticket > 0)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-rose-500 text-white font-mono-num font-bold text-[9px] flex items-center justify-center border border-white dark:border-slate-900 shadow-2xs">
                            {{ $notifikasi_ticket }}
                        </span>
                    @endif
                </a>
            @endif

            @if (Route::has('ticketupdate.index'))
                <a href="{{ route('ticketupdate.index') }}?status=pending" title="Update Data" class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    @if (!empty($notifikasi_update_data) && $notifikasi_update_data > 0)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-rose-500 text-white font-mono-num font-bold text-[9px] flex items-center justify-center border border-white dark:border-slate-900 shadow-2xs">
                            {{ $notifikasi_update_data }}
                        </span>
                    @endif
                </a>
            @endif
        @endif

        <!-- 4. Program Ikatan & Kumulatif Dropdown -->
        @if (in_array($level_user, $allowedProgramRoles))
            @php $hasNotifProgram = !empty($notifikasi_ajuan_program) && $notifikasi_ajuan_program > 0; @endphp
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" type="button" title="Program Marketing" class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i data-lucide="archive" class="w-4 h-4"></i>
                    @if ($hasNotifProgram)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-rose-500 text-white font-mono-num font-bold text-[9px] flex items-center justify-center border border-white dark:border-slate-900 shadow-2xs">
                            {{ $notifikasi_ajuan_program }}
                        </span>
                    @endif
                </button>

                <div 
                    x-show="open" 
                    x-cloak 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-72 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-2 shadow-xl shadow-slate-200/50 dark:shadow-none space-y-1 z-50"
                >
                    <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900 dark:text-white">Program Marketing</span>
                        @if($hasNotifProgram)
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 font-mono-num">{{ $notifikasi_ajuan_program }} baru</span>
                        @endif
                    </div>

                    <div class="space-y-0.5 max-h-[360px] overflow-y-auto">
                        <!-- Program Ikatan -->
                        @if(Route::has('programikatan2026.index'))
                        <a href="{{ route('programikatan2026.index') }}?status=pending" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="file-signature" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-tight">Ajuan Program Ikatan</p>
                                    <p class="text-[10px] text-slate-400">Persetujuan program</p>
                                </div>
                            </div>
                            @if(!empty($notifikasi_ajuanprogramikatan) && $notifikasi_ajuanprogramikatan > 0)
                                <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_ajuanprogramikatan }}</span>
                            @endif
                        </a>
                        @endif

                        @if(Route::has('pencairanprogramikatan2026.index'))
                        <a href="{{ route('pencairanprogramikatan2026.index') }}" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-tight">Pencairan Program Ikatan</p>
                                    <p class="text-[10px] text-slate-400">Realisasi ikatan</p>
                                </div>
                            </div>
                            @if(!empty($notifikasi_pencairanprogramikatan) && $notifikasi_pencairanprogramikatan > 0)
                                <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_pencairanprogramikatan }}</span>
                            @endif
                        </a>
                        @endif

                        <!-- Program Kumulatif -->
                        @if(Route::has('ajuankumulatif.index'))
                        <a href="{{ route('ajuankumulatif.index') }}?status=pending" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="file-plus" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-tight">Ajuan Prog. Kumulatif</p>
                                    <p class="text-[10px] text-slate-400">Persetujuan kumulatif</p>
                                </div>
                            </div>
                            @if(!empty($notifikasi_ajuanprogramkumulatif) && $notifikasi_ajuanprogramkumulatif > 0)
                                <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_ajuanprogramkumulatif }}</span>
                            @endif
                        </a>
                        @endif

                        @if(Route::has('pencairanprogram.index'))
                        <a href="{{ route('pencairanprogram.index') }}" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-tight">Pencairan Kumulatif</p>
                                    <p class="text-[10px] text-slate-400">Realisasi reward</p>
                                </div>
                            </div>
                            @if(!empty($notifikasi_pencairanprogramkumulatif) && $notifikasi_pencairanprogramkumulatif > 0)
                                <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_pencairanprogramkumulatif }}</span>
                            @endif
                        </a>
                        @endif

                        <!-- Program 6 Bulan -->
                        @if(Route::has('ajuanprogramenambulan.index'))
                        <a href="{{ route('ajuanprogramenambulan.index') }}?status=pending" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="calendar-clock" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-tight">Ajuan Prog. 6 Bulan</p>
                                    <p class="text-[10px] text-slate-400">Persetujuan program</p>
                                </div>
                            </div>
                            @if(!empty($notifikasi_ajuanprogramikatanenambulan) && $notifikasi_ajuanprogramikatanenambulan > 0)
                                <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_ajuanprogramikatanenambulan }}</span>
                            @endif
                        </a>
                        @endif

                        @if(Route::has('pencairanprogramenambulan.index'))
                        <a href="{{ route('pencairanprogramenambulan.index') }}?status=pending" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-tight">Pencairan Prog. 6 Bulan</p>
                                    <p class="text-[10px] text-slate-400">Realisasi 6 bulan</p>
                                </div>
                            </div>
                            @if(!empty($notifikasi_pencairanprogramikatanenambulan) && $notifikasi_pencairanprogramikatanenambulan > 0)
                                <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_pencairanprogramikatanenambulan }}</span>
                            @endif
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- 5. Shortcuts / Notifikasi Pengajuan Operasional Dropdown -->
        @if (in_array($level_user, $allowedShortcutRoles))
            @php $hasNotifTotal = !empty($total_notifikasi) && $total_notifikasi > 0; @endphp
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" type="button" title="Shortcuts & Notifikasi Pengajuan" class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                    @if ($hasNotifTotal)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-rose-500 text-white font-mono-num font-bold text-[9px] flex items-center justify-center border border-white dark:border-slate-900 shadow-2xs">
                            {{ $total_notifikasi }}
                        </span>
                    @endif
                </button>

                <div 
                    x-show="open" 
                    x-cloak 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3 shadow-xl shadow-slate-200/50 dark:shadow-none space-y-2 z-50"
                >
                    <div class="pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white">Shortcuts & Persetujuan</h4>
                            <p class="text-[10px] text-slate-400">Pengajuan menunggu konfirmasi Anda</p>
                        </div>
                        @if($hasNotifTotal)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-mono-num bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                                {{ $total_notifikasi }} Tertunda
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2 max-h-[380px] overflow-y-auto">
                        <!-- Limit Kredit -->
                        @if(Route::has('ajuanlimit.index'))
                        <a href="{{ route('ajuanlimit.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}" class="flex flex-col p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-800 hover:bg-blue-50/30 dark:hover:bg-blue-950/20 transition group">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                </div>
                                @if(!empty($notifikasi_limitkredit) && $notifikasi_limitkredit > 0)
                                    <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_limitkredit }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-600 transition leading-tight">Limit Kredit</span>
                            <span class="text-[10px] text-slate-400 leading-none mt-0.5">Ajuan</span>
                        </a>
                        @endif

                        <!-- Faktur Kredit -->
                        @if(Route::has('ajuanfaktur.index'))
                        <a href="{{ route('ajuanfaktur.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}" class="flex flex-col p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-amber-200 dark:hover:border-amber-800 hover:bg-amber-50/30 dark:hover:bg-amber-950/20 transition group">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                </div>
                                @if(!empty($notifikasi_ajuanfaktur) && $notifikasi_ajuanfaktur > 0)
                                    <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_ajuanfaktur }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-amber-600 transition leading-tight">Faktur Kredit</span>
                            <span class="text-[10px] text-slate-400 leading-none mt-0.5">Ajuan</span>
                        </a>
                        @endif

                        <!-- Penilaian SDM -->
                        @if(Route::has('penilaiankaryawan.index'))
                        <a href="{{ route('penilaiankaryawan.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}" class="flex flex-col p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-sky-200 dark:hover:border-sky-800 hover:bg-sky-50/30 dark:hover:bg-sky-950/20 transition group">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                                    <i data-lucide="users" class="w-3.5 h-3.5"></i>
                                </div>
                                @if(!empty($notifikasi_penilaiankaryawan) && $notifikasi_penilaiankaryawan > 0)
                                    <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_penilaiankaryawan }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-sky-600 transition leading-tight">Penilaian SDM</span>
                            <span class="text-[10px] text-slate-400 leading-none mt-0.5">Karyawan</span>
                        </a>
                        @endif

                        <!-- Target Marketing -->
                        <a href="/targetkomisi?posisi_ajuan={{ $level_user }}&status=0" class="flex flex-col p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-rose-200 dark:hover:border-rose-800 hover:bg-rose-50/30 dark:hover:bg-rose-950/20 transition group">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                                    <i data-lucide="target" class="w-3.5 h-3.5"></i>
                                </div>
                                @if(!empty($notifikasi_target) && $notifikasi_target > 0)
                                    <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_target }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-rose-600 transition leading-tight">Target Sales</span>
                            <span class="text-[10px] text-slate-400 leading-none mt-0.5">Marketing</span>
                        </a>

                        <!-- Izin Kerja -->
                        @if(Route::has('izinabsen.index'))
                        <a href="{{ route('izinabsen.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}" class="flex flex-col p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-emerald-200 dark:hover:border-emerald-800 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 transition group">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                    <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i>
                                </div>
                                @if(!empty($notifikasi_pengajuan_izin) && $notifikasi_pengajuan_izin > 0)
                                    <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_pengajuan_izin }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600 transition leading-tight">Izin Kerja</span>
                            <span class="text-[10px] text-slate-400 leading-none mt-0.5">Pengajuan</span>
                        </a>
                        @endif

                        <!-- Lembur -->
                        @if(Route::has('lembur.index'))
                        <a href="{{ route('lembur.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}" class="flex flex-col p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-purple-200 dark:hover:border-purple-800 hover:bg-purple-50/30 dark:hover:bg-purple-950/20 transition group">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                </div>
                                @if(!empty($notifikasi_lembur) && $notifikasi_lembur > 0)
                                    <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasi_lembur }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-purple-600 transition leading-tight">Lembur</span>
                            <span class="text-[10px] text-slate-400 leading-none mt-0.5">Karyawan</span>
                        </a>
                        @endif

                        <!-- Transfer Dana -->
                        @if(Route::has('ajuantransfer.index'))
                        <a href="{{ route('ajuantransfer.index') }}" class="col-span-2 flex items-center justify-between p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-800 hover:bg-blue-50/30 dark:hover:bg-blue-950/20 transition group">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-600 transition leading-tight">Ajuan Transfer Dana</p>
                                    <p class="text-[10px] text-slate-400 leading-none mt-0.5">Keuangan & transfer</p>
                                </div>
                            </div>
                            @if(!empty($notifikasiajuantransferdana) && $notifikasiajuantransferdana > 0)
                                <span class="px-1.5 py-0.5 text-[10px] font-bold font-mono-num rounded-full bg-rose-500 text-white">{{ $notifikasiajuantransferdana }}</span>
                            @endif
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="h-5 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

        <!-- 6. Theme Toggle Button (Light/Dark) -->
        <button id="navThemeToggleBtn" type="button" title="Ganti Mode (Light / Dark)" class="p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/60 border border-slate-200/80 dark:border-slate-700/60 shadow-2xs transition active:scale-95">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block text-amber-400"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden text-slate-600"></i>
        </button>

        <!-- 7. User Dropdown Menu using Alpine.js -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.outside="open = false" type="button" class="flex items-center gap-2.5 p-1.5 sm:px-3 sm:py-1.5 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition select-none">
                <img src="{{ $photo }}" alt="{{ $user->name ?? 'User' }}" class="w-8 h-8 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-2xs" onerror="this.src='{{ asset('assets/img/avatars/1.png') }}'">
                <div class="hidden sm:block text-left min-w-0">
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate max-w-[120px]">{{ $user->name ?? 'Pengguna' }}</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-semibold truncate">{{ $level_user }}</p>
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Dropdown Panel -->
            <div 
                x-show="open" 
                x-cloak 
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-2 shadow-xl shadow-slate-200/50 dark:shadow-none space-y-1 z-50"
            >
                <!-- Profile Header Details -->
                <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-800 mb-1">
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $user->name ?? '' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ $user->email ?? $user->username ?? '' }}</p>
                </div>

                @if(Route::has('profile.edit') || Route::has('profile'))
                <a href="{{ Route::has('profile.edit') ? route('profile.edit') : route('profile') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                    <span>Profil Saya</span>
                </a>
                @endif

                @if(Route::has('settings.index'))
                <a href="{{ route('settings.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
                    <span>Pengaturan</span>
                </a>
                @endif

                <div class="border-t border-slate-100 dark:border-slate-800 my-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</header>
