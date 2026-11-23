@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $user = auth()->user();
    $hasAccessAllCabang = $user->hasRole($roles_show_cabang);
@endphp

<form action="{{ route('settingkomisidriverhelper.store') }}" method="POST" id="formSettingkomisi" autocomplete="off" class="space-y-3.5">
    @csrf

    <!-- SECTION 1: CABANG & PERIODE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Cabang &amp; Periode Perhitungan</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
            
            @if ($hasAccessAllCabang)
                <!-- Cabang (Col-span 6) -->
                <div class="sm:col-span-6 field-group">
                    <label for="kode_cabang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Cabang <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang_create"
                            class="select2KodecabangCreate select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->kode_cabang }} - {{ textUpperCase($c->nama_cabang) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Bulan (Col-span 3) -->
                <div class="sm:col-span-3 field-group">
                    <label for="bulan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Bulan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="bulan" id="bulan_create"
                            class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>
                                    {{ $d['nama_bulan'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                </div>

                <!-- Tahun (Col-span 3) -->
                <div class="sm:col-span-3 field-group">
                    <label for="tahun_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="tahun" id="tahun_create"
                            class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                </div>
            @else
                <!-- Bulan (Col-span 6) -->
                <div class="sm:col-span-6 field-group">
                    <label for="bulan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Bulan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="bulan" id="bulan_create"
                            class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>
                                    {{ $d['nama_bulan'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                </div>

                <!-- Tahun (Col-span 6) -->
                <div class="sm:col-span-6 field-group">
                    <label for="tahun_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="tahun" id="tahun_create"
                            class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- SECTION 2: PARAMETER NILAI KOMISI -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="coins" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Nilai &amp; Variabel Perhitungan Komisi</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
            
            <!-- 1. Komisi Salesman -->
            <div class="field-group">
                <label for="komisi_salesman_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Komisi Salesman (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="komisi_salesman" id="komisi_salesman_create"
                        class="money w-full h-[36px] pl-8 pr-3 text-right font-mono-num font-bold text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0">
                </div>
            </div>

            <!-- 2. Qty Flat -->
            <div class="field-group">
                <label for="qty_flat_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Qty Flat (Dus) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="package" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="qty_flat" id="qty_flat_create"
                        class="money w-full h-[36px] pl-8 pr-3 text-right font-mono-num font-bold text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0">
                </div>
            </div>

            <!-- 3. UMK Cabang -->
            <div class="field-group">
                <label for="umk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    UMK Cabang (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="umk" id="umk_create"
                        class="money w-full h-[36px] pl-8 pr-3 text-right font-mono-num font-bold text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0">
                </div>
            </div>

            <!-- 4. Persentase Komisi -->
            <div class="field-group">
                <label for="persentase_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Persentase (%) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="percent" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="persentase" id="persentase_create"
                        class="w-full h-[36px] pl-8 pr-3 text-right font-mono-num font-bold text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0.00">
                </div>
            </div>

        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" class="btn-close-modal h-[36px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpan"
            class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Setting Komisi</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formSettingkomisi");
        const $kodeCabang = $form.find("#kode_cabang_create");
        const $bulan = $form.find("#bulan_create");
        const $tahun = $form.find("#tahun_create");
        const $komisiSalesman = $form.find("#komisi_salesman_create");
        const $qtyFlat = $form.find("#qty_flat_create");
        const $umk = $form.find("#umk_create");
        const $persentase = $form.find("#persentase_create");
        const $btnSimpan = $form.find("#btnSimpan");

        // MaskMoney initialization
        if (typeof $.fn.maskMoney !== 'undefined') {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        // Initialize Select2 if exists
        if (typeof $.fn.select2 !== 'undefined' && $kodeCabang.length) {
            $kodeCabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                width: '100%'
            }).on('change', function() {
                clearFieldError($(this));
            });
        }

        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $container.find('.field-error-msg').remove();
            $container.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        // Live cleanup
        $form.find("input, select").on('input change', function() {
            clearFieldError($(this));
        });

        // Form Submit Validation
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;

            if ($kodeCabang.length && !$kodeCabang.val()) {
                setFieldError($kodeCabang, 'Pilih cabang terlebih dahulu');
                hasError = true;
                if (!$firstError) $firstError = $kodeCabang;
            }

            if (!$bulan.val()) {
                setFieldError($bulan, 'Pilih bulan perhitungan komisi');
                hasError = true;
                if (!$firstError) $firstError = $bulan;
            }

            if (!$tahun.val()) {
                setFieldError($tahun, 'Pilih tahun perhitungan komisi');
                hasError = true;
                if (!$firstError) $firstError = $tahun;
            }

            if (!$komisiSalesman.val()) {
                setFieldError($komisiSalesman, 'Isi nominal komisi salesman');
                hasError = true;
                if (!$firstError) $firstError = $komisiSalesman;
            }

            if (!$qtyFlat.val()) {
                setFieldError($qtyFlat, 'Isi kuantiti flat');
                hasError = true;
                if (!$firstError) $firstError = $qtyFlat;
            }

            if (!$umk.val()) {
                setFieldError($umk, 'Isi nilai UMK cabang');
                hasError = true;
                if (!$firstError) $firstError = $umk;
            }

            if (!$persentase.val()) {
                setFieldError($persentase, 'Isi persentase komisi');
                hasError = true;
                if (!$firstError) $firstError = $persentase;
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) {
                    if ($firstError.hasClass('select2-has-prefix')) {
                        $firstError.select2('open');
                    } else {
                        $firstError.focus();
                    }
                }
                return false;
            }

            // Anti Double Submit
            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });

    });
</script>
