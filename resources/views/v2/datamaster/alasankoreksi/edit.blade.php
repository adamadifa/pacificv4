<form action="{{ route('alasankoreksi.update', $alasankoreksi->id) }}" id="formeditAlasankoreksi" method="POST" class="space-y-3">
    @csrf
    @method('PUT')

    <!-- Alasan Koreksi -->
    <div class="field-group">
        <label for="alasan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Alasan Koreksi <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" name="alasan" id="alasan_edit" value="{{ $alasankoreksi->alasan }}" autofocus maxlength="100"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Alasan Koreksi Presensi">
        </div>
    </div>

    <!-- Status Denda -->
    <div class="field-group">
        <label for="status_denda_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Status Denda <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
            </div>
            <select name="status_denda" id="status_denda_edit"
                class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                <option value="">Pilih Status Denda</option>
                <option value="1" {{ $alasankoreksi->status_denda == 1 ? 'selected' : '' }}>Ya (Kena Denda)</option>
                <option value="0" {{ $alasankoreksi->status_denda == 0 ? 'selected' : '' }}>Tidak (Bebas Denda)</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
            </div>
        </div>
        <p class="text-[10px] text-slate-400 mt-0.5 pl-1 helper-text">Tentukan apakah alasan ini memicu pemotongan denda presensi.</p>
    </div>

    <!-- Submit Button -->
    <div class="pt-1.5">
        <button type="submit" id="btnUpdateAlasankoreksi" data-loading-text="Memperbarui Alasan..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Perbarui Alasan Koreksi</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditAlasankoreksi");
        const $alasan = $form.find("#alasan_edit");
        const $statusDenda = $form.find("#status_denda_edit");
        const $btnSubmit = $form.find("#btnUpdateAlasankoreksi");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Helper untuk menandai input invalid
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $container.find('.helper-text').addClass('hidden');
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
            $container.find('.helper-text').removeClass('hidden');
            $container.find('.field-error-msg').remove();
        }

        // Event listener saat user mengetik
        $alasan.add($statusDenda).on('input change', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            const alasanVal = $alasan.val().trim();
            const statusVal = $statusDenda.val();

            let hasError = false;
            let firstInvalid = null;

            if (!alasanVal) {
                setFieldError($alasan, "Alasan koreksi wajib diisi!");
                hasError = true;
                if (!firstInvalid) firstInvalid = $alasan;
            }

            if (!statusVal) {
                setFieldError($statusDenda, "Status denda wajib dipilih!");
                hasError = true;
                if (!firstInvalid) firstInvalid = $statusDenda;
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
                window.setButtonSubmitting($btnSubmit, 'Memperbarui Alasan...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>