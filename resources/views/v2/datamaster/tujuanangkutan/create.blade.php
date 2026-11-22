<form action="{{ route('tujuanangkutan.store') }}" id="formcreateTujuanangkutan" method="POST" class="space-y-2.5">
    @csrf

    <!-- Kode Tujuan -->
    <div class="field-group">
        <label for="kode_tujuan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kode Tujuan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_tujuan" id="kode_tujuan_create"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold uppercase font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Misal: TJ001">
        </div>
    </div>

    <!-- Nama Tujuan -->
    <div class="field-group">
        <label for="tujuan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Tujuan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tujuan" id="tujuan_create"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Misal: BANDUNG - CIMAHI">
        </div>
    </div>

    <!-- Tarif (Rp) -->
    <div class="field-group">
        <label for="tarif_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tarif Ongkos Angkut (Rp) <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="receipt" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tarif" id="tarif_create"
                class="money w-full h-[36px] pl-9 pr-3 text-xs font-semibold font-mono-num text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="0">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSubmitTujuan" data-loading-text="Menyimpan Data..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Tujuan Angkutan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formcreateTujuanangkutan");
        const $kode = $form.find("#kode_tujuan_create");
        const $tujuan = $form.find("#tujuan_create");
        const $tarif = $form.find("#tarif_create");
        const $btnSubmit = $form.find("#btnSubmitTujuan");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        if ($.fn.maskMoney) {
            $tarif.maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        // Helper invalid
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

        // Helper clean
        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        $form.find('input').on('input change', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;

            if (!$kode.val() || !$kode.val().toString().trim()) {
                setFieldError($kode, "Kode tujuan wajib diisi!");
                hasError = true;
                if (!$firstError) $firstError = $kode;
            }

            if (!$tujuan.val() || !$tujuan.val().toString().trim()) {
                setFieldError($tujuan, "Nama tujuan wajib diisi!");
                hasError = true;
                if (!$firstError) $firstError = $tujuan;
            }

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                if ($firstError) $firstError.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Menyimpan Data...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
