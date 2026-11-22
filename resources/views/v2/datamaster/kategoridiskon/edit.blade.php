<form action="{{ route('kategoridiskon.update', Crypt::encrypt($kategoridiskon->kode_kategori_diskon)) }}" id="formeditKategoridiskon" method="POST" class="space-y-2.5">
    @method('PUT')
    @csrf

    <!-- Input Kode Kategori Diskon (Readonly) -->
    <div class="field-group">
        <label for="kode_kategori_diskon_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kode Kategori Diskon
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="percent" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_kategori_diskon" id="kode_kategori_diskon_edit" value="{{ $kategoridiskon->kode_kategori_diskon }}" readonly
                class="w-full h-[36px] pl-9 pr-3 text-xs font-bold uppercase rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 cursor-not-allowed"
                placeholder="Kode Kategori Diskon">
        </div>
        <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Kode kategori diskon bersifat permanen.</p>
    </div>

    <!-- Input Nama Kategori Diskon -->
    <div class="field-group">
        <label for="nama_kategori_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Kategori Diskon <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="tag" class="w-4 h-4"></i>
            </div>
            <input type="text" name="nama_kategori" id="nama_kategori_edit" value="{{ $kategoridiskon->nama_kategori }}" maxlength="50" autofocus
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Nama Kategori Diskon">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-1.5">
        <button type="submit" id="btnUpdateKategoridiskon" data-loading-text="Memperbarui Kategori..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Perbarui Kategori Diskon</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditKategoridiskon");
        const $nama = $form.find("#nama_kategori_edit");
        const $btnSubmit = $form.find("#btnUpdateKategoridiskon");

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

        // Event listener saat user memperbaiki isi input
        $nama.on('input change', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            const nama = $nama.val().trim();

            if (!nama) {
                e.preventDefault();
                setFieldError($nama, "Nama Kategori Diskon wajib diisi!");
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                $nama.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Memperbarui Kategori...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
