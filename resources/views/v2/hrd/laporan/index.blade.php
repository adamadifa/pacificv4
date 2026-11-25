@extends('v2.layouts.app')
@section('titlepage', 'Laporan HRD')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Human Resources</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Laporan HRD</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Laporan HRD</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pusat laporan presensi, penggajian, cuti, keterlambatan, dan pelanggaran karyawan.</p>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER WITH VERTICAL TABS -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">

        <!-- 2-COLUMN VERTICAL TAB LAYOUT -->
        <div class="flex flex-col md:flex-row min-h-[460px]">

            <!-- LEFT VERTICAL TAB MENU -->
            <div class="w-full md:w-72 shrink-0 border-b md:border-b-0 md:border-r border-slate-200/80 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-900/50 p-3 sm:p-3.5 flex flex-col gap-1.5 overflow-y-auto max-h-[660px]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 py-0.5 block">Kategori Laporan</span>

                @can('hrd.presensi')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-presensi"
                        data-title="Laporan Presensi"
                        data-desc="Rekapitulasi dan detail kehadiran karyawan berdasarkan periode laporan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Presensi</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekap & detail kehadiran</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('hrd.gaji')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-gaji"
                        data-title="Laporan Gaji"
                        data-desc="Laporan gaji detail, rekap, SPIP & BPJS, serta THR karyawan.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Gaji</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Gaji, BPJS & THR bulanan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('hrd.presensi')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-cuti"
                        data-title="Laporan Cuti"
                        data-desc="Rekapitulasi pengambilan dan sisa cuti karyawan per tahun.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="calendar-off" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Cuti</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekap cuti tahunan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('hrd.presensi')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-keterlambatan"
                        data-title="Laporan Keterlambatan"
                        data-desc="Daftar keterlambatan masuk kerja karyawan berdasarkan rentang tanggal.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="alarm-clock" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Keterlambatan</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Keterlambatan per tanggal</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan

                @can('hrd.pelanggaran')
                    <button type="button" class="laporan-tab-btn group relative flex items-center gap-2.5 p-2.5 rounded-lg text-left transition-all duration-200 cursor-pointer bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60"
                        data-target="#pane-pelanggaran"
                        data-title="Laporan Pelanggaran"
                        data-desc="Rekapitulasi data pelanggaran dan surat peringatan karyawan per tahun.">
                        <div class="tab-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0 transition">
                            <i data-lucide="shield-alert" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="tab-title block text-xs font-bold leading-tight transition">Pelanggaran</span>
                            <span class="tab-desc block text-[10.5px] text-slate-400 dark:text-slate-500 truncate mt-0.5">Rekap pelanggaran tahunan</span>
                        </div>
                        <i data-lucide="chevron-right" class="tab-chevron w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 shrink-0 ml-auto transition"></i>
                    </button>
                @endcan
            </div>

            <!-- RIGHT CONTENT PANE -->
            <div class="flex-1 min-w-0 p-4 sm:p-5">
                <div class="pb-3 mb-4 border-b border-slate-200/80 dark:border-slate-800">
                    <h4 id="activeReportTitle" class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider"></h4>
                    <p id="activeReportDesc" class="text-[11px] text-slate-400 mt-0.5"></p>
                </div>

                <!-- TAB PANES -->
                @can('hrd.presensi')
                    <div id="pane-presensi" class="laporan-panel hidden">
                        @include('v2.hrd.laporan.presensi')
                    </div>
                @endcan

                @can('hrd.gaji')
                    <div id="pane-gaji" class="laporan-panel hidden">
                        @include('v2.hrd.laporan.gaji')
                    </div>
                @endcan

                @can('hrd.presensi')
                    <div id="pane-cuti" class="laporan-panel hidden">
                        @include('v2.hrd.laporan.cuti')
                    </div>
                @endcan

                @can('hrd.presensi')
                    <div id="pane-keterlambatan" class="laporan-panel hidden">
                        @include('v2.hrd.laporan.keterlambatan')
                    </div>
                @endcan

                @can('hrd.pelanggaran')
                    <div id="pane-pelanggaran" class="laporan-panel hidden">
                        @include('v2.hrd.laporan.pelanggaran')
                    </div>
                @endcan
            </div>

        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    const LaporanHrdValidator = {
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
        triggerPrintSpinner: function($form, clickedButton) {
            const $btnSubmit = $form.find('button[name="submitButton"]').first();
            const $btnExport = $form.find('button[name="exportButton"]').first();
            const origSubmit = $btnSubmit.html();
            const origExport = $btnExport.html();

            $form.find('input.temp-export-flag').remove();
            if (clickedButton === 'exportButton') {
                $form.append('<input type="hidden" name="exportButton" value="1" class="temp-export-flag">');
            }

            $btnSubmit.prop('disabled', true).addClass('opacity-75 pointer-events-none');
            $btnExport.prop('disabled', true).addClass('opacity-75 pointer-events-none');

            const $targetBtn = clickedButton === 'exportButton' ? $btnExport : $btnSubmit;
            const labelText = clickedButton === 'exportButton' ? 'Mengexport...' : 'Mencetak...';

            $targetBtn.html(`
                <svg class="animate-spin -ml-1 mr-1.5 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>${labelText}</span>
            `);

            setTimeout(function() {
                $form.find('input.temp-export-flag').remove();
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

        ['presensi', 'gaji', 'cuti', 'keterlambatan'].forEach(function(suffix) {
            setupSelect2(`.select2Kodecabang${suffix}`, 'Semua Cabang');
            setupSelect2(`.select2Kodedept${suffix}`, 'Semua Departemen');
            setupSelect2(`.select2Kodegroup${suffix}`, 'Semua Group');
        });

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

        // Dynamic Departemen & Group by Cabang
        function bindCabangDependency(formId, cabangId, deptId, groupId) {
            const $form = $(`#${formId}`);
            if (!$form.length) return;

            function loadDeptAndGroup(kode_cabang) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('laporanhrd.getdepartemen') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        $form.find(`#${deptId}`).html(respond).trigger('change.select2');
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ route('laporanhrd.getgroup') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        $form.find(`#${groupId}`).html(respond).trigger('change.select2');
                    }
                });
            }

            const $cabang = $form.find(`#${cabangId}`);
            loadDeptAndGroup($cabang.length ? $cabang.val() : '');

            $cabang.on('change', function() {
                loadDeptAndGroup($(this).val());
            });
        }

        bindCabangDependency('frmLaporanPresensi', 'kode_cabang_presensi', 'kode_dept_presensi', 'kode_group_presensi');
        bindCabangDependency('frmLaporanGaji', 'kode_cabang_gaji', 'kode_dept_gaji', 'kode_group_gaji');
        bindCabangDependency('frmLaporanCuti', 'kode_cabang_cuti', 'kode_dept_cuti', 'kode_group_cuti');
        bindCabangDependency('frmLaporanKeterlambatan', 'kode_cabang_keterlambatan', 'kode_dept_keterlambatan', 'kode_group_keterlambatan');

        // Helper binding submit button click tracking
        function bindValidatedForm(formId, validateFn) {
            const $form = $(`#${formId}`);
            if (!$form.length) return;

            LaporanHrdValidator.bindLiveCleanup($form);

            let clickedButton = 'submitButton';
            $form.on('click', 'button[type="submit"]', function() {
                clickedButton = $(this).attr('name') || 'submitButton';
            });

            $form.on('submit', function(e) {
                const firstInvalid = validateFn($form);
                if (firstInvalid) {
                    e.preventDefault();
                    firstInvalid.focus();
                    return false;
                }
                LaporanHrdValidator.triggerPrintSpinner($form, clickedButton);
            });
        }

        // 1. Laporan Presensi
        bindValidatedForm('frmLaporanPresensi', function($form) {
            const $periode = $form.find('#periode_laporan_presensi');
            const $bulan = $form.find('#bulan_presensi');
            const $tahun = $form.find('#tahun_presensi');
            let firstInvalid = null;

            if (!$periode.val()) {
                LaporanHrdValidator.showError($periode, 'Periode laporan wajib dipilih.');
                firstInvalid = firstInvalid || $periode;
            }
            if (!$bulan.val()) {
                LaporanHrdValidator.showError($bulan, 'Bulan wajib dipilih.');
                firstInvalid = firstInvalid || $bulan;
            }
            if (!$tahun.val()) {
                LaporanHrdValidator.showError($tahun, 'Tahun wajib dipilih.');
                firstInvalid = firstInvalid || $tahun;
            }
            return firstInvalid;
        });

        // 2. Laporan Gaji
        bindValidatedForm('frmLaporanGaji', function($form) {
            const $bulan = $form.find('#bulan_gaji');
            const $tahun = $form.find('#tahun_gaji');
            let firstInvalid = null;

            if (!$bulan.val()) {
                LaporanHrdValidator.showError($bulan, 'Bulan wajib dipilih.');
                firstInvalid = firstInvalid || $bulan;
            }
            if (!$tahun.val()) {
                LaporanHrdValidator.showError($tahun, 'Tahun wajib dipilih.');
                firstInvalid = firstInvalid || $tahun;
            }
            return firstInvalid;
        });

        // 3. Laporan Cuti
        bindValidatedForm('frmLaporanCuti', function($form) {
            const $tahun = $form.find('#tahun_cuti');
            if (!$tahun.val()) {
                LaporanHrdValidator.showError($tahun, 'Tahun wajib dipilih.');
                return $tahun;
            }
            return null;
        });

        // 4. Laporan Keterlambatan
        bindValidatedForm('frmLaporanKeterlambatan', function($form) {
            const $dari = $form.find('#dari_tanggal_keterlambatan');
            const $sampai = $form.find('#sampai_tanggal_keterlambatan');
            let firstInvalid = null;

            if (!$dari.val()) {
                LaporanHrdValidator.showError($dari, 'Dari tanggal wajib diisi.');
                firstInvalid = firstInvalid || $dari;
            }
            if (!$sampai.val()) {
                LaporanHrdValidator.showError($sampai, 'Sampai tanggal wajib diisi.');
                firstInvalid = firstInvalid || $sampai;
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                LaporanHrdValidator.showError($sampai, 'Sampai tanggal tidak boleh lebih kecil dari dari tanggal.');
                firstInvalid = firstInvalid || $sampai;
            }
            return firstInvalid;
        });

        // 5. Laporan Pelanggaran
        bindValidatedForm('frmLaporanPelanggaran', function($form) {
            const $tahun = $form.find('#tahun_pelanggaran');
            if (!$tahun.val()) {
                LaporanHrdValidator.showError($tahun, 'Tahun wajib dipilih.');
                return $tahun;
            }
            return null;
        });
    });
</script>
@endpush
