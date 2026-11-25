<form action="{{ route('setoranpusat.store') }}" id="formSetoranpusat" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Tanggal Setoran -->
    <div class="space-y-1">
        <label for="tanggal_setoran_pusat" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal Setoran <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_setoran_pusat" name="tanggal" readonly placeholder="Pilih Tanggal Setoran"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-tanggal-setoran-pusat" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Tanggal setoran wajib diisi</span>
        </p>
    </div>

    <!-- Cabang (Jika multi-cabang) -->
    @hasanyrole($roles_show_cabang)
        <div class="space-y-1">
            <label for="kode_cabang_setoran_pusat" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_setoran_pusat" class="select2Kodecabang w-full">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
            <p id="error-cabang-setoran-pusat" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Cabang wajib dipilih</span>
            </p>
        </div>
    @endhasanyrole

    <!-- Setoran Kertas, Logam, & Total -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 p-3 sm:p-3.5 space-y-3">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="banknote" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-200">Rincian Nominal Setoran</h6>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <div class="space-y-1">
                <label for="setoran_kertas_pusat" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Setoran Uang Kertas (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="banknote" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="setoran_kertas_pusat" name="setoran_kertas" placeholder="0"
                        class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>

            <div class="space-y-1">
                <label for="setoran_logam_pusat" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Setoran Uang Logam (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="coins" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="setoran_logam_pusat" name="setoran_logam" placeholder="0"
                        class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>
        </div>

        <div class="space-y-1 pt-1">
            <label for="total_setoran_pusat" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Total Setoran (Rp) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calculator" class="w-4 h-4"></i>
                </div>
                <input type="text" id="total_setoran_pusat" name="total_setoran" placeholder="0" readonly
                    class="w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-black font-mono-num text-[#284c9a] dark:text-blue-400">
            </div>
            <p id="error-total-setoran-pusat" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Total setoran tidak boleh 0</span>
            </p>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="space-y-1">
        <label for="keterangan_setoran_pusat" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan
        </label>
        <div class="relative">
            <textarea id="keterangan_setoran_pusat" name="keterangan" rows="2" placeholder="Keterangan tambahan setoran pusat (opsional)"
                class="w-full p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition"></textarea>
        </div>
    </div>

    <!-- Action Buttons (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelSetoranPusat"
            class="h-[34px] px-4 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanSetoranPusat"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Setoran Pusat</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formSetoranpusat");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $form.find(".money-mask").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });

        $form.find('.select2Kodecabang').select2({
            placeholder: 'Pilih Cabang',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number || 0);
        }

        function parseMoney(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        function loadtotal() {
            const sk = parseMoney($form.find('#setoran_kertas_pusat').val());
            const sl = parseMoney($form.find('#setoran_logam_pusat').val());
            const total = sk + sl;
            $form.find("#total_setoran_pusat").val(formatRupiah(total));
            if (total > 0) {
                clearError($form.find("#total_setoran_pusat"), $form.find("#error-total-setoran-pusat"));
            }
        }

        $form.find('#setoran_kertas_pusat, #setoran_logam_pusat').on('input keyup change', function() {
            loadtotal();
        });

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#tanggal_setoran_pusat").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-setoran-pusat"));
        });
        $form.find("#kode_cabang_setoran_pusat").on('change', function() {
            clearError($form.find('.select2-container-v2'), $form.find("#error-cabang-setoran-pusat"));
        });

        $form.find("#btnCancelSetoranPusat").on('click', function() {
            if (typeof modalCreateSetoranPusat !== 'undefined') {
                modalCreateSetoranPusat.close();
            } else {
                $("#modalCreateSetoranPusat").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_setoran_pusat").val();
            const total_setoran = parseMoney($form.find("#total_setoran_pusat").val());

            if (!tanggal) {
                showError($form.find("#tanggal_setoran_pusat"), $form.find("#error-tanggal-setoran-pusat"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_setoran_pusat");
            }

            if (total_setoran <= 0) {
                showError($form.find("#total_setoran_pusat"), $form.find("#error-total-setoran-pusat"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#setoran_kertas_pusat");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanSetoranPusat");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);

            this.submit();
        });
    });
</script>
