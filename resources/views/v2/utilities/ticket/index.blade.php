@extends('layouts.app')
@section('titlepage', 'Tiket Ajuan System')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Utilities</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Tiket Ajuan System</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    <!-- SUB-NAVIGATION -->
    @include('layouts.navigation_ticket')

    <!-- HEADER BAR & ACTION BUTTONS -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3.5 sm:p-4 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900 shrink-0">
                <i data-lucide="ticket" class="w-4 h-4"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Pusat Layanan & Pengajuan Tiket IT</h2>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Helpdesk & Service Desk IT — Kelola pengajuan perbaikan, permintaan fitur, dan kendala sistem.</p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @hasrole('super admin')
                <a href="{{ route('ticket.cetaklaporan', request()->all()) }}" target="_blank" class="inline-flex items-center gap-1.5 h-[34px] px-3 rounded-lg bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 transition">
                    <i data-lucide="printer" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>Cetak SLA</span>
                </a>
                <a href="{{ route('ticketconfig.index') }}" class="inline-flex items-center gap-1.5 h-[34px] px-3 rounded-lg bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 border border-blue-200 dark:border-blue-800 text-xs font-bold text-[#284c9a] dark:text-blue-400 transition">
                    <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                    <span>Config Approval</span>
                </a>
            @endhasrole
            <button type="button" id="btnCreate" class="inline-flex items-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-2xs transition cursor-pointer">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat Tiket Ajuan</span>
            </button>
        </div>
    </div>

    <!-- STATISTIC CARDS STRIP (SOLID PACIFIC NAVY UNIFIED CARD) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>
        <div class="grid grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-white/15">
            <!-- 1. Total Pengajuan -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[104px] hover:bg-white/[0.05] transition-colors">
                <svg class="absolute right-0 bottom-0 w-20 h-20 text-white/[0.06] pointer-events-none" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] pointer-events-none">
                    <i data-lucide="tickets" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-white/75 uppercase tracking-wider">Total Pengajuan</p>
                    <div class="text-2xl font-black text-white font-mono-num mt-1.5">{{ number_format($stats['total'] ?? 0) }}</div>
                </div>
            </div>

            <!-- 2. Menunggu Persetujuan -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[104px] hover:bg-white/[0.05] transition-colors">
                <svg class="absolute right-0 bottom-0 w-20 h-20 text-white/[0.06] pointer-events-none" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] pointer-events-none">
                    <i data-lucide="hourglass" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-white/75 uppercase tracking-wider">Menunggu Persetujuan</p>
                    <div class="text-2xl font-black text-amber-300 font-mono-num mt-1.5">{{ number_format($stats['pending'] ?? 0) }}</div>
                </div>
            </div>

            <!-- 3. Sedang Diproses IT -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[104px] hover:bg-white/[0.05] transition-colors">
                <svg class="absolute right-0 bottom-0 w-20 h-20 text-white/[0.06] pointer-events-none" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] pointer-events-none">
                    <i data-lucide="cpu" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-white/75 uppercase tracking-wider">Sedang Diproses IT</p>
                    <div class="text-2xl font-black text-sky-300 font-mono-num mt-1.5">{{ number_format($stats['proses_it'] ?? 0) }}</div>
                </div>
            </div>

            <!-- 4. Selesai / Ditutup -->
            <div class="group relative overflow-hidden p-4 flex flex-col justify-between min-h-[104px] hover:bg-white/[0.05] transition-colors">
                <svg class="absolute right-0 bottom-0 w-20 h-20 text-white/[0.06] pointer-events-none" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1 -bottom-1 text-white/[0.08] pointer-events-none">
                    <i data-lucide="check-circle-2" class="w-14 h-14 stroke-[1.2]"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-white/75 uppercase tracking-wider">Selesai / Ditutup</p>
                    <div class="text-2xl font-black text-emerald-300 font-mono-num mt-1.5">{{ number_format($stats['selesai'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- COMPACT NO-LABEL FILTER BAR -->
    <form action="{{ route('ticket.index') }}" method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            @hasanyrole($roles_show_cabang)
            <div class="lg:col-span-3 relative select2-icon-wrapper">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_cabang_search" id="kode_cabang_search" class="select2Filter w-full" data-placeholder="Semua Cabang">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                            {{ strtoupper($c->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endhasanyrole

            <div class="lg:col-span-3 relative select2-icon-wrapper">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                    <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                </div>
                <select name="id_kategori_search" id="id_kategori_search" class="select2Filter w-full" data-placeholder="Semua Kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ Request('id_kategori_search') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                </div>
                <select name="status_search" class="w-full h-[36px] pl-8 pr-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ Request('status_search') == 'pending' ? 'selected' : '' }}>Belum Selesai</option>
                    <option value="selesai" {{ Request('status_search') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ Request('status_search') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="@hasanyrole($roles_show_cabang) lg:col-span-4 @else lg:col-span-7 @endhasanyrole flex items-center gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="keyword" value="{{ Request('keyword') }}" class="w-full h-[36px] pl-8 pr-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Cari No. Tiket / Judul / Keterangan...">
                </div>
                <button type="submit" class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center gap-1.5 transition shrink-0 cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>
        </div>
    </form>

    <!-- SECTION HEADER -->
    @php $totalTicket = method_exists($ticket, 'total') ? $ticket->total() : $ticket->count(); @endphp
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="list-checks" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">Daftar Pengajuan Tiket</h3>
        </div>
        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-600 dark:text-slate-300 font-mono-num">
            {{ $totalTicket }} Data Ditemukan
        </span>
    </div>

    <!-- TICKET LIST -->
    <div class="space-y-3">
        @forelse ($ticket as $d)
            @php
                $currentUserId = auth()->user()->id;
                $userRoles = auth()->user()->roles->pluck('name')->toArray();
                $isApprover = false;
                if ($d->posisi_approval == 'ADMIN' && auth()->user()->hasRole(['super admin', 'admin maintenance'])) {
                    $isApprover = true;
                } elseif (auth()->user()->hasRole('super admin')) {
                    $isApprover = true;
                } elseif (in_array($d->posisi_approval, $userRoles)) {
                    $isApprover = true;
                } elseif ($d->posisi_approval == 'MANAGER_DEPT' && $d->id_manager_dept == $currentUserId) {
                    $isApprover = true;
                } elseif ($d->posisi_approval == 'SMM' && $d->id_smm == $currentUserId) {
                    $isApprover = true;
                } elseif ($d->posisi_approval == 'RSM' && $d->id_rsm == $currentUserId) {
                    $isApprover = true;
                } elseif ($d->posisi_approval == 'GM' && $d->id_gm == $currentUserId) {
                    $isApprover = true;
                }
                $priorityBadge = match($d->priority) {
                    'Urgent' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-900',
                    'Tinggi' => 'bg-orange-50 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 border-orange-200 dark:border-orange-900',
                    'Sedang' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-900',
                    default  => 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700',
                };
            @endphp

            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xs hover:shadow-sm hover:border-slate-300 dark:hover:border-slate-700 transition-all overflow-hidden">
                <!-- TOP META STRIP -->
                <div class="px-4 py-2 bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-mono-num font-bold text-[11px] border border-blue-200/80 dark:border-blue-900">
                            {{ $d->kode_pengajuan }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            {{ date('d/m/Y', strtotime($d->tanggal)) }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 font-semibold text-[11px] border border-emerald-200/80 dark:border-emerald-900">
                            {{ $d->category->nama_kategori ?? 'Umum' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md border text-[10px] font-bold uppercase {{ $priorityBadge }}">
                            @if ($d->priority == 'Urgent')
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                            @endif
                            {{ $d->priority ?? 'Rendah' }}
                        </span>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-200/70 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold text-[11px]">
                        {{ $d->kode_cabang }}
                    </span>
                </div>

                <!-- BODY -->
                <div class="p-4 grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                    <!-- Left: Content -->
                    <div class="lg:col-span-5 space-y-1.5">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-snug">{{ $d->judul ?? 'Pengajuan Tiket' }}</h4>
                        @if ($d->no_bukti)
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[11px] font-mono-num text-slate-600 dark:text-slate-300">
                                <i data-lucide="file-text" class="w-3 h-3"></i>
                                <span>No. Bukti: {{ $d->no_bukti }}</span>
                            </div>
                        @endif
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ Str::limit($d->keterangan, 160) }}</p>

                        @if ($d->lampiran || $d->link)
                            <div class="flex items-center gap-2 flex-wrap pt-1">
                                @if ($d->lampiran)
                                    <a href="{{ asset($d->lampiran) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white border border-rose-200 dark:border-rose-900 text-[11px] font-semibold transition">
                                        <i data-lucide="paperclip" class="w-3 h-3"></i>
                                        <span>Dokumen Lampiran</span>
                                    </a>
                                @endif
                                @if ($d->link)
                                    <a href="{{ $d->link }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white border border-blue-200 dark:border-blue-900 text-[11px] font-semibold transition">
                                        <i data-lucide="link" class="w-3 h-3"></i>
                                        <span>Link Referensi</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Middle: User & Status -->
                    <div class="lg:col-span-4 lg:border-l lg:border-slate-100 dark:lg:border-slate-800 lg:pl-4 space-y-2.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300 shrink-0">
                                {{ strtoupper(substr($d->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">{{ formatName2($d->user->name ?? '-') }}</div>
                                <div class="text-[10px] font-bold uppercase text-slate-400">{{ $d->user->kode_dept ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-1.5">
                            @if ($d->status == '1')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900 text-[11px] font-bold">
                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                    <span>Selesai</span>
                                </span>
                            @elseif ($d->status == '2')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900 text-[11px] font-bold">
                                    <i data-lucide="x-circle" class="w-3 h-3"></i>
                                    <span>Ditolak</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900 text-[11px] font-bold">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span>Menunggu</span>
                                </span>
                            @endif

                            @if ($d->status == '1')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-100/80 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 text-[10px] font-bold">
                                    Selesai (IT Admin)
                                </span>
                            @elseif ($d->status == '2')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-100/80 dark:bg-rose-900/40 text-rose-800 dark:text-rose-300 text-[10px] font-bold">
                                    Ditolak
                                </span>
                            @elseif ($d->posisi_approval == 'ADMIN')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#284c9a] text-white text-[10px] font-bold">
                                    <i data-lucide="cpu" class="w-3 h-3"></i>
                                    <span>Diproses IT Admin</span>
                                </span>
                            @else
                                @php
                                    $posisiLabel = match($d->posisi_approval) {
                                        'MANAGER_DEPT' => 'Menunggu Manager Dept',
                                        'SMM' => 'Menunggu SMM',
                                        'RSM' => 'Menunggu RSM',
                                        'GM' => 'Menunggu GM',
                                        default => 'Menunggu ' . strtoupper($d->posisi_approval ?? '-'),
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 border border-blue-200/80 dark:border-blue-900 text-[10px] font-bold">
                                    {{ $posisiLabel }}
                                </span>
                            @endif
                        </div>
                        <div class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            <span>{{ $d->lama_penyelesaian }}</span>
                        </div>
                    </div>

                    <!-- Right: Actions -->
                    <div class="lg:col-span-3 lg:border-l lg:border-slate-100 dark:lg:border-slate-800 lg:pl-4 flex flex-wrap lg:flex-col gap-1.5 justify-end">
                        <button type="button" class="btnMessage h-[32px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 flex items-center justify-center gap-1.5 transition cursor-pointer" kode_pengajuan="{{ $d->kode_pengajuan }}">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-slate-500"></i>
                            <span>Diskusi</span>
                            @if (($d->messages_count ?? 0) > 0)
                                <span class="px-1.5 py-0.2 rounded-full bg-rose-600 text-white text-[10px] font-bold">{{ $d->messages_count }}</span>
                            @endif
                        </button>

                        @if ($d->status == '0')
                            @if ($isApprover)
                                <button type="button" class="btnApprove h-[32px] px-3 rounded-lg border border-emerald-200 dark:border-emerald-900 bg-emerald-50 dark:bg-emerald-950/50 hover:bg-emerald-600 hover:text-white text-emerald-700 dark:text-emerald-400 text-xs font-bold flex items-center justify-center gap-1.5 transition cursor-pointer" kode_pengajuan="{{ $d->kode_pengajuan }}">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    <span>Approve</span>
                                </button>
                            @endif
                            @if ($d->id_user == $currentUserId || auth()->user()->hasRole('super admin'))
                                <div class="flex items-center gap-1.5 w-full">
                                    <button type="button" class="btnEdit flex-1 h-[32px] px-2.5 rounded-lg border border-amber-200 dark:border-amber-900 bg-amber-50 dark:bg-amber-950/50 hover:bg-amber-500 hover:text-white text-amber-700 dark:text-amber-400 text-xs font-bold flex items-center justify-center gap-1 transition cursor-pointer" kode_pengajuan="{{ $d->kode_pengajuan }}">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        <span>Edit</span>
                                    </button>
                                    <form method="POST" action="{{ route('ticket.delete', Crypt::encrypt($d->kode_pengajuan)) }}" class="deleteform inline-flex m-0 p-0 flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-confirm w-full h-[32px] px-2.5 rounded-lg border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-600 hover:text-white text-rose-600 dark:text-rose-400 text-xs font-bold flex items-center justify-center gap-1 transition cursor-pointer">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <button type="button" class="btnApprove h-[32px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 text-slate-700 dark:text-slate-200 text-xs font-bold flex items-center justify-center gap-1.5 transition cursor-pointer" kode_pengajuan="{{ $d->kode_pengajuan }}">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Detail</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl bg-white dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-800 p-10 text-center">
                <i data-lucide="ticket-x" class="w-10 h-10 text-slate-300 dark:text-slate-700 mx-auto mb-2"></i>
                <h4 class="text-sm font-bold text-slate-600 dark:text-slate-300">Tidak Ada Tiket Ditemukan</h4>
                <p class="text-xs text-slate-400 mt-1">Silakan sesuaikan filter pencarian atau buat tiket baru jika ada kendala.</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if ($ticket->hasPages())
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 px-4 py-3 shadow-2xs">
            {{ $ticket->links('v2.components.pagination') }}
        </div>
    @endif
</div>

<x-modal-form id="modalTicket" size="modal-lg" show="loadmodalform" title="Tiket Ajuan" />
<x-modal-form id="modalTicketMessage" size="modal-lg" show="loadmodalmessage" title="Diskusi Tiket Ajuan" />
@endsection

@push('myscript')
<script>
    $(function() {
        $('.select2Filter').each(function() {
            $(this).select2({
                placeholder: $(this).data('placeholder') || 'Pilih...',
                allowClear: true
            });
        });

        function openTicketModal(url, title) {
            $("#modalTicket").modal("show");
            $("#modalTicket").find(".modal-title").text(title);
            $("#modalTicket").find(".loadmodalform").load(url, function() {
                if (window.lucide) lucide.createIcons();
            });
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            openTicketModal("{{ route('ticket.create') }}", "Buat Tiket Ajuan Baru");
        });

        $(document).on("click", ".btnEdit", function(e) {
            e.preventDefault();
            let kp = $(this).attr("kode_pengajuan");
            openTicketModal("/ticket/" + kp + "/edit", "Edit Tiket Ajuan");
        });

        $(document).on("click", ".btnApprove", function(e) {
            e.preventDefault();
            let kp = $(this).attr("kode_pengajuan");
            openTicketModal("/ticket/" + kp + "/approve", "Detail & Persetujuan Tiket");
        });

        $(document).on("click", ".btnMessage", function(e) {
            e.preventDefault();
            let kp = $(this).attr("kode_pengajuan");
            $("#modalTicketMessage").modal("show");
            $("#modalTicketMessage").find(".modal-title").text("Diskusi Tiket: " + kp);
            $("#modalTicketMessage").find(".loadmodalform, .loadmodalmessage, #loadmodalmessage").load("/ticket/" + kp + "/message", function() {
                if (window.lucide) lucide.createIcons();
            });
        });
    });
</script>
@endpush
