@extends('layouts.app')
@section('titlepage', 'Laporan Gudang Jadi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Jadi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan Gudang Jadi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Filter dan cetak dokumen resmi persediaan, rekap hasil produksi, pengeluaran, realisasi, dan angkutan.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER WITH VERTICAL TABS -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <!-- 2-COLUMN VERTICAL TAB LAYOUT -->
        <div class="flex flex-col md:flex-row min-h-[460px]">
            
            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-72 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3.5 sm:p-4 flex flex-col gap-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Pilihan Laporan</span>

                <!-- 1. Persediaan -->
                @can('gj.persediaan')
                    <button type="button" class="laporan-tab-btn active group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-[#284c9a] text-white shadow-xs"
                        data-target="#pane-persediaan"
                        data-title="Laporan Persediaan Produk Jadi"
                        data-desc="Laporan kartu persediaan dan pergerakan stok barang produk jadi.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="box" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight">Persediaan</span>
                            <span class="tab-desc block text-[10.5px] text-white/75 truncate mt-0.5">Kartu stok produk jadi</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-white/80 shrink-0 ml-auto"></i>
                    </button>
                @endcan

                <!-- 2. Rekap Persediaan -->
                @can('gj.rekappersediaan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekappersediaan"
                        data-title="Laporan Rekap Persediaan"
                        data-desc="Rekapitulasi mutasi saldo awal, penerimaan, pengeluaran, dan saldo akhir produk.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Persediaan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekapitulasi saldo & mutasi</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 3. Hasil Produksi -->
                @can('gj.rekaphasilproduksi')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekaphasilproduksi"
                        data-title="Laporan Rekap Hasil Produksi"
                        data-desc="Laporan rekapitulasi penerimaan barang jadi dari departemen produksi.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="factory" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Hasil Produksi</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Penerimaan dari produksi</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 4. Rekap Pengeluaran -->
                @can('gj.rekappengeluaran')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekappengeluaran"
                        data-title="Laporan Rekap Pengeluaran"
                        data-desc="Laporan pengeluaran produk jadi mingguan dan bulanan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Pengeluaran</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Distribusi & pengeluaran</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 5. Realisasi Kiriman -->
                @can('gj.realisasikiriman')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-realisasikiriman"
                        data-title="Laporan Realisasi Kiriman"
                        data-desc="Laporan komparasi dan pencapaian realisasi pengiriman barang ke cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Realisasi Kiriman</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Pencapaian kirim ke cabang</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 6. Realisasi OMAN -->
                @can('gj.realisasioman')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-realisasioman"
                        data-title="Laporan Realisasi OMAN"
                        data-desc="Laporan pemenuhan order marketing (OMAN) per cabang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Realisasi OMAN</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Pemenuhan order marketing</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                <!-- 7. Angkutan -->
                @can('gj.angkutan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-angkutan"
                        data-title="Laporan Angkutan"
                        data-desc="Laporan rekapitulasi pengiriman dan biaya ekspedisi angkutan armada.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="navigation" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Angkutan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Biaya angkutan & ekspedisi</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

            </div>

            <!-- RIGHT TAB CONTENT (DYNAMIC FORM PANES) -->
            <div class="flex-1 p-4 sm:p-6 flex flex-col justify-start">
                
                <!-- HEADER STRIP ACTIVE TAB INFO (BERSIH TANPA TITIK BIRU & TANPA BADGE FORMAT) -->
                <div class="pb-3 mb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <h4 id="activeTabTitle" class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">
                        Laporan Persediaan Produk Jadi
                    </h4>
                    <p id="activeTabDesc" class="text-[11px] text-slate-400 mt-0.5">
                        Laporan kartu persediaan dan pergerakan stok barang produk jadi.
                    </p>
                </div>

                <!-- TAB PANES -->
                <div class="tab-panes-wrapper">
                    <!-- Pane 1: Persediaan -->
                    @can('gj.persediaan')
                        <div id="pane-persediaan" class="laporan-pane">
                            @include('v2.gudangjadi.laporan.persediaan')
                        </div>
                    @endcan

                    <!-- Pane 2: Rekap Persediaan -->
                    @can('gj.rekappersediaan')
                        <div id="pane-rekappersediaan" class="laporan-pane hidden">
                            @include('v2.gudangjadi.laporan.rekappersediaan')
                        </div>
                    @endcan

                    <!-- Pane 3: Hasil Produksi -->
                    @can('gj.rekaphasilproduksi')
                        <div id="pane-rekaphasilproduksi" class="laporan-pane hidden">
                            @include('v2.gudangjadi.laporan.rekaphasilproduksi')
                        </div>
                    @endcan

                    <!-- Pane 4: Rekap Pengeluaran -->
                    @can('gj.rekappengeluaran')
                        <div id="pane-rekappengeluaran" class="laporan-pane hidden">
                            @include('v2.gudangjadi.laporan.rekappengeluaran')
                        </div>
                    @endcan

                    <!-- Pane 5: Realisasi Kiriman -->
                    @can('gj.realisasikiriman')
                        <div id="pane-realisasikiriman" class="laporan-pane hidden">
                            @include('v2.gudangjadi.laporan.realisasikiriman')
                        </div>
                    @endcan

                    <!-- Pane 6: Realisasi OMAN -->
                    @can('gj.realisasioman')
                        <div id="pane-realisasioman" class="laporan-pane hidden">
                            @include('v2.gudangjadi.laporan.realisasioman')
                        </div>
                    @endcan

                    <!-- Pane 7: Angkutan -->
                    @can('gj.angkutan')
                        <div id="pane-angkutan" class="laporan-pane hidden">
                            @include('v2.gudangjadi.laporan.angkutan')
                        </div>
                    @endcan
                </div>

            </div>

        </div>

    </div>

</div>
@endsection

@push('myscript')
<script>
$(function() {
    // Initialize Flatpickr Dates for all report forms
    $(".flatpickr-date").flatpickr({
        dateFormat: "Y-m-d"
    });

    // Initialize Select2 V2
    function initSelect2($select, placeholder) {
        if ($select.length) {
            $select.select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%'
            });
        }
    }

    initSelect2($('.select2KodeprodukPersediaan'), 'Pilih Produk');
    initSelect2($('.select2KodeangkutanLaporan'), 'Pilih Armada Angkutan');

    // TAB SWITCHING LOGIC
    $('.laporan-tab-btn').on('click', function(e) {
        e.preventDefault();
        const $this = $(this);
        const targetPane = $this.data('target');
        const title = $this.data('title');
        const desc = $this.data('desc');

        // Reset all buttons to inactive
        $('.laporan-tab-btn').each(function() {
            const $btn = $(this);
            $btn.removeClass('active bg-[#284c9a] text-white shadow-xs')
                .addClass('bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
            $btn.find('.tab-icon').removeClass('bg-white/20 text-white')
                .addClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50');
            $btn.find('.tab-title').removeClass('text-white')
                .addClass('group-hover:text-[#284c9a] dark:group-hover:text-white');
            $btn.find('.tab-desc').removeClass('text-white/75')
                .addClass('text-slate-400 dark:text-slate-500');
            $btn.find('.tab-chevron').removeClass('text-white/80')
                .addClass('text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100');
        });

        // Set clicked button to active
        $this.addClass('active bg-[#284c9a] text-white shadow-xs')
            .removeClass('bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
        $this.find('.tab-icon').addClass('bg-white/20 text-white')
            .removeClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50');
        $this.find('.tab-title').addClass('text-white')
            .removeClass('group-hover:text-[#284c9a] dark:group-hover:text-white');
        $this.find('.tab-desc').addClass('text-white/75')
            .removeClass('text-slate-400 dark:text-slate-500');
        $this.find('.tab-chevron').addClass('text-white/80')
            .removeClass('text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100');

        // Update Header Titles
        $('#activeTabTitle').text(title);
        $('#activeTabDesc').text(desc);

        // Switch Panes
        $('.laporan-pane').addClass('hidden');
        $(targetPane).removeClass('hidden');

        // Re-init lucide icons for fresh pane if needed
        if (window.lucide) lucide.createIcons();
    });

    // STANDAR VALIDASI INTERAKTIF V2 (ANTI SWAL ALERT)
    function setFieldError($field, message) {
        const $group = $field.closest('.field-group');
        $group.addClass('has-error');
        $field.addClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-selection').addClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-container').addClass('is-invalid');
        $group.find('.input-prefix-icon').addClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
        $group.append(`
            <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>${message}</span>
            </p>
        `);
    }

    function clearFieldError($field) {
        const $group = $field.closest('.field-group');
        $group.removeClass('has-error');
        $field.removeClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-selection').removeClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-container').removeClass('is-invalid');
        $group.find('.input-prefix-icon').removeClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
    }

    // Live Cleanup on any input/select change
    $("form").on('input change select2:select select2:clear', 'input, select', function() {
        clearFieldError($(this));
    });

    // Helper untuk Tombol Cetak / Export Loading State (Target _blank Timeout 2.5s)
    function handleReportSubmit($form, validatorCallback) {
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;

            // Run form specific validation
            validatorCallback($form, function($field, msg) {
                setFieldError($field, msg);
                hasError = true;
                if (!$firstError) $firstError = $field;
            });

            if (hasError) {
                e.preventDefault();
                if ($firstError) $firstError.focus();
                return false;
            }

            // Valid form -> Trigger temporary loading state
            const $clickedBtn = $(document.activeElement).is('button[type=submit]') 
                ? $(document.activeElement) 
                : $form.find('button[type=submit]:first');

            const originalHtml = $clickedBtn.html();
            $clickedBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $clickedBtn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mencetak...</span>
            `);

            // Re-enable button after 2.5s because target="_blank" opens in a new tab
            setTimeout(function() {
                $clickedBtn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                $clickedBtn.html(originalHtml);
                if (window.lucide) lucide.createIcons();
            }, 2500);

            return true;
        });
    }

    // 1. Validasi Form Persediaan (kode_produk, dari, sampai)
    handleReportSubmit($("#frmLaporanpersediaan"), function($form, onError) {
        const kode_produk = $form.find("#kode_produk_persediaan").val();
        const dari = $form.find("#dari_persediaan").val();
        const sampai = $form.find("#sampai_persediaan").val();

        if (!kode_produk) onError($form.find("#kode_produk_persediaan"), "Silakan pilih produk terlebih dahulu");
        if (!dari) onError($form.find("#dari_persediaan"), "Periode dari tanggal wajib dipilih");
        if (!sampai) onError($form.find("#sampai_persediaan"), "Periode sampai tanggal wajib dipilih");
        if (dari && sampai && new Date(dari) > new Date(sampai)) {
            onError($form.find("#sampai_persediaan"), "Sampai tanggal harus lebih akhir dari dari tanggal");
        }
    });

    // 2. Validasi Form Rekap Persediaan (dari, sampai)
    handleReportSubmit($("#frmRekappersediaan"), function($form, onError) {
        const dari = $form.find("#dari_rekappersediaan").val();
        const sampai = $form.find("#sampai_rekappersediaan").val();

        if (!dari) onError($form.find("#dari_rekappersediaan"), "Periode dari tanggal wajib dipilih");
        if (!sampai) onError($form.find("#sampai_rekappersediaan"), "Periode sampai tanggal wajib dipilih");
        if (dari && sampai && new Date(dari) > new Date(sampai)) {
            onError($form.find("#sampai_rekappersediaan"), "Sampai tanggal harus lebih akhir dari dari tanggal");
        }
    });

    // 3. Validasi Form Rekap Hasil Produksi (dari, sampai)
    handleReportSubmit($("#frmRekaphasilproduksi"), function($form, onError) {
        const dari = $form.find("#dari_rekaphasilproduksi").val();
        const sampai = $form.find("#sampai_rekaphasilproduksi").val();

        if (!dari) onError($form.find("#dari_rekaphasilproduksi"), "Periode dari tanggal wajib dipilih");
        if (!sampai) onError($form.find("#sampai_rekaphasilproduksi"), "Periode sampai tanggal wajib dipilih");
        if (dari && sampai && new Date(dari) > new Date(sampai)) {
            onError($form.find("#sampai_rekaphasilproduksi"), "Sampai tanggal harus lebih akhir dari dari tanggal");
        }
    });

    // 4. Validasi Form Rekap Pengeluaran (bulan, tahun)
    handleReportSubmit($("#frmRekappengeluaran"), function($form, onError) {
        const bulan = $form.find("#bulan_rekappengeluaran").val();
        const tahun = $form.find("#tahun_rekappengeluaran").val();

        if (!bulan) onError($form.find("#bulan_rekappengeluaran"), "Silakan pilih bulan");
        if (!tahun) onError($form.find("#tahun_rekappengeluaran"), "Silakan pilih tahun");
    });

    // 5. Validasi Form Realisasi Kiriman (dari, sampai)
    handleReportSubmit($("#frmRealisasikiriman"), function($form, onError) {
        const dari = $form.find("#dari_realisasikiriman").val();
        const sampai = $form.find("#sampai_realisasikiriman").val();

        if (!dari) onError($form.find("#dari_realisasikiriman"), "Periode dari tanggal wajib dipilih");
        if (!sampai) onError($form.find("#sampai_realisasikiriman"), "Periode sampai tanggal wajib dipilih");
        if (dari && sampai && new Date(dari) > new Date(sampai)) {
            onError($form.find("#sampai_realisasikiriman"), "Sampai tanggal harus lebih akhir dari dari tanggal");
        }
    });

    // 6. Validasi Form Realisasi OMAN (bulan, tahun)
    handleReportSubmit($("#frmRealisasioman"), function($form, onError) {
        const bulan = $form.find("#bulan_realisasioman").val();
        const tahun = $form.find("#tahun_realisasioman").val();

        if (!bulan) onError($form.find("#bulan_realisasioman"), "Silakan pilih bulan");
        if (!tahun) onError($form.find("#tahun_realisasioman"), "Silakan pilih tahun");
    });

    // 7. Validasi Form Angkutan (kode_angkutan, dari, sampai)
    handleReportSubmit($("#frmAngkutan"), function($form, onError) {
        const kode_angkutan = $form.find("#kode_angkutan_laporan").val();
        const dari = $form.find("#dari_angkutan").val();
        const sampai = $form.find("#sampai_angkutan").val();

        if (!kode_angkutan) onError($form.find("#kode_angkutan_laporan"), "Silakan pilih armada angkutan");
        if (!dari) onError($form.find("#dari_angkutan"), "Periode dari tanggal wajib dipilih");
        if (!sampai) onError($form.find("#sampai_angkutan"), "Periode sampai tanggal wajib dipilih");
        if (dari && sampai && new Date(dari) > new Date(sampai)) {
            onError($form.find("#sampai_angkutan"), "Sampai tanggal harus lebih akhir dari dari tanggal");
        }
    });

    if (window.lucide) lucide.createIcons();
});
</script>
@endpush
