@extends('v2.layouts.app')
@section('titlepage', 'Laporan Accounting')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Accounting</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan Accounting</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan Accounting</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pusat laporan data accounting, persediaan, cost ratio, jurnal umum, dan laporan keuangan.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER WITH VERTICAL TABS -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">

        <!-- 2-COLUMN VERTICAL TAB LAYOUT -->
        <div class="flex flex-col md:flex-row min-h-[460px]">

            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-72 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3 sm:p-3.5 flex flex-col gap-1.5 overflow-y-auto max-h-[660px]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Kategori Laporan</span>

                @can('akt.rekappersediaan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekappersediaan"
                        data-title="Laporan Rekap Persediaan"
                        data-desc="Rekapitulasi persediaan barang jadi dan bahan per bulan dan tahun.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="package" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Rekap Persediaan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekap persediaan bulanan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('akt.rekapbj')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapbj"
                        data-title="Laporan Rekap BJ"
                        data-desc="Rekapitulasi barang jadi (BJ) per bulan dan tahun.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Rekap BJ</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekap barang jadi bulanan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('akt.costratio')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-costratio"
                        data-title="Laporan Cost Ratio"
                        data-desc="Laporan rasio biaya operasional per cabang, kategori, dan format.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="pie-chart" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Cost Ratio</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rasio biaya per bulan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('akt.rekapcostratio')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapcostratio"
                        data-title="Laporan Rekap Cost Ratio Multi Tahun"
                        data-desc="Perbandingan rekapitulasi cost ratio lintas tahun per cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Rekap Cost Ratio</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Perbandingan multi tahun</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('akt.jurnalumum')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-jurnalumum"
                        data-title="Laporan Jurnal Umum"
                        data-desc="Laporan daftar transaksi jurnal umum berdasarkan rentang tanggal.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="book" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Jurnal Umum</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Mutasi jurnal umum</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('lk.bukubesar')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-bukubesar"
                        data-title="Laporan Keuangan (Buku Besar)"
                        data-desc="Buku besar, neraca, laba rugi, laporan perbandingan, dan analisa rasio keuangan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Laporan Keuangan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Buku besar, neraca & laba rugi</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('akt.aruskas')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-aruskas"
                        data-title="Laporan Arus Kas (Sumber & Penggunaan Kas)"
                        data-desc="Laporan arus kas masuk dan keluar per bulan dan tahun.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="banknote" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Laporan Arus Kas</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Sumber & penggunaan kas</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan
            </div>

            <!-- RIGHT CONTENT FORM PANEL -->
            <div class="flex-1 p-5 sm:p-6 bg-white dark:bg-slate-900">
                <!-- CLEAN HEADER STRIP (NO BLUE DOT, NO OUTPUT BADGE) -->
                <div class="pb-3.5 mb-4 border-b border-slate-200/80 dark:border-slate-800">
                    <h4 id="activeReportTitle" class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider"></h4>
                    <p id="activeReportDesc" class="text-[11px] text-slate-400 mt-0.5"></p>
                </div>

                <!-- TAB PANES -->
                @can('akt.rekappersediaan')
                    <div id="pane-rekappersediaan" class="laporan-panel hidden">
                        @include('v2.accounting.laporan.rekappersediaan')
                    </div>
                @endcan

                @can('akt.rekapbj')
                    <div id="pane-rekapbj" class="laporan-panel hidden">
                        @include('v2.accounting.laporan.rekapbj')
                    </div>
                @endcan

                @can('akt.costratio')
                    <div id="pane-costratio" class="laporan-panel hidden">
                        @include('v2.accounting.laporan.costratio')
                    </div>
                @endcan

                @can('akt.rekapcostratio')
                    <div id="pane-rekapcostratio" class="laporan-panel hidden">
                        @include('v2.accounting.laporan.rekapcostratio')
                    </div>
                @endcan

                @can('akt.jurnalumum')
                    <div id="pane-jurnalumum" class="laporan-panel hidden">
                        @include('v2.accounting.laporan.jurnalumum')
                    </div>
                @endcan

                @can('lk.bukubesar')
                    <div id="pane-bukubesar" class="laporan-panel hidden">
                        @include('v2.accounting.laporan.lk.bukubesar')
                    </div>
                @endcan

                @can('akt.aruskas')
                    <div id="pane-aruskas" class="laporan-panel hidden">
                        @include('v2.accounting.laporan.aruskas')
                    </div>
                @endcan
            </div>

        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    const LaporanAccountingValidator = {
        showError: function($field, message) {
            $field.addClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${message}</span>
                </p>
            `);
        },
        clearError: function($field) {
            $field.removeClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500');
            $group.find('.field-error-msg').remove();
        },
        bindLiveCleanup: function($form) {
            const self = this;
            $form.on('input change', 'input, select', function() {
                self.clearError($(this));
            });
            $form.find('select').on('select2:select select2:clear', function() {
                self.clearError($(this));
            });
        },
        triggerPrintSpinner: function($form) {
            const $btnSubmit = $form.find('button[name="submitButton"], button[type="submit"]:not([name="exportButton"])').first();
            const $btnExport = $form.find('button[name="exportButton"]');
            const origSubmit = $btnSubmit.html();
            const origExport = $btnExport.html();

            $btnSubmit.prop('disabled', true).addClass('opacity-75 pointer-events-none');
            $btnExport.prop('disabled', true).addClass('opacity-75 pointer-events-none');

            $btnSubmit.html(`
                <svg class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mencetak...</span>
            `);

            setTimeout(function() {
                $btnSubmit.prop('disabled', false).removeClass('opacity-75 pointer-events-none').html(origSubmit);
                $btnExport.prop('disabled', false).removeClass('opacity-75 pointer-events-none').html(origExport);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }, 2500);
        }
    };

    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        // Select2 V2 global mount (tanpa dropdownParent modal)
        const setupSelect2 = (selector, placeholder) => {
            const $el = $(selector);
            if ($el.length) {
                $el.select2({
                    placeholder: placeholder,
                    allowClear: true,
                    dropdownCssClass: 'select2-dropdown-v2'
                });
            }
        };

        setupSelect2('.select2Kodecabang', 'Semua Cabang');
        setupSelect2('.select2Kodecabangrekapcostratio', 'Semua Cabang');
        setupSelect2('.select2Tahunrekapcostratio', 'Pilih Tahun');
        setupSelect2('.select2Kodeakundari', 'Semua Akun');
        setupSelect2('.select2Kodeakunsampai', 'Semua Akun');

        // Tab Switching
        function activateTab($tab) {
            const target = $tab.data('target');
            const title = $tab.data('title');
            const desc = $tab.data('desc');

            $('.laporan-tab-btn')
                .removeClass('active bg-[#284c9a] text-white shadow-xs')
                .addClass('bg-white dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
            $('.laporan-tab-btn .tab-icon')
                .removeClass('bg-white/20 text-white')
                .addClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400');
            $('.laporan-tab-btn .tab-desc')
                .removeClass('text-white/75')
                .addClass('text-slate-400 dark:text-slate-500');
            $('.laporan-tab-btn .tab-chevron')
                .removeClass('text-white/80 opacity-100')
                .addClass('text-slate-300 dark:text-slate-600 opacity-0');

            $tab
                .addClass('active bg-[#284c9a] text-white shadow-xs')
                .removeClass('bg-white dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
            $tab.find('.tab-icon')
                .addClass('bg-white/20 text-white')
                .removeClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400');
            $tab.find('.tab-desc')
                .addClass('text-white/75')
                .removeClass('text-slate-400 dark:text-slate-500');
            $tab.find('.tab-chevron')
                .addClass('text-white/80 opacity-100')
                .removeClass('text-slate-300 dark:text-slate-600 opacity-0');

            $('#activeReportTitle').text(title);
            $('#activeReportDesc').text(desc);

            $('.laporan-panel').addClass('hidden');
            $(target).removeClass('hidden');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        const $firstTab = $('.laporan-tab-btn').first();
        if ($firstTab.length) {
            activateTab($firstTab);
        }

        $('.laporan-tab-btn').on('click', function() {
            activateTab($(this));
        });

        // Helper Validasi Bulan & Tahun standar
        function bindBulanTahunForm(formId, bulanId, tahunId) {
            const $form = $(`#${formId}`);
            if (!$form.length) return;
            LaporanAccountingValidator.bindLiveCleanup($form);

            $form.on('submit', function(e) {
                const $bulan = $form.find(`#${bulanId}`);
                const $tahun = $form.find(`#${tahunId}`);
                let firstInvalid = null;

                if (!$bulan.val()) {
                    LaporanAccountingValidator.showError($bulan, 'Bulan wajib dipilih.');
                    firstInvalid = firstInvalid || $bulan;
                }
                if (!$tahun.val()) {
                    LaporanAccountingValidator.showError($tahun, 'Tahun wajib dipilih.');
                    firstInvalid = firstInvalid || $tahun;
                }

                if (firstInvalid) {
                    e.preventDefault();
                    firstInvalid.focus();
                    return false;
                }

                LaporanAccountingValidator.triggerPrintSpinner($form);
            });
        }

        bindBulanTahunForm('frmRekappersediaan', 'bulan_rekappersediaan', 'tahun_rekappersediaan');
        bindBulanTahunForm('frmRekapbj', 'bulan_rekapbj', 'tahun_rekapbj');
        bindBulanTahunForm('formCostratio', 'bulan_costratio', 'tahun_costratio');
        bindBulanTahunForm('formAruskas', 'bulan_aruskas', 'tahun_aruskas');

        // Rekap Cost Ratio Multi Tahun
        const $formRekapCostRatio = $('#formRekapcostratio');
        if ($formRekapCostRatio.length) {
            LaporanAccountingValidator.bindLiveCleanup($formRekapCostRatio);
            $formRekapCostRatio.on('submit', function(e) {
                const $tahun = $formRekapCostRatio.find('#tahun_rekapcostratio');
                const val = $tahun.val();
                if (!val || val.length === 0) {
                    e.preventDefault();
                    LaporanAccountingValidator.showError($tahun, 'Tahun wajib dipilih minimal satu.');
                    $tahun.focus();
                    return false;
                }
                LaporanAccountingValidator.triggerPrintSpinner($formRekapCostRatio);
            });
        }

        // Jurnal Umum
        const $formJu = $('#formJurnalumum');
        if ($formJu.length) {
            LaporanAccountingValidator.bindLiveCleanup($formJu);
            $formJu.on('submit', function(e) {
                const $dari = $formJu.find('#dari_jurnalumum');
                const $sampai = $formJu.find('#sampai_jurnalumum');
                let firstInvalid = null;

                if (!$dari.val()) {
                    LaporanAccountingValidator.showError($dari, 'Dari tanggal wajib diisi.');
                    firstInvalid = firstInvalid || $dari;
                }
                if (!$sampai.val()) {
                    LaporanAccountingValidator.showError($sampai, 'Sampai tanggal wajib diisi.');
                    firstInvalid = firstInvalid || $sampai;
                } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                    LaporanAccountingValidator.showError($sampai, 'Sampai tanggal tidak boleh lebih kecil dari Dari tanggal.');
                    firstInvalid = firstInvalid || $sampai;
                }

                if (firstInvalid) {
                    e.preventDefault();
                    firstInvalid.focus();
                    return false;
                }

                LaporanAccountingValidator.triggerPrintSpinner($formJu);
            });
        }

        // Buku Besar / Laporan Keuangan
        const $formLedger = $('#formLedger');
        if ($formLedger.length) {
            LaporanAccountingValidator.bindLiveCleanup($formLedger);

            function showCoa() {
                const formatlaporan = $formLedger.find('#formatlaporan_ledger').val();
                if (formatlaporan == '1') {
                    $('#jenislaporan_container').show();
                    $('#coa').show();
                    $('#formatcetak_container').hide();
                    $('#row_periode').show();
                    $('#row_tahun').hide();
                    $('#row_sampai_bulan').hide();
                } else if (formatlaporan == '2' || formatlaporan == '3') {
                    $('#jenislaporan_container').hide();
                    $('#coa').hide();
                    $('#formatcetak_container').show();
                    $formLedger.find('#kode_akun_dari_ledger').val('').trigger('change');
                    $formLedger.find('#kode_akun_sampai_ledger').val('').trigger('change');
                    $('#row_periode').show();
                    $('#row_tahun').hide();
                    $('#row_sampai_bulan').hide();
                } else if (formatlaporan == '4' || formatlaporan == '5' || formatlaporan == '6') {
                    $('#jenislaporan_container').hide();
                    $('#coa').hide();
                    $('#formatcetak_container').hide();
                    $('#row_periode').hide();
                    $('#row_tahun').show();
                    if (formatlaporan == '6') {
                        $('#row_sampai_bulan').show();
                    } else {
                        $('#row_sampai_bulan').hide();
                    }
                    $formLedger.find('#kode_akun_dari_ledger').val('').trigger('change');
                    $formLedger.find('#kode_akun_sampai_ledger').val('').trigger('change');
                } else {
                    $('#jenislaporan_container').hide();
                    $('#coa').hide();
                    $('#formatcetak_container').hide();
                    $('#row_periode').show();
                    $('#row_tahun').hide();
                    $('#row_sampai_bulan').hide();
                }
            }

            showCoa();
            $formLedger.find('#formatlaporan_ledger').on('change', showCoa);

            let isConfirmedSubmit = false;
            let clickedButton = null;

            $formLedger.on('click', 'button[type="submit"]', function() {
                clickedButton = $(this).attr('name');
            });

            $formLedger.on('submit', function(e) {
                const $formatlaporan = $formLedger.find('#formatlaporan_ledger');
                const formatlaporan = $formatlaporan.val();
                const jenis_laporan = $formLedger.find('#jenis_laporan_ledger').val();
                const kode_akun_dari = $formLedger.find('#kode_akun_dari_ledger').val();
                const kode_akun_sampai = $formLedger.find('#kode_akun_sampai_ledger').val();
                const $dari = $formLedger.find('#dari_ledger');
                const $sampai = $formLedger.find('#sampai_ledger');
                const $tahun = $formLedger.find('#tahun_ledger');

                let firstInvalid = null;

                if (!formatlaporan) {
                    LaporanAccountingValidator.showError($formatlaporan, 'Format laporan wajib dipilih.');
                    firstInvalid = firstInvalid || $formatlaporan;
                }

                if (formatlaporan == '4' || formatlaporan == '5' || formatlaporan == '6') {
                    if (!$tahun.val()) {
                        LaporanAccountingValidator.showError($tahun, 'Tahun wajib dipilih.');
                        firstInvalid = firstInvalid || $tahun;
                    }
                } else if (formatlaporan) {
                    if (!$dari.val()) {
                        LaporanAccountingValidator.showError($dari, 'Periode dari wajib diisi.');
                        firstInvalid = firstInvalid || $dari;
                    }
                    if (!$sampai.val()) {
                        LaporanAccountingValidator.showError($sampai, 'Periode sampai wajib diisi.');
                        firstInvalid = firstInvalid || $sampai;
                    } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                        LaporanAccountingValidator.showError($sampai, 'Periode sampai tidak boleh lebih kecil dari periode dari.');
                        firstInvalid = firstInvalid || $sampai;
                    }
                }

                if (firstInvalid) {
                    e.preventDefault();
                    firstInvalid.focus();
                    return false;
                }

                if (clickedButton !== 'exportButton' && formatlaporan === '1' && (jenis_laporan === '1' || !jenis_laporan) && !kode_akun_dari && !kode_akun_sampai && !isConfirmedSubmit) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Perhatian: Data Sangat Besar',
                        html: 'Anda memilih <b>Semua Akun</b> dengan format <b>Detail</b>.<br>Jumlah transaksi sangat banyak. Disarankan menggunakan format <b>Rekap</b> atau <b>Export Excel</b>.<br><br>Jika tetap ingin melanjutkan, data akan ditampilkan <b>secara bertahap per akun</b> saat di-scroll agar tidak out of memory.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#284c9a',
                        cancelButtonColor: '#e11d48',
                        confirmButtonText: 'Ya, Tetap Lanjutkan (Bertahap)',
                        cancelButtonText: 'Batal / Ubah Filter'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            isConfirmedSubmit = true;
                            if (!$formLedger.find('input[name="submitButton"]').length) {
                                $formLedger.append('<input type="hidden" name="submitButton" value="1">');
                            }
                            $formLedger.submit();
                            isConfirmedSubmit = false;
                        }
                    });
                    return false;
                }

                LaporanAccountingValidator.triggerPrintSpinner($formLedger);
            });
        }
    });
</script>
@endpush
