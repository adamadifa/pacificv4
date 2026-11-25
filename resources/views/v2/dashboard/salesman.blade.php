@extends('layouts.app')
@section('titlepage', 'Dashboard Salesman')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Dashboard Salesman</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- ACTIVE CHECK-IN CUSTOMER BANNER (MOBILE-FIRST QUICK ACCESS) -->
    @if (!empty($pelanggan_checkin))
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($pelanggan_checkin->kode_pelanggan)) }}"
            class="group flex items-center justify-between gap-3 p-3 sm:p-3.5 rounded-xl border border-emerald-200 dark:border-emerald-800/80 bg-emerald-50/80 dark:bg-emerald-950/30 hover:bg-emerald-100/70 dark:hover:bg-emerald-950/50 shadow-2xs transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <i data-lucide="map-pin-check" class="w-4 h-4"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-emerald-600/15 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Sedang Check-in
                        </span>
                        <span class="text-[11px] font-bold font-mono-num text-slate-500 dark:text-slate-400">
                            {{ $pelanggan_checkin->kode_pelanggan }}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm font-black text-slate-800 dark:text-slate-100 truncate mt-0.5 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors">
                        {{ strtoupper($pelanggan_checkin->nama_pelanggan) }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-1 text-xs font-bold text-emerald-700 dark:text-emerald-300 shrink-0">
                <span class="hidden sm:inline">Buka Outlet</span>
                <i data-lucide="chevron-right" class="w-4 h-4 transition-transform group-hover:translate-x-0.5"></i>
            </div>
        </a>
    @else
        <a href="{{ route('sfa.pelanggan') }}"
            class="group flex items-center justify-between gap-3 p-3 sm:p-3.5 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-[#284c9a]/50 hover:bg-slate-50/70 dark:hover:bg-slate-800/50 shadow-2xs transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 flex items-center justify-center shrink-0 transition-colors">
                    <i data-lucide="map-pin-off" class="w-4 h-4"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-200">Belum Ada Pelanggan Check-in</p>
                    <p class="text-[11px] text-slate-400 truncate">Klik untuk memilih pelanggan dan mulai kunjungan.</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-lg bg-[#284c9a] text-white text-[11px] font-bold shrink-0">
                Pilih Outlet
            </span>
        </a>
    @endif

    <!-- STATISTIC CARDS STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-white/15">

            <!-- 1. Penjualan Hari Ini -->
            <div class="group relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[124px] hover:bg-white/[0.05] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="shopping-bag" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-white/10 text-white/80 text-[10px] font-bold uppercase tracking-wider">Hari Ini</span>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            Rp {{ formatRupiah($penjualan->total ?? 0) }}
                        </div>
                        <p class="text-xs font-bold text-white/75 mt-1.5 leading-tight">Penjualan Hari Ini</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Total omzet penjualan aktif</p>
                    </div>
                </div>
            </div>

            <!-- 2. Pembayaran Hari Ini -->
            <div class="group relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[124px] hover:bg-white/[0.05] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="wallet" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-white/10 text-white/80 text-[10px] font-bold uppercase tracking-wider">Hari Ini</span>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            Rp {{ formatRupiah($pembayaran->total ?? 0) }}
                        </div>
                        <p class="text-xs font-bold text-white/75 mt-1.5 leading-tight">Pembayaran</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Total penerimaan tagihan</p>
                    </div>
                </div>
            </div>

            <!-- 3. Jumlah Transaksi -->
            <div class="group relative overflow-hidden p-4 sm:p-5 flex flex-col justify-between min-h-[124px] hover:bg-white/[0.05] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="receipt" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs">
                            <i data-lucide="receipt" class="w-4 h-4"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-white/10 text-white/80 text-[10px] font-bold uppercase tracking-wider">Faktur</span>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            {{ formatRupiah($jmltransaksi ?? 0) }}
                        </div>
                        <p class="text-xs font-bold text-white/75 mt-1.5 leading-tight">Jumlah Transaksi</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Faktur penjualan hari ini</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MAIN WORKSPACE CARD (TARGET, HISTORI KUNJUNGAN, DPB) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">

        <!-- CARD HEADER PACIFIC NAVY + SEGMENTED TABS -->
        <div class="bg-[#284c9a] text-white px-3.5 py-2 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-3.5 h-3.5 text-blue-200 shrink-0"></i>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider">Monitoring Salesman</h4>
            </div>

            <div class="inline-flex items-center gap-1 bg-[#1e3a78] p-1 rounded-lg">
                <button type="button" data-target="#pane-target"
                    class="salesman-tab-btn active h-[28px] px-3 rounded-md text-xs font-bold bg-white text-[#284c9a] shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="target" class="w-3.5 h-3.5"></i>
                    <span>Target</span>
                </button>
                <button type="button" data-target="#pane-histori"
                    class="salesman-tab-btn h-[28px] px-3 rounded-md text-xs font-bold text-white/80 hover:text-white hover:bg-white/10 transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    <span>Histori</span>
                </button>
                <button type="button" data-target="#pane-dpb"
                    class="salesman-tab-btn h-[28px] px-3 rounded-md text-xs font-bold text-white/80 hover:text-white hover:bg-white/10 transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="package-check" class="w-3.5 h-3.5"></i>
                    <span>DPB</span>
                </button>
            </div>
        </div>

        <!-- TAB CONTENT AREA -->
        <div class="p-4 sm:p-5">

            <!-- PANE 1: TARGET -->
            <div id="pane-target" class="salesman-tab-pane space-y-4">
                <form action="#" id="formTarget" class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-w-xl">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <select name="bulan" id="bulan_target"
                            class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Semua Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>
                                    {{ $d['nama_bulan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-4 h-4"></i>
                        </div>
                        <select name="tahun" id="tahun_target"
                            class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                </form>

                <div id="loadtarget" class="min-h-[120px]"></div>
            </div>

            <!-- PANE 2: HISTORI KUNJUNGAN -->
            <div id="pane-histori" class="salesman-tab-pane hidden space-y-4">
                <form action="#" id="formHistori" class="max-w-xs">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="tanggal" id="tanggal_histori" readonly
                            value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal"
                            class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                    </div>
                </form>

                <div id="loadhistori" class="min-h-[120px]"></div>
            </div>

            <!-- PANE 3: DPB -->
            <div id="pane-dpb" class="salesman-tab-pane hidden space-y-4">
                <form action="#" id="formDpb" class="max-w-xs">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="tanggal" id="tanggal_dpb" readonly
                            value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal"
                            class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                    </div>
                </form>

                <div id="loaddpb" class="min-h-[120px]"></div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        const loadingHtml = `
            <div class="flex items-center justify-center gap-2 py-8 text-xs font-semibold text-slate-400">
                <svg class="animate-spin h-4 w-4 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat data...</span>
            </div>
        `;

        // Tab switching
        $('.salesman-tab-btn').on('click', function() {
            const $btn = $(this);
            const target = $btn.data('target');

            $('.salesman-tab-btn')
                .removeClass('active bg-white text-[#284c9a] shadow-xs')
                .addClass('text-white/80 hover:text-white hover:bg-white/10');

            $btn
                .addClass('active bg-white text-[#284c9a] shadow-xs')
                .removeClass('text-white/80 hover:text-white hover:bg-white/10');

            $('.salesman-tab-pane').addClass('hidden');
            $(target).removeClass('hidden');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        const $formTarget = $('#formTarget');
        const $formHistori = $('#formHistori');
        const $formDpb = $('#formDpb');

        function loadtarget() {
            const bulan = $formTarget.find('#bulan_target').val();
            const tahun = $formTarget.find('#tahun_target').val();
            $('#loadtarget').html(loadingHtml);
            $.ajax({
                type: 'POST',
                url: "{{ route('targetkomisi.gettargetsalesmandashboard') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $('#loadtarget').html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        function loadhistori() {
            const tanggal = $formHistori.find('#tanggal_histori').val();
            $('#loadhistori').html(loadingHtml);
            $.ajax({
                type: 'POST',
                url: "{{ route('penjualan.getcheckinsalesman') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    $('#loadhistori').html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        function loaddpb() {
            const tanggal = $formDpb.find('#tanggal_dpb').val();
            $('#loaddpb').html(loadingHtml);
            $.ajax({
                type: 'POST',
                url: "{{ route('penjualan.getdpbsalesman') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    $('#loaddpb').html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        loadtarget();
        loadhistori();
        loaddpb();

        $formTarget.find('#bulan_target, #tahun_target').on('change', loadtarget);
        $formHistori.find('#tanggal_histori').on('change', loadhistori);
        $formDpb.find('#tanggal_dpb').on('change', loaddpb);
    });
</script>
@endpush
