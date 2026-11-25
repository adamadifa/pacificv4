@extends('v2.layouts.app')
@if (request()->is('samutasibank*'))
    @section('titlepage', 'Saldo Awal Mutasi Bank')
@else
    @section('titlepage', 'Saldo Awal Ledger')
@endif

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">
            {{ request()->is('samutasibank*') ? 'Saldo Awal Mutasi Bank' : 'Saldo Awal Ledger' }}
        </span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- SUB-NAVIGATION TABS (Saldo Awal, Ledger / Mutasi Bank) -->
    @if (request()->is('samutasibank*'))
        @include('v2.layouts.navigation_mutasibank')
    @else
        @include('v2.layouts.navigation_ledger')
    @endif

    <!-- PAGE TITLE BAR & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">
                {{ request()->is('samutasibank*') ? 'Saldo Awal Mutasi Bank' : 'Saldo Awal Ledger' }}
            </h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen saldo awal bulanan rekening buku kas dan ledger bank.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @canany(['saledger.create', 'samutasibank.create'])
                <button type="button" id="btnCreateSaldoawal"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Buat Saldo Awal</span>
                </button>
            @endcanany
        </div>
    </div>

    <!-- FILTER SECTION (COMPACT NO-LABEL V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <form action="{{ URL::current() }}" method="GET" id="formFilterSaldoawal" class="w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-center">
                <!-- Pilihan Bank -->
                <div class="relative select2-container-v2 lg:col-span-4">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="landmark" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_bank_search" id="kode_bank_filter" class="select2Kodebanksearch w-full">
                        <option value="">Pilih Bank</option>
                        @foreach ($bank as $d)
                            <option value="{{ $d->kode_bank }}" {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }}>
                                {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bulan -->
                <div class="relative lg:col-span-3">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <select name="bulan" id="bulan_filter"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                        <option value="">Semua Bulan</option>
                        @foreach ($list_bulan as $d)
                            <option value="{{ $d['kode_bulan'] }}" {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>
                                {{ $d['nama_bulan'] }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <!-- Tahun -->
                <div class="relative lg:col-span-3">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar-days" class="w-4 h-4"></i>
                    </div>
                    <select name="tahun" id="tahun_filter"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                        <option value="">Semua Tahun</option>
                        @for ($t = $start_year; $t <= date('Y'); $t++)
                            <option value="{{ $t }}"
                                @if (!empty(Request('tahun')))
                                    {{ Request('tahun') == $t ? 'selected' : '' }}
                                @else
                                    {{ date('Y') == $t ? 'selected' : '' }}
                                @endif>
                                {{ $t }}
                            </option>
                        @endfor
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <!-- Tombol Cari & Reset Filter -->
                <div class="flex items-center gap-1.5 lg:col-span-2">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>

                    @if (request()->hasAny(['kode_bank_search', 'bulan', 'tahun']))
                        <a href="{{ URL::current() }}"
                            class="h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 text-slate-500 text-xs font-bold transition flex items-center justify-center cursor-pointer shrink-0" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="database" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Saldo Awal</h5>
            </div>
            <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                Total: {{ count($saldo_awal) }} Data
            </span>
        </div>

        <!-- TABLE CONTENT (KOLOM TERPISAH MANDIRI, TANPA DATA BERTUMPUK) -->
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <!-- BARIS 2: HEADER KOLOM (bg-[#1e3a78]) -->
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-10">NO</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">KODE</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">BULAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TAHUN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[200px]">BANK</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">JUMLAH</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-16 sticky right-0 z-10 bg-[#1e3a78]">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($saldo_awal as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <!-- NO -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">
                                {{ $loop->iteration }}
                            </td>

                            <!-- KODE -->
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_saldo_awal }}
                            </td>

                            <!-- TANGGAL -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ formatIndo($d->tanggal) }}
                            </td>

                            <!-- BULAN -->
                            <td class="py-1.5 px-3 text-center font-medium text-slate-700 dark:text-slate-300">
                                {{ $nama_bulan[$d->bulan] }}
                            </td>

                            <!-- TAHUN -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">
                                {{ $d->tahun }}
                            </td>

                            <!-- BANK -->
                            <td class="py-1.5 px-3 text-slate-800 dark:text-slate-200">
                                <span class="font-bold">{{ $d->nama_bank }}</span>
                                @if (!empty($d->no_rekening))
                                    <span class="text-slate-400 font-mono-num text-[11px]">({{ $d->no_rekening }})</span>
                                @endif
                            </td>

                            <!-- JUMLAH -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-black text-[#284c9a] dark:text-blue-400">
                                {{ formatAngka($d->jumlah) }}
                            </td>

                            <!-- AKSI STICKY RIGHT -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/40">
                                @if (auth()->user()->can('saledger.delete') || auth()->user()->can('samutasibank.delete'))
                                    <form method="POST" class="inline-flex m-0 p-0 deleteform"
                                        action="{{ request()->is('samutasibank*') ? route('samutasibank.delete', Crypt::encrypt($d->kode_saldo_awal)) : route('saledger.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btnDeleteSaldoawal w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus Saldo Awal">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data saldo awal ledger untuk filter ini</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL CREATE SALDO AWAL (STATIC BACKDROP) ================= -->
<div id="modalSaldoawal" class="fixed inset-0 z-50 hidden">
    <div id="modalSaldoawalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalSaldoawalDialog" class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="database" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalSaldoawalTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        {{ request()->is('samutasibank*') ? 'Buat Saldo Awal Mutasi Bank' : 'Buat Saldo Awal Ledger' }}
                    </h5>
                </div>
                <button type="button" id="btnCloseModalSaldoawal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalSaldoawalContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $select2Bank = $('.select2Kodebanksearch');
        if ($select2Bank.length) {
            $select2Bank.select2({
                placeholder: 'Semua Bank',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        function initModalHelper(modalId, dialogId, backdropId, closeBtnId) {
            const $modal = $(modalId);
            const $dialog = $(dialogId);
            const $backdrop = $(backdropId);
            const $closeBtn = $(closeBtnId);

            function open() {
                $modal.removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function close() {
                $modal.addClass('hidden');
                $('body').removeClass('overflow-hidden');
                $modal.find(`${modalId}Content`).html('');
            }

            $closeBtn.on('click', close);

            $backdrop.on('click', function(e) {
                e.stopPropagation();
                $dialog.addClass('scale-[1.015] shadow-2xl');
                setTimeout(() => $dialog.removeClass('scale-[1.015] shadow-2xl'), 150);
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                    $dialog.addClass('scale-[1.015] shadow-2xl');
                    setTimeout(() => $dialog.removeClass('scale-[1.015] shadow-2xl'), 150);
                }
            });

            return { open, close };
        }

        window.modalSaldoawalHelper = initModalHelper('#modalSaldoawal', '#modalSaldoawalDialog', '#modalSaldoawalBackdrop', '#btnCloseModalSaldoawal');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat formulir...</span>
            </div>
        `;

        // Action: Buat Saldo Awal
        $("#btnCreateSaldoawal").on('click', function(e) {
            e.preventDefault();
            const createUrl = "{{ request()->is('samutasibank*') ? '/samutasibank/create' : '/saledger/create' }}";
            $("#modalSaldoawalContent").html(loadingSpinner);
            modalSaldoawalHelper.open();
            $("#modalSaldoawalContent").load(createUrl, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Delete Saldo Awal Confirm
        $(document).on('click', '.btnDeleteSaldoawal', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Saldo Awal?',
                text: 'Data saldo awal yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
