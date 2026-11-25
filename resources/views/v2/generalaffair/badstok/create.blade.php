<form action="{{ route('badstokga.store') }}" method="POST" id="formBadStok" class="space-y-2.5">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        
        <!-- Tanggal -->
        <div>
            <label for="tanggal_bs_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_bs_create" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>

        <!-- Asal Bad Stok -->
        <div>
            <label for="kode_asal_bs_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Asal Bad Stok <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_asal_bs" id="kode_asal_bs_create" class="select2KodeasalbsCreate w-full">
                    <option value="">Pilih Asal Bad Stok</option>
                    <option value="GDG">GUDANG</option>
                    @foreach ($asalbadstok as $cabang)
                        <option value="{{ $cabang->kode_cabang }}">{{ textUpperCase($cabang->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <!-- Tabel Input Produk Bad Stok -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[340px] overflow-y-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="3" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="package-x" class="w-3.5 h-3.5 text-blue-200"></i>
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Produk Bad Stok</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[180px]">PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @foreach ($produk as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                {{ $d->kode_produk }}
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1 px-2">
                                <input type="text" name="jumlah[]" placeholder="0"
                                    class="money text-right w-full h-[30px] px-2.5 text-xs font-semibold font-mono-num rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Footer Actions (Right-Aligned) -->
    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanBS"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Bad Stok</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const form = $('#formBadStok');

        if (typeof flatpickr !== 'undefined') {
            form.find(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: new Date()
            });
        }

        if ($.fn.maskMoney) {
            form.find(".money").maskMoney({ thousands: '.', decimal: ',', precision: 0 });
        }

        form.find('.select2KodeasalbsCreate').select2({ placeholder: 'Pilih Asal Bad Stok', allowClear: true, width: '100%' });

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

        function buttonDisable() {
            const $btn = form.find("#btnSimpanBS");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        }

        form.submit(function(e) {
            const tanggal = form.find("#tanggal_bs_create").val().trim();
            const kode_asal_bs = form.find("#kode_asal_bs_create").val();

            let hasError = false;

            if (!tanggal) {
                showInlineError(form.find("#tanggal_bs_create"), "Tanggal wajib diisi!");
                hasError = true;
            }
            if (!kode_asal_bs) {
                showInlineError(form.find("#kode_asal_bs_create"), "Asal bad stok wajib dipilih!");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            buttonDisable();
        });

        form.on("input change", "input, select", function() {
            clearInlineError($(this));
        });

        form.find("select").on("select2:select select2:clear", function(e) {
            clearInlineError($(e.target));
        });
    });
</script>
