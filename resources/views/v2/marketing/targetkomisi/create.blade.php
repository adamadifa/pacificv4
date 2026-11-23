@php
    $user = auth()->user();
    $roles_show_cabang = config('global.roles_show_cabang');
    $hasAccessAllCabang = $user->hasRole($roles_show_cabang);
@endphp

<form action="{{ route('targetkomisi.store') }}" method="POST" id="formTargetkomisi" autocomplete="off" class="space-y-3.5">
    @csrf

    <!-- SECTION 1: PARAMETER TARGET KOMISI -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Parameter Target Komisi</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
            
            @if ($hasAccessAllCabang)
                <!-- Pilihan Cabang (Col-span 6) -->
                <div class="sm:col-span-6 field-group">
                    <label for="kode_cabang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Cabang <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang"
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
                    <label for="bulan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Bulan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="bulan" id="bulan"
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
                    <label for="tahun" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="tahun" id="tahun"
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
                    <label for="bulan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Bulan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="bulan" id="bulan"
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
                    <label for="tahun" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="tahun" id="tahun"
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

    <!-- SECTION 2: TABEL MATRIKS SALESMAN & PRODUK (STICKY SALESMAN COLUMNS) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[380px] scrollbar-thin">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap" id="tableTargetDetail">
                <thead class="sticky top-0 z-20">
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="{{ count($produk) + 2 }}" class="sticky left-0 z-30 px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="table-2" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Matriks Target Komisi Per Produk</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">{{ count($produk) }} Item Produk</span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Kolom Header Kode & Salesman (Frozen/Sticky Left), dan Produk -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="sticky left-0 z-30 bg-[#1e3a78] py-2 px-3 text-center w-[85px] min-w-[85px] max-w-[85px] font-bold uppercase tracking-wider text-white/90 border-r border-white/10">KODE</th>
                        <th class="sticky left-[85px] z-30 bg-[#1e3a78] py-2 px-3 font-bold uppercase tracking-wider text-white/90 w-[180px] min-w-[180px] max-w-[180px] border-r-2 border-[#152a56] shadow-[3px_0_6px_-2px_rgba(0,0,0,0.35)]">NAMA SALESMAN</th>
                        @foreach ($produk as $p)
                            <th class="py-2 px-2 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-r border-white/10">
                                {{ $p->kode_produk }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="gettargetsalesman" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    <tr>
                        <td colspan="{{ count($produk) + 2 }}" class="py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="w-6 h-6 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                                <span class="text-xs font-medium">Memuat daftar salesman cabang...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 3: KONFIRMASI & SUBMIT -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <input type="checkbox" id="agreement_check" name="agreement"
                class="w-4 h-4 rounded-sm border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 cursor-pointer">
            <label for="agreement_check" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                Konfirmasi data target komisi salesman sudah benar
            </label>
        </div>

        <div class="flex items-center justify-end gap-2 shrink-0">
            <button type="button" class="btn-close-modal h-[36px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpan" disabled
                class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer opacity-50 pointer-events-none">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Simpan Target Komisi</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formTargetkomisi");
        const $kodeCabang = $form.find("#kode_cabang");
        const $bulan = $form.find("#bulan");
        const $tahun = $form.find("#tahun");
        const $btnSimpan = $form.find("#btnSimpan");
        const $agreement = $form.find("#agreement_check");

        // Agreement toggle
        $agreement.on('change', function() {
            if (this.checked) {
                $btnSimpan.prop('disabled', false).removeClass('opacity-50 pointer-events-none');
            } else {
                $btnSimpan.prop('disabled', true).addClass('opacity-50 pointer-events-none');
            }
        });

        // Initialize Select2 if exists
        if (typeof $.fn.select2 !== 'undefined' && $kodeCabang.length) {
            $kodeCabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                width: '100%'
            }).on('change', function() {
                clearFieldError($(this));
                loadSalesman();
            });
        } else {
            $kodeCabang.on('change', function() {
                clearFieldError($(this));
                loadSalesman();
            });
        }

        // Live cleanup for bulan and tahun
        $bulan.on('change', function() { clearFieldError($(this)); });
        $tahun.on('change', function() { clearFieldError($(this)); });

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

        // Load Salesman AJAX
        function loadSalesman() {
            const valCabang = $kodeCabang.length ? $kodeCabang.val() : '';
            $form.find("#gettargetsalesman").html(`
                <tr>
                    <td colspan="{{ count($produk) + 2 }}" class="py-10 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <div class="w-6 h-6 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-xs font-medium">Memuat data salesman...</span>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                type: 'POST',
                url: "{{ route('targetkomisi.gettargetsalesman') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: valCabang
                },
                success: function(response) {
                    $form.find("#gettargetsalesman").html(response);
                    if (typeof $.fn.maskMoney !== 'undefined') {
                        $form.find(".money").maskMoney({
                            thousands: '.',
                            decimal: ',',
                            precision: 0,
                            allowZero: true
                        });
                    }
                },
                error: function() {
                    $form.find("#gettargetsalesman").html(`
                        <tr>
                            <td colspan="{{ count($produk) + 2 }}" class="py-8 text-center text-rose-500">
                                <span class="text-xs font-bold">Gagal memuat data salesman cabang.</span>
                            </td>
                        </tr>
                    `);
                }
            });
        }

        // Initial Load
        loadSalesman();

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
                setFieldError($bulan, 'Pilih bulan permintaan target');
                hasError = true;
                if (!$firstError) $firstError = $bulan;
            }

            if (!$tahun.val()) {
                setFieldError($tahun, 'Pilih tahun permintaan target');
                hasError = true;
                if (!$firstError) $firstError = $tahun;
            }

            if (!$agreement.is(':checked')) {
                hasError = true;
                alert('Silakan centang konfirmasi bahwa data target komisi sudah benar.');
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
