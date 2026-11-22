<form action="{{ route('kategoridiskon.storedetail', Crypt::encrypt($kode_kategori_diskon)) }}" id="formcreateDetail" method="POST" class="space-y-2.5">
    @csrf

    <div class="grid grid-cols-2 gap-2.5">
        <!-- Input Min Qty -->
        <div class="field-group">
            <label for="min_qty_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Min. Qty <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                </div>
                <input type="number" name="min_qty" id="min_qty_create" min="1" autofocus
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Min. Qty (misal: 1)">
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Batas minimum kuantiti.</p>
        </div>

        <!-- Input Max Qty -->
        <div class="field-group">
            <label for="max_qty_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Max. Qty <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                </div>
                <input type="number" name="max_qty" id="max_qty_create" min="1"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Max. Qty (misal: 999)">
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Batas maksimum kuantiti.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2.5">
        <!-- Input Diskon Kredit -->
        <div class="field-group">
            <label for="diskon_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Diskon Kredit (Rp) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                </div>
                <input type="number" name="diskon" id="diskon_create" min="0" step="any"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Diskon Kredit (Rp / Unit)">
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Potongan harga transaksi kredit.</p>
        </div>

        <!-- Input Diskon Tunai -->
        <div class="field-group">
            <label for="diskon_tunai_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Diskon Tunai (Rp) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
                <input type="number" name="diskon_tunai" id="diskon_tunai_create" min="0" step="any"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Diskon Tunai (Rp / Unit)">
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Potongan harga transaksi tunai.</p>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-1">
        <button type="submit" id="btnSubmitDetail" data-loading-text="Menyimpan Aturan..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Aturan Diskon</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formcreateDetail");
        const $min = $form.find("#min_qty_create");
        const $max = $form.find("#max_qty_create");
        const $diskon = $form.find("#diskon_create");
        const $diskonTunai = $form.find("#diskon_tunai_create");
        const $btnSubmit = $form.find("#btnSubmitDetail");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Helper untuk menandai input invalid
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $container.find('.field-error-msg').remove();
            $container.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        // Helper untuk membersihkan tanda error
        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        // Event listener saat user mengetik
        $min.add($max).add($diskon).add($diskonTunai).on('input change', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            const minVal = parseFloat($min.val());
            const maxVal = parseFloat($max.val());
            const diskonVal = $diskon.val().trim();
            const diskonTunaiVal = $diskonTunai.val().trim();

            let hasError = false;
            let firstInvalid = null;

            if (isNaN(minVal)) {
                setFieldError($min, "Min. Qty wajib diisi!");
                hasError = true;
                if (!firstInvalid) firstInvalid = $min;
            }

            if (isNaN(maxVal)) {
                setFieldError($max, "Max. Qty wajib diisi!");
                hasError = true;
                if (!firstInvalid) firstInvalid = $max;
            } else if (!isNaN(minVal) && minVal > maxVal) {
                setFieldError($max, "Max Qty tidak boleh lebih kecil dari Min Qty!");
                hasError = true;
                if (!firstInvalid) firstInvalid = $max;
            }

            if (diskonVal === "") {
                setFieldError($diskon, "Diskon Kredit wajib diisi!");
                hasError = true;
                if (!firstInvalid) firstInvalid = $diskon;
            }

            if (diskonTunaiVal === "") {
                setFieldError($diskonTunai, "Diskon Tunai wajib diisi!");
                hasError = true;
                if (!firstInvalid) firstInvalid = $diskonTunai;
            }

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                firstInvalid.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Menyimpan Aturan...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
