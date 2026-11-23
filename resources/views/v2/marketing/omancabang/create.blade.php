<form action="{{ route('omancabang.store') }}" method="POST" id="frmCreateomancabang" autocomplete="off" class="space-y-3">
    @csrf
    <input type="hidden" id="cektutuplaporan" value="0">

    @php
        $user = auth()->user();
        $roles_show_cabang = config('global.roles_show_cabang');
        $hasAccessAllCabang = $user->hasRole($roles_show_cabang);
    @endphp

    <!-- SECTION PARAMETER OMAN CABANG -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Parameter OMAN Cabang</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            
            @if ($hasAccessAllCabang)
                <!-- Pilihan Cabang (Col-span 6) -->
                <div class="sm:col-span-6 field-group">
                    <label for="kode_cabang_create" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                        Cabang <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang_create"
                            class="select2KodecabangCreate select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->kode_cabang }} - {{ textUpperCase($c->nama_cabang) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Bulan (Col-span 3) -->
                <div class="sm:col-span-3 field-group">
                    <label for="bulan_create" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                        Bulan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="bulan" id="bulan_create"
                            class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Bulan</option>
                            @foreach ($list_bulan as $b)
                                <option value="{{ $b['kode_bulan'] }}" {{ date('m') == $b['kode_bulan'] ? 'selected' : '' }}>
                                    {{ $b['nama_bulan'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                        </div>
                    </div>
                </div>

                <!-- Tahun (Col-span 3) -->
                <div class="sm:col-span-3 field-group">
                    <label for="tahun_create" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="tahun" id="tahun_create"
                            class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Tahun</option>
                            @for ($t = $start_year; $t <= date('Y') + 1; $t++)
                                <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                        </div>
                    </div>
                </div>
            @else
                <!-- Bulan (Col-span 6) -->
                <div class="sm:col-span-6 field-group">
                    <label for="bulan_create" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                        Bulan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="bulan" id="bulan_create"
                            class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Bulan</option>
                            @foreach ($list_bulan as $b)
                                <option value="{{ $b['kode_bulan'] }}" {{ date('m') == $b['kode_bulan'] ? 'selected' : '' }}>
                                    {{ $b['nama_bulan'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                        </div>
                    </div>
                </div>

                <!-- Tahun (Col-span 6) -->
                <div class="sm:col-span-6 field-group">
                    <label for="tahun_create" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="tahun" id="tahun_create"
                            class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Tahun</option>
                            @for ($t = $start_year; $t <= date('Y') + 1; $t++)
                                <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3 h-3"></i>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- TABEL INPUT MATRIKS PERMINTAAN PER MINGGU -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[360px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tableomanCabang">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="7" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Rincian Permintaan OMAN Cabang</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">{{ count($produk) }} Produk</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-2 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">MINGGU 1</th>
                        <th class="py-2 px-2 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">MINGGU 2</th>
                        <th class="py-2 px-2 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">MINGGU 3</th>
                        <th class="py-2 px-2 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">MINGGU 4</th>
                        <th class="py-2 px-3 text-right w-28 font-bold uppercase tracking-wider text-white/90 border-none">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @foreach ($produk as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3">
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-2 text-center">
                                <input type="text" id="jmlm1" name="jmlm1[]"
                                    class="jmlm1 money w-24 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition"
                                    placeholder="0">
                            </td>
                            <td class="py-1.5 px-2 text-center">
                                <input type="text" id="jmlm2" name="jmlm2[]"
                                    class="jmlm2 money w-24 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition"
                                    placeholder="0">
                            </td>
                            <td class="py-1.5 px-2 text-center">
                                <input type="text" id="jmlm3" name="jmlm3[]"
                                    class="jmlm3 money w-24 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition"
                                    placeholder="0">
                            </td>
                            <td class="py-1.5 px-2 text-center">
                                <input type="text" id="jmlm4" name="jmlm4[]"
                                    class="jmlm4 money w-24 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition"
                                    placeholder="0">
                            </td>
                            <td class="py-1.5 px-3 text-right bg-slate-50/50 dark:bg-slate-800/30">
                                <input type="text" id="subtotal" name="subtotal[]"
                                    class="subtotal w-28 h-[28px] px-2 text-right font-mono-num font-black text-xs rounded border-0 bg-transparent text-[#284c9a] dark:text-blue-400 cursor-default"
                                    placeholder="0" readonly>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 font-bold text-xs sticky bottom-0 z-10 backdrop-blur-xs">
                    <tr>
                        <td colspan="2" class="py-2 px-3 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            Grand Total:
                        </td>
                        <td id="totColM1" class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-100">0</td>
                        <td id="totColM2" class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-100">0</td>
                        <td id="totColM3" class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-100">0</td>
                        <td id="totColM4" class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-100">0</td>
                        <td id="totColGrand" class="py-2 px-3 text-right font-mono-num font-black text-sm text-[#284c9a] dark:text-blue-400 bg-blue-50/50 dark:bg-blue-950/30">0</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- FOOTER ACTIONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3">
        <div class="field-group">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="agreement_check"
                    class="w-4 h-4 rounded-sm border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 cursor-pointer">
                <label for="agreement_check" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                    Konfirmasi data OMAN cabang sudah benar
                </label>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpan"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span id="btnSimpanText">Simpan OMAN Cabang</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#frmCreateomancabang');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Initialize Select2 Floating
        $form.find('.select2KodecabangCreate').select2({
            placeholder: "Pilih Cabang",
            allowClear: true,
            width: '100%'
        });

        // Money Masking
        function initMaskMoney() {
            if (typeof $.fn.maskMoney !== 'undefined') {
                $form.find(".money").maskMoney({
                    thousands: '.',
                    decimal: ',',
                    precision: 0,
                    allowZero: true
                });
            }
        }
        initMaskMoney();

        function formatRupiah(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function parseNum(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        // Recalculate Row & Column Totals
        function recalculate() {
            let colM1 = 0, colM2 = 0, colM3 = 0, colM4 = 0, colGrand = 0;

            $form.find("#tableomanCabang tbody tr").each(function() {
                const $row = $(this);
                const m1 = parseNum($row.find(".jmlm1").val());
                const m2 = parseNum($row.find(".jmlm2").val());
                const m3 = parseNum($row.find(".jmlm3").val());
                const m4 = parseNum($row.find(".jmlm4").val());
                const rowTotal = m1 + m2 + m3 + m4;

                $row.find(".subtotal").val(rowTotal > 0 ? formatRupiah(rowTotal) : "0");

                colM1 += m1;
                colM2 += m2;
                colM3 += m3;
                colM4 += m4;
                colGrand += rowTotal;
            });

            $form.find("#totColM1").text(formatRupiah(colM1));
            $form.find("#totColM2").text(formatRupiah(colM2));
            $form.find("#totColM3").text(formatRupiah(colM3));
            $form.find("#totColM4").text(formatRupiah(colM4));
            $form.find("#totColGrand").text(formatRupiah(colGrand));
        }

        $form.on('input change', '.jmlm1, .jmlm2, .jmlm3, .jmlm4', function() {
            recalculate();
        });

        // Error Helpers
        function showError($field, message) {
            $field.addClass('!border-rose-500 !ring-1 !ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('!text-rose-500');
            $group.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearError($field) {
            $field.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('!text-rose-500');
            $group.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $group.find('.field-error-msg').remove();
        }

        $form.on('input change', 'select, input', function() {
            clearError($(this));
        });

        $form.find('.select2KodecabangCreate').on('select2:select select2:clear', function() {
            clearError($(this));
        });

        $form.find('#agreement_check').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).removeClass('!ring-2 !ring-rose-500');
                $(this).closest('.field-group').find('.field-error-msg').remove();
            }
        });

        // Cek Tutup Laporan
        function cektutuplaporan() {
            const bulan = $form.find("#bulan_create").val();
            const tahun = $form.find("#tahun_create").val();
            if (bulan && tahun) {
                const bln = bulan < 10 ? "0" + parseInt(bulan) : bulan;
                const tanggal = tahun + "-" + bln + "-01";
                $.ajax({
                    type: "POST",
                    url: "/tutuplaporan/cektutuplaporan",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal,
                        jenis_laporan: "penjualan"
                    },
                    cache: false,
                    success: function(respond) {
                        $form.find("#cektutuplaporan").val(respond);
                    }
                });
            }
        }

        $form.find("#bulan_create, #tahun_create").on('change', function() {
            cektutuplaporan();
        });
        cektutuplaporan();

        // Submit Form Validation
        $form.on('submit', function(e) {
            const $cabang = $form.find("#kode_cabang_create");
            const $bulan = $form.find("#bulan_create");
            const $tahun = $form.find("#tahun_create");
            const cektutup = $form.find("#cektutuplaporan").val();
            const $agreement = $form.find('#agreement_check');

            let hasError = false;
            let $firstError = null;

            if ($cabang.length && !$cabang.val()) {
                showError($cabang, 'Pilih cabang terlebih dahulu');
                hasError = true;
                if (!$firstError) $firstError = $cabang;
            }

            if (!$bulan.val()) {
                showError($bulan, 'Pilih bulan OMAN');
                hasError = true;
                if (!$firstError) $firstError = $bulan;
            }

            if (!$tahun.val()) {
                showError($tahun, 'Pilih tahun OMAN');
                hasError = true;
                if (!$firstError) $firstError = $tahun;
            }

            if (cektutup === '1') {
                Swal.fire({
                    title: "Peringatan",
                    text: "Laporan untuk periode tanggal ini sudah ditutup!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            if (!$agreement.is(':checked')) {
                $agreement.addClass('!ring-2 !ring-rose-500');
                const $agGroup = $agreement.closest('.field-group');
                $agGroup.find('.field-error-msg').remove();
                $agGroup.append(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Centang konfirmasi terlebih dahulu</span>
                    </p>
                `);
                hasError = true;
                if (!$firstError) $firstError = $agreement;
            } else {
                $agreement.removeClass('!ring-2 !ring-rose-500');
                $agreement.closest('.field-group').find('.field-error-msg').remove();
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) {
                    if ($firstError.hasClass('select2KodecabangCreate')) {
                        $firstError.select2('open');
                    } else {
                        $firstError.focus();
                    }
                }
                return false;
            }

            // Anti-Double Submit
            const $btn = $form.find('#btnSimpan');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.find('i').remove();
            $form.find('#btnSimpanText').html(`
                <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `);
        });

    });
</script>
