@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $canFilterCabang = auth()->user()->hasAnyRole($roles_show_cabang);
@endphp

<form action="{{ route('dpb.store') }}" method="POST" id="formDPB" class="space-y-2.5" autocomplete="off">
    @csrf

    <!-- SECTION PARAMETER HEADER (2-KOLOM VERTIKAL SEIMBANG & KOMPAK) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-2.5 sm:p-3">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-3 items-start">
            
            <!-- KOLOM KIRI: DATA PENUGASAN & SALESMAN -->
            <div class="space-y-2">
                <!-- Baris 1: No DPB & Tanggal Pengambilan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 items-start">
                    <!-- No DPB Prefix & Sequence -->
                    <div class="field-group">
                        <label for="no_dpb" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                            No. DPB <span class="text-rose-500">*</span>
                        </label>
                        <div class="input-group-joined flex items-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 overflow-hidden focus-within:ring-2 focus-within:ring-brand-500/20 focus-within:border-brand-500 transition">
                            <input type="text" name="no_dpb_format" id="no_dpb_format" readonly placeholder="PREFIX"
                                class="w-18 sm:w-20 h-[34px] px-2 text-xs font-mono-num font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-r border-slate-200 dark:border-slate-700 text-center cursor-not-allowed select-none focus:outline-hidden">
                            <input type="text" name="no_dpb" id="no_dpb" maxlength="5" placeholder="00000"
                                class="flex-1 min-w-0 h-[34px] px-2.5 text-xs font-mono-num font-bold bg-transparent text-slate-800 dark:text-slate-100 focus:outline-hidden">
                        </div>
                    </div>

                    <!-- Tanggal Ambil -->
                    <div class="field-group">
                        <label for="tanggal_ambil" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5 whitespace-nowrap">
                            Tanggal Ambil <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tanggal_ambil" id="tanggal_ambil" placeholder="Pilih Tanggal" readonly
                                class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- Baris 2: Cabang & Salesman -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 items-start">
                    @if ($canFilterCabang)
                        <!-- Cabang (Select2 V2) -->
                        <div class="field-group">
                            <label for="kode_cabang" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                                Cabang <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                    <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="kode_cabang" id="kode_cabang"
                                    class="select2Kodecabang select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}">{{ strtoupper($c->nama_cabang) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="kode_cabang" id="kode_cabang" value="{{ auth()->user()->kode_cabang }}">
                    @endif

                    <!-- Salesman -->
                    <div class="{{ $canFilterCabang ? '' : 'sm:col-span-2' }} field-group">
                        <label for="kode_salesman" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                            Salesman <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_salesman" id="kode_salesman"
                                class="select2Kodesalesman select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                <option value="">Pilih Salesman</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Baris 3: Tujuan & Kendaraan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 items-start">
                    <!-- Tujuan -->
                    <div class="field-group">
                        <label for="tujuan" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                            Tujuan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tujuan" id="tujuan" placeholder="Wilayah / Kota Tujuan"
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        </div>
                    </div>

                    <!-- Kendaraan -->
                    <div class="field-group">
                        <label for="kode_kendaraan" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                            Kendaraan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_kendaraan" id="kode_kendaraan"
                                class="select2Kodekendaraan select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                <option value="">Pilih Kendaraan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: TIM PENGANTARAN (DRIVER & HELPER) -->
            <div class="space-y-2">
                <!-- Baris 1: Driver -->
                <div class="field-group">
                    <label for="kode_driver" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                        Driver (Opsional)
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                            <i data-lucide="car" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_driver" id="kode_driver"
                            class="select2Kodedriver select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Pilih Driver</option>
                        </select>
                    </div>
                </div>

                <!-- Baris 2: Helper 1 -->
                <div class="field-group">
                    <label for="kode_helper_1" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                        Helper 1 (Opsional)
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_helper_1" id="kode_helper_1"
                            class="select2Kodehelper1 select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Pilih Helper 1</option>
                        </select>
                    </div>
                </div>

                <!-- Baris 3: Helper 2 & Helper 3 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 items-start">
                    <div class="field-group">
                        <label for="kode_helper_2" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                            Helper 2 (Opsional)
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_helper_2" id="kode_helper_2"
                                class="select2Kodehelper2 select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                <option value="">Pilih Helper 2</option>
                            </select>
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="kode_helper_3" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                            Helper 3 (Opsional)
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_helper_3" id="kode_helper_3"
                                class="select2Kodehelper3 select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                <option value="">Pilih Helper 3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- DETAIL ITEMS TABLE (PAS SESUAI LEBAR MODAL & KOMPAK) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[320px]">
            <table class="w-full table-fixed text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Strip (colspan=6) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-3.5 py-1.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Pengambilan (DPB)</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">{{ count($produk) }} produk</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-1.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-1.5 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-1.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-1.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-32">DUS / BALL</th>
                        <th class="py-1.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-32">PACK</th>
                        <th class="py-1.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-32">PCS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @foreach ($produk as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1 px-3 text-center text-slate-400 font-mono-num text-[11px]">{{ $loop->iteration }}</td>
                            <td class="py-1 px-3 text-center">
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <input type="hidden" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
                                <input type="hidden" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-1 px-3 font-medium text-slate-800 dark:text-slate-200 truncate" title="{{ $d->nama_produk }}">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1 px-2.5 text-right">
                                <input type="text" name="jml_dus[]"
                                    class="money w-full h-[28px] text-right px-2 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                            </td>
                            <td class="py-1 px-2.5 text-right">
                                <input type="text" name="jml_pack[]" {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}
                                    class="money w-full h-[28px] text-right px-2 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 {{ empty($d->isi_pcs_pack) ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-400 cursor-not-allowed' : 'bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500 focus:ring-1 focus:ring-brand-500' }} transition">
                            </td>
                            <td class="py-1 px-2.5 text-right">
                                <input type="text" name="jml_pcs[]"
                                    class="money w-full h-[28px] text-right px-2 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-1">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanDpb"
            class="inline-flex items-center justify-center gap-2 h-[34px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Data DPB</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $('#formDPB');
        const $btnSimpan = $('#btnSimpanDpb');

        // Masking
        if (typeof $.fn.maskMoney !== 'undefined') {
            $(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }
        if (typeof $.fn.mask !== 'undefined') {
            $form.find("#no_dpb").mask("00000");
        }

        // Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Initialize Select2 dropdowns
        const selectConfigs = [
            { el: '.select2Kodecabang', placeholder: 'Pilih Cabang' },
            { el: '.select2Kodesalesman', placeholder: 'Pilih Salesman' },
            { el: '.select2Kodekendaraan', placeholder: 'Pilih Kendaraan' },
            { el: '.select2Kodedriver', placeholder: 'Pilih Driver' },
            { el: '.select2Kodehelper1', placeholder: 'Pilih Helper 1' },
            { el: '.select2Kodehelper2', placeholder: 'Pilih Helper 2' },
            { el: '.select2Kodehelper3', placeholder: 'Pilih Helper 3' }
        ];

        selectConfigs.forEach(cfg => {
            const $target = $form.find(cfg.el);
            if ($target.length) {
                $target.select2({
                    placeholder: cfg.placeholder,
                    allowClear: true,
                    width: '100%'
                });
            }
        });

        // Dynamic loaders by branch
        function getsalesmanbyCabang() {
            const kodeCabang = $form.find("#kode_cabang").val();
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: kodeCabang },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_salesman").html(respond).trigger('change.select2');
                }
            });
        }

        function getkendaraanbyCabang() {
            const kodeCabang = $form.find("#kode_cabang").val();
            $.ajax({
                type: 'POST',
                url: '/kendaraan/getkendaraanbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: kodeCabang },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_kendaraan").html(respond).trigger('change.select2');
                }
            });
        }

        function getdriverhelperbyCabang() {
            const kodeCabang = $form.find("#kode_cabang").val();
            $.ajax({
                type: 'POST',
                url: '/driverhelper/getdriverhelperbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: kodeCabang },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_driver").html(respond).trigger('change.select2');
                    $form.find("#kode_helper_1").html(respond).trigger('change.select2');
                    $form.find("#kode_helper_2").html(respond).trigger('change.select2');
                    $form.find("#kode_helper_3").html(respond).trigger('change.select2');
                }
            });
        }

        function generatenodpb() {
            const kodeCabang = $form.find("#kode_cabang").val();
            const tanggal = $form.find("#tanggal_ambil").val();
            $.ajax({
                type: 'POST',
                url: '/dpb/generatenodpb',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kodeCabang,
                    tanggal: tanggal
                },
                success: function(respond) {
                    $form.find("#no_dpb_format").val(respond);
                }
            });
        }

        $form.find("#tanggal_ambil").on('change', function() {
            generatenodpb();
        });

        $form.find("#kode_cabang").on('change', function() {
            getsalesmanbyCabang();
            getkendaraanbyCabang();
            getdriverhelperbyCabang();
            generatenodpb();
        });

        // Trigger initial load
        getsalesmanbyCabang();
        getkendaraanbyCabang();
        getdriverhelperbyCabang();
        generatenodpb();

        // Validation helpers
        function setFieldError($input, message) {
            const $fieldGroup = $input.closest('.field-group');
            $fieldGroup.addClass('has-error');

            if ($input.hasClass('select2-hidden-accessible')) {
                $fieldGroup.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else if ($fieldGroup.find('.input-group-joined').length) {
                $fieldGroup.find('.input-group-joined').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $input.addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }

            $fieldGroup.find('.input-prefix-icon').addClass('!text-rose-500');
            $fieldGroup.find('.field-error-msg').remove();
            $fieldGroup.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                        <circle cx="12" cy="16" r="1" fill="currentColor"/>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($input) {
            const $fieldGroup = $input.closest('.field-group');
            $fieldGroup.removeClass('has-error');

            if ($input.hasClass('select2-hidden-accessible')) {
                $fieldGroup.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else if ($fieldGroup.find('.input-group-joined').length) {
                $fieldGroup.find('.input-group-joined').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $input.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }

            $fieldGroup.find('.input-prefix-icon').removeClass('!text-rose-500');
            $fieldGroup.find('.field-error-msg').remove();
        }

        $form.find('select, input').on('input change select2:select', function() {
            clearFieldError($(this));
        });

        // Form Submit
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;

            const $noDpb = $form.find('#no_dpb');
            const $tanggalAmbil = $form.find('#tanggal_ambil');
            const $kodeCabang = $form.find('#kode_cabang');
            const $kodeSalesman = $form.find('#kode_salesman');
            const $kodeKendaraan = $form.find('#kode_kendaraan');
            const $tujuan = $form.find('#tujuan');

            if (!$noDpb.val()) {
                setFieldError($noDpb, 'Nomor DPB wajib diisi');
                hasError = true;
                if (!$firstError) $firstError = $noDpb;
            }

            if (!$tanggalAmbil.val()) {
                setFieldError($tanggalAmbil, 'Tanggal pengambilan wajib diisi');
                hasError = true;
                if (!$firstError) $firstError = $tanggalAmbil;
            }

            if ($kodeCabang.length && !$kodeCabang.val()) {
                setFieldError($kodeCabang, 'Cabang wajib dipilih');
                hasError = true;
                if (!$firstError) $firstError = $kodeCabang;
            }

            if (!$kodeSalesman.val()) {
                setFieldError($kodeSalesman, 'Salesman wajib dipilih');
                hasError = true;
                if (!$firstError) $firstError = $kodeSalesman;
            }

            if (!$kodeKendaraan.val()) {
                setFieldError($kodeKendaraan, 'Kendaraan wajib dipilih');
                hasError = true;
                if (!$firstError) $firstError = $kodeKendaraan;
            }

            if (!$tujuan.val()) {
                setFieldError($tujuan, 'Tujuan wajib diisi');
                hasError = true;
                if (!$firstError) $firstError = $tujuan;
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) {
                    if ($firstError.hasClass('select2-hidden-accessible')) {
                        $firstError.select2('open');
                    } else {
                        $firstError.focus();
                    }
                }
                return false;
            }

            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan DPB...</span>
            `);
        });
    });
</script>
