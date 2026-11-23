<form action="{{ route('coa.update', Crypt::encrypt($coa->kode_akun)) }}" method="POST" id="formCoa" class="space-y-3">
    @csrf
    @method('PUT')

    <!-- Field 1: Kode Akun -->
    <div class="field-group">
        <label for="kode_akun_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kode Akun <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="hash" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_akun" id="kode_akun_edit" value="{{ $coa->kode_akun }}" maxlength="20" autofocus autocomplete="off"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-mono font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Contoh: 1-1105">
        </div>
        <p class="text-[10px] text-slate-400 mt-0.5 pl-1 helper-text">Masukkan format kode akun (misal: 1-1105 atau 5-1102).</p>
    </div>

    <!-- Field 2: Nama Akun -->
    <div class="field-group">
        <label for="nama_akun_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Akun <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" name="nama_akun" id="nama_akun_edit" value="{{ $coa->nama_akun }}" maxlength="100" autocomplete="off"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Contoh: KAS KECIL PUSAT">
        </div>
    </div>

    <!-- Field 3: Parent Account (Sub Akun) -->
    <div class="field-group">
        <label for="sub_akun_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Parent Account (Sub Dari Akun) <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                <i data-lucide="folder-tree" class="w-4 h-4"></i>
            </div>
            <select name="sub_akun" id="sub_akun_edit" class="select2-parent-account-edit w-full">
                <option value="">-- Pilih Parent Account --</option>
                <option value="0" @selected($coa->sub_akun === '0' || $coa->sub_akun === null)>0 - AKUN UTAMA / HEADER (Level 0)</option>
                @foreach ($sub_akun as $d)
                    <option value="{{ $d->kode_akun }}" @selected($coa->sub_akun == $d->kode_akun)>{{ $d->kode_akun }} - {{ $d->nama_akun }} (Lvl {{ $d->level ?? 0 }})</option>
                @endforeach
            </select>
        </div>
        <p class="text-[10px] text-slate-400 mt-0.5 pl-1 helper-text">Pilih induk akun perkiraan untuk menyusun hirarki level COA.</p>
    </div>

    <!-- Field 4: Kategori Akun -->
    <div class="field-group">
        <label for="kode_kategori_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kategori Akun <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                <i data-lucide="tag" class="w-4 h-4"></i>
            </div>
            <select name="kode_kategori" id="kode_kategori_edit" class="select2-kategori-account-edit w-full">
                <option value="">-- Pilih Kategori Akun --</option>
                @foreach ($kategori as $d)
                    <option value="{{ $d->kode_kategori }}" @selected($coa->kode_kategori == $d->kode_kategori)>{{ $d->kode_kategori }} - {{ $d->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSubmitCoa"
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Perbarui Akun COA</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formCoa");
        const $kode = $form.find("#kode_akun_edit");
        const $nama = $form.find("#nama_akun_edit");
        const $subAkun = $form.find("#sub_akun_edit");
        const $kategori = $form.find("#kode_kategori_edit");
        const $btnSubmit = $form.find("#btnSubmitCoa");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Initialize Select2 with V2 standards
        if ($.fn.select2) {
            $subAkun.select2({
                placeholder: '-- Pilih Parent Account --',
                allowClear: true,
                width: '100%'
            });

            $kategori.select2({
                placeholder: '-- Pilih Kategori Akun --',
                allowClear: true,
                width: '100%'
            });
        }

        // Optional mask if available
        if ($.fn.mask) {
            $kode.mask('0-0000');
        }

        // Helper untuk menandai input invalid
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('!border-rose-500 !ring-1 !ring-rose-500')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('!text-rose-500').removeClass('text-slate-400');
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

            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
        }

        // Helper untuk membersihkan tanda error
        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('!border-rose-500 !ring-1 !ring-rose-500')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('!text-rose-500').addClass('text-slate-400');
            $container.find('.helper-text').removeClass('hidden');
            $container.find('.field-error-msg').remove();

            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
        }

        // Live cleanup on typing or changing
        $kode.add($nama).on('input change', function() {
            clearFieldError($(this));
        });

        $subAkun.on('change select2:select', function() {
            clearFieldError($(this));
        });

        $kategori.on('change select2:select', function() {
            clearFieldError($(this));
        });

        // Form submit validation
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstErrorField = null;

            const kode = $kode.val().trim();
            const nama = $nama.val().trim();
            const subAkun = $subAkun.val();
            const kategoriVal = $kategori.val();

            if (!kode) {
                setFieldError($kode, 'Kode Akun wajib diisi!');
                if (!hasError) $firstErrorField = $kode;
                hasError = true;
            }

            if (!nama) {
                setFieldError($nama, 'Nama Akun wajib diisi!');
                if (!hasError) $firstErrorField = $nama;
                hasError = true;
            }

            if (subAkun === "" || subAkun === null || subAkun === undefined) {
                setFieldError($subAkun, 'Parent Account wajib dipilih!');
                if (!hasError) $firstErrorField = $subAkun;
                hasError = true;
            }

            if (!kategoriVal) {
                setFieldError($kategori, 'Kategori Akun wajib dipilih!');
                if (!hasError) $firstErrorField = $kategori;
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    if ($firstErrorField.hasClass('select2-hidden-accessible')) {
                        $firstErrorField.select2('open');
                    } else {
                        $firstErrorField.focus();
                    }
                }
                return false;
            }

            // Disable button and show spinner
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memperbarui...</span>
            `);
        });
    });
</script>
