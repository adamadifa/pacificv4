<form action="{{ route('omancabang.update', Crypt::encrypt($oman_cabang->kode_oman)) }}" method="POST" id="frmeditOmancabang" autocomplete="off" class="space-y-3">
    @csrf

    <!-- SECTION DOKUMEN & PARAMETER OMAN CABANG -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block leading-none">Kode OMAN</span>
                    <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-xs sm:text-sm">
                        {{ $oman_cabang->kode_oman }}
                    </span>
                </div>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50">
                <i data-lucide="edit-3" class="w-3 h-3"></i>
                <span>Mode Edit</span>
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
            <!-- Cabang -->
            <div class="flex flex-col justify-center">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Cabang</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-bold text-xs border border-blue-200/60 dark:border-blue-900/50 w-fit">
                    <i data-lucide="building-2" class="w-3 h-3"></i>
                    <span>{{ textUpperCase($oman_cabang->cabang->nama_cabang) }}</span>
                </span>
            </div>

            <!-- Bulan & Tahun -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Periode</span>
                <div class="flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                    <span class="font-bold text-slate-800 dark:text-slate-100 text-xs">
                        {{ $namabulan[$oman_cabang->bulan] }} {{ $oman_cabang->tahun }}
                    </span>
                </div>
            </div>

            <!-- Total Item -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Total Produk</span>
                <div class="flex items-center gap-1.5">
                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                    <span class="font-bold text-slate-800 dark:text-slate-100 text-xs">
                        {{ count($detail) }} Produk
                    </span>
                </div>
            </div>
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
                                    <span class="text-xs font-bold text-white">Daftar Produk OMAN Cabang</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">{{ count($detail) }} Produk</span>
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
                    @foreach ($detail as $d)
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
                                    placeholder="0" value="{{ formatAngka($d->minggu_1) }}">
                            </td>
                            <td class="py-1.5 px-2 text-center">
                                <input type="text" id="jmlm2" name="jmlm2[]"
                                    class="jmlm2 money w-24 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition"
                                    placeholder="0" value="{{ formatAngka($d->minggu_2) }}">
                            </td>
                            <td class="py-1.5 px-2 text-center">
                                <input type="text" id="jmlm3" name="jmlm3[]"
                                    class="jmlm3 money w-24 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition"
                                    placeholder="0" value="{{ formatAngka($d->minggu_3) }}">
                            </td>
                            <td class="py-1.5 px-2 text-center">
                                <input type="text" id="jmlm4" name="jmlm4[]"
                                    class="jmlm4 money w-24 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition"
                                    placeholder="0" value="{{ formatAngka($d->minggu_4) }}">
                            </td>
                            <td class="py-1.5 px-3 text-right bg-slate-50/50 dark:bg-slate-800/30">
                                <input type="text" id="subtotal" name="subtotal[]"
                                    class="subtotal w-28 h-[28px] px-2 text-right font-mono-num font-black text-xs rounded border-0 bg-transparent text-[#284c9a] dark:text-blue-400 cursor-default"
                                    placeholder="0" value="{{ formatAngka($d->total) }}" readonly>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 font-bold text-xs sticky bottom-0 z-10 backdrop-blur-xs">
                    <tr>
                        <td colspan="2" class="py-2 px-3 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            Grand Total:
                        </td>
                        <td id="totColM1Edit" class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-100">0</td>
                        <td id="totColM2Edit" class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-100">0</td>
                        <td id="totColM3Edit" class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-100">0</td>
                        <td id="totColM4Edit" class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-100">0</td>
                        <td id="totColGrandEdit" class="py-2 px-3 text-right font-mono-num font-black text-sm text-[#284c9a] dark:text-blue-400 bg-blue-50/50 dark:bg-blue-950/30">0</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- FOOTER ACTIONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3">
        <div class="field-group">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="agreement_check_edit"
                    class="w-4 h-4 rounded-sm border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 cursor-pointer">
                <label for="agreement_check_edit" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                    Konfirmasi perubahan data OMAN cabang sudah benar
                </label>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpanEdit"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                <span id="btnSimpanEditText">Simpan Perubahan</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#frmeditOmancabang');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

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

            $form.find("#totColM1Edit").text(formatRupiah(colM1));
            $form.find("#totColM2Edit").text(formatRupiah(colM2));
            $form.find("#totColM3Edit").text(formatRupiah(colM3));
            $form.find("#totColM4Edit").text(formatRupiah(colM4));
            $form.find("#totColGrandEdit").text(formatRupiah(colGrand));
        }

        recalculate();

        $form.on('input change', '.jmlm1, .jmlm2, .jmlm3, .jmlm4', function() {
            recalculate();
        });

        $form.find('#agreement_check_edit').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).removeClass('!ring-2 !ring-rose-500');
                $(this).closest('.field-group').find('.field-error-msg').remove();
            }
        });

        // Submit Form Validation
        $form.on('submit', function(e) {
            const $agreement = $form.find('#agreement_check_edit');

            if (!$agreement.is(':checked')) {
                e.preventDefault();
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
                $agreement.focus();
                return false;
            }

            // Anti-Double Submit
            const $btn = $form.find('#btnSimpanEdit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.find('i').remove();
            $form.find('#btnSimpanEditText').html(`
                <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `);
        });

    });
</script>
