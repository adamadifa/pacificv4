@extends('layouts.app')
@section('titlepage', 'Laporan General Affair')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">General Affair</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan General Affair</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Filter dan cetak dokumen resmi riwayat service kendaraan operasional dan rekapitulasi bad stok.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER WITH VERTICAL TABS (STANDAR V2) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <div class="flex flex-col md:flex-row min-h-[380px]">
            
            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-72 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3 sm:p-3.5 flex flex-col gap-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Kategori Laporan</span>

                <!-- 1. Service Kendaraan -->
                @can('ga.servicekendaraan')
                    <button type="button" class="laporan-tab-btn active group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-[#284c9a] text-white shadow-xs"
                        data-target="#pane-servicekendaraan"
                        data-title="Laporan Service Kendaraan"
                        data-desc="Rekapitulasi riwayat perbaikan, penggantian suku cadang, dan biaya perawatan kendaraan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="wrench" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight">Service Kendaraan</span>
                            <span class="tab-desc block text-[10.5px] text-white/75 truncate mt-0.5">Riwayat & biaya service</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-white/80 shrink-0 ml-auto"></i>
                    </button>
                @endcan

                <!-- 2. Rekap Bad Stok -->
                @can('ga.rekapbadstok')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-rekapbadstok"
                        data-title="Laporan Rekap Bad Stok"
                        data-desc="Rekapitulasi data bad stok per bulan atau per tahun dari cabang dan gudang.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="package-x" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight group-hover:text-[#284c9a] dark:group-hover:text-white transition">Rekap Bad Stok</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekap bulanan & tahunan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

            </div>

            <!-- RIGHT CONTENT AREA -->
            <div class="flex-1 p-4 sm:p-6 flex flex-col">
                
                <!-- Dynamic Active Report Header (Clean Title without Blue Dot) -->
                <div class="pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
                    <h4 id="activeReportTitle" class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Laporan Service Kendaraan</h4>
                    <p id="activeReportDesc" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Rekapitulasi riwayat perbaikan, penggantian suku cadang, dan biaya perawatan kendaraan.</p>
                </div>

                <!-- Tab Panes -->
                <div class="flex-1">
                    @can('ga.servicekendaraan')
                        <div id="pane-servicekendaraan" class="laporan-pane block">
                            @include('generalaffair.laporan.servicekendaraan')
                        </div>
                    @endcan

                    @can('ga.rekapbadstok')
                        <div id="pane-rekapbadstok" class="laporan-pane hidden">
                            @include('generalaffair.laporan.rekapbadstok')
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

        // Initialize Select2 dropdowns
        $('.select2Kendaraan').select2({ placeholder: 'Semua Kendaraan', allowClear: true, width: '100%' });
        $('.select2Kodecabang').select2({ placeholder: 'Semua Asal Bad Stok', allowClear: true, width: '100%' });

        if (typeof flatpickr !== 'undefined') {
            $(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d"
            });
        }

        // Tab Switching Logic (Unified V2 Style)
        const $tabBtns = $('.laporan-tab-btn');
        const $panes = $('.laporan-pane');

        function activateTab($btn) {
            // Reset all buttons
            $tabBtns.removeClass('active bg-[#284c9a] text-white shadow-xs')
                    .addClass('bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
            $tabBtns.find('.tab-icon').removeClass('bg-white/20 text-white').addClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50');
            $tabBtns.find('.tab-desc').removeClass('text-white/75').addClass('text-slate-400 dark:text-slate-500');
            $tabBtns.find('.tab-chevron').removeClass('text-white/80').addClass('text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100');

            // Set active button
            $btn.addClass('active bg-[#284c9a] text-white shadow-xs')
                .removeClass('bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60');
            $btn.find('.tab-icon').addClass('bg-white/20 text-white').removeClass('bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-950/50');
            $btn.find('.tab-desc').addClass('text-white/75').removeClass('text-slate-400 dark:text-slate-500');
            $btn.find('.tab-chevron').addClass('text-white/80').removeClass('text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100');

            // Update Header Title & Desc
            $('#activeReportTitle').text($btn.data('title'));
            $('#activeReportDesc').text($btn.data('desc'));

            // Toggle Pane
            $panes.addClass('hidden').removeClass('block');
            $($btn.data('target')).removeClass('hidden').addClass('block');
        }

        if ($tabBtns.length > 0) {
            activateTab($tabBtns.first());
        }

        $tabBtns.on('click', function() {
            activateTab($(this));
        });

        // Inline Validation Helpers (Anti-Swal)
        function showInlineError($el, msg) {
            clearInlineError($el);
            $el.addClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find(".select2-selection").addClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").addClass("is-invalid");
                $rel.find("i, svg").addClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $el.focus();
        }

        function clearInlineError($el) {
            $el.removeClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find(".select2-selection").removeClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").removeClass("is-invalid");
                $rel.find("i, svg").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
            }
        }

        function showTempPrintingState($form) {
            const $btn = $form.find('button[type="submit"]:focus').length ? $form.find('button[type="submit"]:focus') : $form.find('.btn-cetak');
            if ($btn.length && $btn.hasClass('btn-cetak')) {
                const origHtml = $btn.html();
                $btn.prop('disabled', true).addClass('opacity-75');
                $btn.html(`
                    <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Mencetak...</span>
                `);
                setTimeout(() => {
                    $btn.prop('disabled', false).removeClass('opacity-75');
                    $btn.html(origHtml);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }, 2500);
            }
        }

        // Validation: Laporan Service Kendaraan
        const formServicekendaraan = $('#formLapServicekendaraan');
        formServicekendaraan.submit(function(e) {
            const $dari = $(this).find("#dari_service");
            const $sampai = $(this).find("#sampai_service");
            const dari = $dari.val().trim();
            const sampai = $sampai.val().trim();
            let hasError = false;

            if (!dari) {
                showInlineError($dari, "Periode Dari wajib diisi!");
                hasError = true;
            }
            if (!sampai) {
                showInlineError($sampai, "Periode Sampai wajib diisi!");
                hasError = true;
            }
            if (dari && sampai && new Date(dari).getTime() > new Date(sampai).getTime()) {
                showInlineError($sampai, "Tanggal Sampai tidak boleh lebih awal dari Tanggal Dari!");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            showTempPrintingState($(this));
        });

        // Toggle Bulan container on Format Laporan change
        const formRekapbadstok = $('#formLapRekapbadstok');
        function loadRekapbadstokbulan() {
            const formatlaporan = formRekapbadstok.find("#formatlaporan").val();
            if (formatlaporan == '1') {
                formRekapbadstok.find("#bulan_container").show();
            } else {
                formRekapbadstok.find("#bulan_container").hide();
            }
        }
        loadRekapbadstokbulan();
        formRekapbadstok.find("#formatlaporan").change(function() {
            loadRekapbadstokbulan();
        });

        // Validation: Laporan Rekap Bad Stok
        formRekapbadstok.submit(function(e) {
            const $bulan = $(this).find("#bulan_rekap");
            const $tahun = $(this).find("#tahun_rekap");
            const formatlaporan = $(this).find("#formatlaporan").val();
            let hasError = false;

            if (formatlaporan == '1' && !$bulan.val()) {
                showInlineError($bulan, "Bulan wajib dipilih!");
                hasError = true;
            }
            if (!$tahun.val()) {
                showInlineError($tahun, "Tahun wajib dipilih!");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            showTempPrintingState($(this));
        });

        // Live cleanup
        $(document).on("input change", ".laporan-pane input, .laporan-pane select", function() {
            clearInlineError($(this));
        });

        $(document).on("select2:select select2:clear", function(e) {
            clearInlineError($(e.target));
        });
    });
</script>
@endpush
