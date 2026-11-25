@extends('layouts.app')
@section('titlepage', 'Dashboard')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Dashboard Utama</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- DASHBOARD SUB-NAVIGATION BAR -->
    @include('layouts.navigation_dashboard')

    <!-- STATISTIC CARDS STRIP (SOLID PACIFIC NAVY UNIFIED CARD WITH DIVIDERS) -->
    <div class="rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-shadow duration-200 bg-[#284c9a] text-white relative">
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-white/15">

            <!-- 1. Total Penjualan Bulan Ini -->
            <a href="{{ Route::has('penjualan.index') ? route('penjualan.index') : '#' }}" class="group relative overflow-hidden block p-4 sm:p-5 flex flex-col justify-between min-h-[120px] hover:bg-white/[0.05] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="shopping-bag" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-white/15 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        </div>
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-white/50 group-hover:text-white transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xl sm:text-2xl font-black text-white font-mono-num tracking-tight leading-none">
                            Rp {{ formatAngka($penjualan->total ?? 0) }}
                        </div>
                        <p class="text-xs font-bold text-white/90 mt-1.5 leading-tight">Penjualan Bulan Ini</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Periode {{ date('F Y') }} (Cabang {{ auth()->user()->kode_cabang }})</p>
                    </div>
                </div>
            </a>

            <!-- 2. Total Pembayaran Bulan Ini -->
            <a href="{{ Route::has('pembayaranpenjualan.index') ? route('pembayaranpenjualan.index') : '#' }}" class="group relative overflow-hidden block p-4 sm:p-5 flex flex-col justify-between min-h-[120px] hover:bg-white/[0.05] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="wallet" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center border border-emerald-400/30 shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                        </div>
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-white/50 group-hover:text-white transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xl sm:text-2xl font-black text-emerald-300 font-mono-num tracking-tight leading-none">
                            Rp {{ formatAngka($pembayaran->total ?? 0) }}
                        </div>
                        <p class="text-xs font-bold text-white/90 mt-1.5 leading-tight">Pembayaran Bulan Ini</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Total penerimaan tagihan berjalan</p>
                    </div>
                </div>
            </a>

            <!-- 3. Jumlah Transaksi -->
            <a href="{{ Route::has('penjualan.index') ? route('penjualan.index') : '#' }}" class="group relative overflow-hidden block p-4 sm:p-5 flex flex-col justify-between min-h-[120px] hover:bg-white/[0.05] transition-colors duration-200">
                <svg class="absolute right-0 bottom-0 w-24 h-24 text-white/[0.06] pointer-events-none transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none">
                    <circle cx="100" cy="100" r="45" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <circle cx="100" cy="100" r="75" stroke="currentColor" stroke-width="1"/>
                </svg>
                <div class="absolute -right-1.5 -bottom-1.5 text-white/[0.08] group-hover:text-white/[0.14] group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300 pointer-events-none">
                    <i data-lucide="receipt" class="w-16 h-16 stroke-[1.2]"></i>
                </div>

                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center border border-amber-400/30 shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            <i data-lucide="receipt" class="w-4 h-4"></i>
                        </div>
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-white/50 group-hover:text-white transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xl sm:text-2xl font-black text-amber-300 font-mono-num tracking-tight leading-none">
                            {{ formatAngka($jmltransaksi ?? 0) }} <span class="text-xs font-bold text-white/70">Faktur</span>
                        </div>
                        <p class="text-xs font-bold text-white/90 mt-1.5 leading-tight">Jumlah Transaksi</p>
                        <p class="text-[11px] text-white/55 leading-none mt-0.5">Faktur penjualan aktif bulan ini</p>
                    </div>
                </div>
            </a>

        </div>
    </div>

    @hasanyrole(['operation manager', 'admin penjualan'])
    <!-- FILTER CARDS (REKAP PENJUALAN & REKAP DPPP) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- CARD 1: REKAP PENJUALAN -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900 shrink-0">
                        <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Rekap Penjualan</h3>
                        <p class="text-[10px] text-slate-400 leading-none">Filter transaksi penjualan & kas besar</p>
                    </div>
                </div>

                <form action="#" id="formRekappenjualan" class="space-y-2.5">
                    @hasanyrole($roles_show_cabang)
                    <div class="relative select2-icon-wrapper">
                        <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang_rekappenjualan" id="kode_cabang_rekappenjualan" class="select2Kodecabangrekappenjualan w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endhasanyrole

                    <div class="grid grid-cols-2 gap-2 items-start">
                        <div>
                            <div class="relative">
                                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="bulan" id="bulan_penjualan" class="w-full h-[34px] pl-8 pr-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>{{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="tahun" id="tahun_penjualan" class="w-full h-[34px] pl-8 pr-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btnRekappenjualan" class="w-full h-[34px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:scale-[0.99] text-white font-bold text-xs shadow-2xs flex items-center justify-center gap-1.5 transition cursor-pointer">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        <span>Tampilkan Rekap Penjualan</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- CARD 2: REKAP DPPP -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-900 shrink-0">
                        <i data-lucide="bar-chart-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Rekap DPPP</h3>
                        <p class="text-[10px] text-slate-400 leading-none">Pencapaian target omset & selling out produk</p>
                    </div>
                </div>

                <form action="#" id="formDppp" class="space-y-2.5">
                    @hasanyrole($roles_show_cabang)
                    <div class="relative select2-icon-wrapper">
                        <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang_rekapdppp" id="kode_cabang_rekapdppp" class="select2Kodecabangrekapdppp w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endhasanyrole

                    <div class="grid grid-cols-2 gap-2 items-start">
                        <div>
                            <div class="relative">
                                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="bulan" id="bulan_dppp" class="w-full h-[34px] pl-8 pr-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>{{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="tahun" id="tahun_dppp" class="w-full h-[34px] pl-8 pr-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btnRekapdppp" class="w-full h-[34px] rounded-lg bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-bold text-xs shadow-2xs flex items-center justify-center gap-1.5 transition cursor-pointer">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        <span>Tampilkan Rekap DPPP</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
    @endhasanyrole

    @hasanyrole(['operation manager', 'admin persediaan cabang'])
    <!-- REKAP PERSEDIAAN CABANG -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs">
        <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-slate-100 dark:border-slate-800 flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900 shrink-0">
                    <i data-lucide="boxes" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Rekap Persediaan Barang</h3>
                    <p class="text-[10px] text-slate-400 leading-none">Monitoring stok good & bad stok gudang cabang {{ auth()->user()->kode_cabang }}</p>
                </div>
            </div>

            <button type="button" onclick="loadrekappersediaan()" class="inline-flex items-center gap-1.5 h-[30px] px-3 rounded-lg bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 dark:hover:bg-blue-900/60 border border-blue-200 dark:border-blue-800 text-xs font-bold text-[#284c9a] dark:text-blue-400 transition-colors cursor-pointer" title="Refresh Stok">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                <span>Segarkan</span>
            </button>
        </div>

        <div id="loadrekappersediaan" class="min-h-[160px] overflow-x-auto">
            <!-- AJAX injected here -->
        </div>
    </div>
    @endhasanyrole

</div>

<x-modal-form id="modal" show="loadmodal" title="" size="" />
@endsection

@push('myscript')
<script>
    $(function() {
        const formRekappenjualan = $('#formRekappenjualan');
        const formDppp = $('#formDppp');

        // Select2 initialization (global body mount per V2 standard)
        if ($('.select2Kodecabangrekappenjualan').length) {
            $('.select2Kodecabangrekappenjualan').select2({
                placeholder: 'Semua Cabang',
                allowClear: true
            });
        }

        if ($('.select2Kodecabangrekapdppp').length) {
            $('.select2Kodecabangrekapdppp').select2({
                placeholder: 'Semua Cabang',
                allowClear: true
            });
        }

        // Inline validation helpers (V2 Standard - Zero Swal Alert)
        function clearFieldError($el) {
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.field-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $el.closest('.relative').parent().find('.field-error-msg').remove();
        }

        function setFieldError($el, msg) {
            clearFieldError($el);
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.field-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $el.closest('.relative').after(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        formRekappenjualan.find('select').on('input change', function() {
            clearFieldError($(this));
        });

        formDppp.find('select').on('input change', function() {
            clearFieldError($(this));
        });

        function loading() {
            $("#loadmodal").html(`<div class="flex flex-col items-center justify-center p-10 text-center">
                <div class="inline-block w-6 h-6 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-2.5 text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat data...</p>
            </div>`);
        }

        // Form Rekap Penjualan
        formRekappenjualan.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formRekappenjualan.find('#kode_cabang_rekappenjualan').val();
            const $bulan = formRekappenjualan.find('#bulan_penjualan');
            const $tahun = formRekappenjualan.find('#tahun_penjualan');
            const bulan = $bulan.val();
            const tahun = $tahun.val();

            let firstError = null;
            if (!bulan) {
                setFieldError($bulan, 'Bulan wajib dipilih!');
                firstError = firstError || $bulan;
            }
            if (!tahun) {
                setFieldError($tahun, 'Tahun wajib dipilih!');
                firstError = firstError || $tahun;
            }
            if (firstError) {
                firstError.focus();
                return;
            }

            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Rekap Penjualan');
            $("#modal").find(".modal-dialog").removeClass('modal-xl modal-xxxl').addClass('modal-xxl');
            loading();
            $.ajax({
                type: "POST",
                url: "{{ route('dashboard.rekappenjualan') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun,
                    kode_cabang: kode_cabang
                },
                success: function(response) {
                    $("#loadmodal").html(response);
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Form Rekap DPPP
        formDppp.submit(function(e) {
            e.preventDefault();
            const kode_cabang = formDppp.find('#kode_cabang_rekapdppp').val();
            const $bulan = formDppp.find('#bulan_dppp');
            const $tahun = formDppp.find('#tahun_dppp');
            const bulan = $bulan.val();
            const tahun = $tahun.val();

            let firstError = null;
            if (!bulan) {
                setFieldError($bulan, 'Bulan wajib dipilih!');
                firstError = firstError || $bulan;
            }
            if (!tahun) {
                setFieldError($tahun, 'Tahun wajib dipilih!');
                firstError = firstError || $tahun;
            }
            if (firstError) {
                firstError.focus();
                return;
            }

            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Rekap DPPP');
            $("#modal").find(".modal-dialog").removeClass('modal-xl modal-xxl').addClass('modal-xxxl');
            loading();
            $.ajax({
                type: "POST",
                url: "{{ route('dashboard.rekapdppp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun,
                    kode_cabang: kode_cabang
                },
                success: function(response) {
                    $("#loadmodal").html(response);
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Load Rekap Persediaan Cabang
        window.loadrekappersediaan = function() {
            if (!$("#loadrekappersediaan").length) return;
            $("#loadrekappersediaan").html(`<div class="flex flex-col items-center justify-center p-8 text-center">
                <div class="inline-block w-6 h-6 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat stok persediaan...</p>
            </div>`);
            $("#loadrekappersediaan").load('/dashboard/rekappersediaancabang', function() {
                if (window.lucide) {
                    lucide.createIcons();
                }
            });
        };

        loadrekappersediaan();
    });
</script>
@endpush
