@extends('layouts.app')
@section('titlepage', 'Laporan Produksi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Produksi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan Produksi</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan & Rekapitulasi Produksi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Filter dan cetak dokumen resmi mutasi barang jadi, rekapan produksi, serta mutasi bahan baku.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER WITH VERTICAL TABS -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <!-- 2-COLUMN VERTICAL TAB LAYOUT -->
        <div class="flex flex-col md:flex-row min-h-[440px]">
            
            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-72 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3.5 sm:p-4 flex flex-col gap-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Pilihan Laporan</span>

                @can('prd.mutasiproduksi')
                    <button type="button" class="laporan-tab-btn active group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-[#284c9a] text-white shadow-xs"
                        data-target="#pane-mutasiproduksi"
                        data-title="Laporan Mutasi Produksi"
                        data-desc="Laporan pergerakan mutasi masuk dan keluar per item produk jadi.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight">Mutasi Produksi</span>
                            <span class="tab-desc block text-[10.5px] text-white/75 truncate mt-0.5">Per produk barang jadi</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-white/80 shrink-0 ml-auto"></i>
                    </button>
                @endcan

                @can('prd.rekapmutasi')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapmutasiproduksi"
                        data-title="Rekapitulasi Mutasi Produksi"
                        data-desc="Ringkasan rekapitulasi mutasi seluruh produk dalam rentang periode.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Mutasi</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekapitulasi total mutasi</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('prd.pemasukan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-barangmasuk"
                        data-title="Laporan Barang Masuk Produksi"
                        data-desc="Laporan rincian penerimaan barang/bahan baku yang masuk ke unit produksi.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="package-plus" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Barang Masuk</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Pemasukan barang & bahan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('prd.pengeluaran')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-barangkeluar"
                        data-title="Laporan Barang Keluar Produksi"
                        data-desc="Laporan pengeluaran bahan baku untuk pemakaian, retur, atau keperluan lainnya.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="package-minus" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Barang Keluar</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Pengeluaran & pemakaian</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('prd.rekappersediaan')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-3 p-3 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekappersediaanbarang"
                        data-title="Rekapitulasi Persediaan Barang"
                        data-desc="Laporan saldo awal, mutasi, dan saldo akhir persediaan barang produksi bulanan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="boxes" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Persediaan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Saldo stok bulanan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan
            </div>

            <!-- RIGHT FORM PANEL (flex-1) -->
            <div class="flex-1 p-5 sm:p-6 lg:p-7 bg-white dark:bg-slate-900 flex flex-col justify-start min-w-0">
                
                <!-- Active Report Header Info -->
                <div class="flex items-start justify-between gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h4 id="activeReportTitle" class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Laporan Mutasi Produksi</h4>
                        <p id="activeReportDesc" class="text-xs text-slate-500 dark:text-slate-400 mt-1">Laporan pergerakan mutasi masuk dan keluar per item produk jadi.</p>
                    </div>
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-300 border border-blue-100 dark:border-blue-900 shrink-0">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>Parameter Filter</span>
                    </span>
                </div>

                <!-- Form Panes (Isolated from bootstrap tab-pane) -->
                <div class="laporan-panes-wrapper">
                    @can('prd.mutasiproduksi')
                        <div class="laporan-panel" id="pane-mutasiproduksi">
                            @include('produksi.laporan.mutasiproduksi')
                        </div>
                    @endcan

                    @can('prd.rekapmutasi')
                        <div class="laporan-panel hidden" id="pane-rekapmutasiproduksi">
                            @include('produksi.laporan.rekapmutasiproduksi')
                        </div>
                    @endcan

                    @can('prd.pemasukan')
                        <div class="laporan-panel hidden" id="pane-barangmasuk">
                            @include('produksi.laporan.barangmasuk')
                        </div>
                    @endcan

                    @can('prd.pengeluaran')
                        <div class="laporan-panel hidden" id="pane-barangkeluar">
                            @include('produksi.laporan.barangkeluar')
                        </div>
                    @endcan

                    @can('prd.rekappersediaan')
                        <div class="laporan-panel hidden" id="pane-rekappersediaanbarang">
                            @include('produksi.laporan.rekappersediaanbarang')
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
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Vertical Tab Switching Handler
        $(".laporan-tab-btn").on("click", function(e) {
            e.preventDefault();
            const $clicked = $(this);
            const target = $clicked.attr("data-target") || $clicked.data("target");
            const title = $clicked.attr("data-title") || $clicked.data("title") || "Laporan Produksi";
            const desc = $clicked.attr("data-desc") || $clicked.data("desc") || "";

            // Reset all tabs to inactive style
            $(".laporan-tab-btn").removeClass("bg-[#284c9a] text-white shadow-xs active")
                .addClass("bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60");
            
            $(".laporan-tab-btn .tab-icon").removeClass("bg-white/20 text-white")
                .addClass("bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50");

            $(".laporan-tab-btn .tab-title").removeClass("text-white")
                .addClass("text-slate-800 dark:text-slate-200 group-hover:text-[#284c9a] dark:group-hover:text-white");

            $(".laporan-tab-btn .tab-desc").removeClass("text-white/75")
                .addClass("text-slate-400 dark:text-slate-500");

            $(".laporan-tab-btn .tab-chevron").removeClass("text-white/80 opacity-100")
                .addClass("text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100");

            // Set clicked tab to active style
            $clicked.removeClass("bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60")
                .addClass("bg-[#284c9a] text-white shadow-xs active");

            $clicked.find(".tab-icon").removeClass("bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50")
                .addClass("bg-white/20 text-white");

            $clicked.find(".tab-title").removeClass("text-slate-800 dark:text-slate-200 group-hover:text-[#284c9a] dark:group-hover:text-white")
                .addClass("text-white");

            $clicked.find(".tab-desc").removeClass("text-slate-400 dark:text-slate-500")
                .addClass("text-white/75");

            $clicked.find(".tab-chevron").removeClass("text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100")
                .addClass("text-white/80 opacity-100");

            // Update Header info
            $("#activeReportTitle").text(title);
            $("#activeReportDesc").text(desc);

            // Switch Tab Panes
            $(".laporan-panel").addClass("hidden");
            $(target).removeClass("hidden");

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Initialize Select2 with V2 design
        $('.select2Kodeproduk').select2({
            placeholder: 'Pilih Produk',
            width: '100%'
        });

        $('.select2Kodebarangmasuk').select2({
            placeholder: 'Semua Barang Produksi',
            allowClear: true,
            width: '100%'
        });

        $('.select2Kodebarangkeluar').select2({
            placeholder: 'Semua Barang Produksi',
            allowClear: true,
            width: '100%'
        });

        // Interactive Form Validation (Rule 5 Compliant)
        function setFieldError($field, message) {
            clearFieldError($field);
            const $group = $field.closest('.field-group');
            $group.addClass('has-error');
            if ($field.hasClass('select2-hidden-accessible') || $field.next().hasClass('select2')) {
                $field.addClass('is-invalid');
                const $s2Container = $field.next('.select2');
                $s2Container.addClass('is-invalid');
                $s2Container.find('.select2-selection').addClass('is-invalid border-rose-500 !border-rose-500');
                $group.find('.select2-selection').addClass('is-invalid border-rose-500 !border-rose-500');
            } else {
                $field.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid');
            }
            $group.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($field) {
            const $group = $field.closest('.field-group');
            $group.removeClass('has-error');
            if ($field.hasClass('select2-hidden-accessible') || $field.next().hasClass('select2')) {
                $field.removeClass('is-invalid');
                const $s2Container = $field.next('.select2');
                $s2Container.removeClass('is-invalid');
                $s2Container.find('.select2-selection').removeClass('is-invalid border-rose-500 !border-rose-500');
                $group.find('.select2-selection').removeClass('is-invalid border-rose-500 !border-rose-500');
            } else {
                $field.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid');
            }
            $group.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $group.find('.field-error-msg').remove();
        }

        // Live cleanup
        $('.laporan-panes-wrapper form').each(function() {
            const $form = $(this);
            $form.find('input, select').on('input change select2:select', function() {
                clearFieldError($(this));
            });
        });

        function handleReportSubmit($form, validateFn) {
            let hasError = false;
            let $firstErrorField = null;

            function markError($field, msg) {
                setFieldError($field, msg);
                hasError = true;
                if (!$firstErrorField) {
                    $firstErrorField = $field;
                }
            }

            validateFn(markError);

            if (hasError) {
                if ($firstErrorField) {
                    if ($firstErrorField.hasClass('select2-hidden-accessible')) {
                        $firstErrorField.select2('open');
                    } else {
                        $firstErrorField.focus();
                    }
                }
                return false;
            }

            // Anti double submit with temporary spinner (target="_blank" friendly)
            const $activeBtn = $form.find('button[type="submit"]:hover, button[type="submit"]:focus').first();
            const $btn = $activeBtn.length ? $activeBtn : $form.find('button[type="submit"]').first();
            const origHtml = $btn.html();

            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mencetak...</span>
            `);

            setTimeout(function() {
                $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                $btn.html(origHtml);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 2500);

            return true;
        }

        // 1. Mutasi Produksi
        $('#frmLaporanmutasiproduksi').on('submit', function() {
            const $form = $(this);
            return handleReportSubmit($form, function(markError) {
                const $produk = $form.find('[name="kode_produk"]');
                const $dari = $form.find('[name="dari"]');
                const $sampai = $form.find('[name="sampai"]');

                if (!$produk.val()) {
                    markError($produk, 'Pilih produk terlebih dahulu');
                }
                if (!$dari.val()) {
                    markError($dari, 'Periode dari tanggal harus diisi');
                }
                if (!$sampai.val()) {
                    markError($sampai, 'Periode sampai tanggal harus diisi');
                } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                    markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
                }
            });
        });

        // 2. Rekap Mutasi Produksi
        $('#frmRekapnmutasiproduksi').on('submit', function() {
            const $form = $(this);
            return handleReportSubmit($form, function(markError) {
                const $dari = $form.find('[name="dari"]');
                const $sampai = $form.find('[name="sampai"]');

                if (!$dari.val()) {
                    markError($dari, 'Periode dari tanggal harus diisi');
                }
                if (!$sampai.val()) {
                    markError($sampai, 'Periode sampai tanggal harus diisi');
                } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                    markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
                }
            });
        });

        // 3. Barang Masuk
        $('#frmLaporanbarangmasuk').on('submit', function() {
            const $form = $(this);
            return handleReportSubmit($form, function(markError) {
                const $dari = $form.find('[name="dari"]');
                const $sampai = $form.find('[name="sampai"]');

                if (!$dari.val()) {
                    markError($dari, 'Periode dari tanggal harus diisi');
                }
                if (!$sampai.val()) {
                    markError($sampai, 'Periode sampai tanggal harus diisi');
                } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                    markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
                }
            });
        });

        // 4. Barang Keluar
        $('#frmLaporanbarangkeluar').on('submit', function() {
            const $form = $(this);
            return handleReportSubmit($form, function(markError) {
                const $dari = $form.find('[name="dari"]');
                const $sampai = $form.find('[name="sampai"]');

                if (!$dari.val()) {
                    markError($dari, 'Periode dari tanggal harus diisi');
                }
                if (!$sampai.val()) {
                    markError($sampai, 'Periode sampai tanggal harus diisi');
                } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                    markError($sampai, 'Periode sampai harus lebih akhir dari periode dari');
                }
            });
        });

        // 5. Rekap Persediaan Barang
        $('#frmRekappersediaanbarang').on('submit', function() {
            const $form = $(this);
            return handleReportSubmit($form, function(markError) {
                const $bulan = $form.find('[name="bulan"]');
                const $tahun = $form.find('[name="tahun"]');

                if (!$bulan.val()) {
                    markError($bulan, 'Pilih bulan terlebih dahulu');
                }
                if (!$tahun.val()) {
                    markError($tahun, 'Pilih tahun terlebih dahulu');
                }
            });
        });
    });
</script>
@endpush
