<form action="{{ route('wilayah.update', Crypt::encrypt($wilayah->kode_wilayah)) }}" id="formeditWilayah" method="POST" class="space-y-2.5">
    @method('PUT')
    @csrf

    <!-- Input Kode Wilayah (Readonly) -->
    <div class="field-group">
        <label for="kode_wilayah_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kode Wilayah
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="hash" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_wilayah" id="kode_wilayah_edit" value="{{ $wilayah->kode_wilayah }}" readonly
                class="w-full h-[36px] pl-9 pr-3 text-xs font-bold uppercase rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 cursor-not-allowed"
                placeholder="Kode Wilayah">
        </div>
        <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Kode wilayah bersifat permanen.</p>
    </div>

    <!-- Input Nama Wilayah -->
    <div class="field-group">
        <label for="nama_wilayah_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Wilayah / Rute <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
            </div>
            <input type="text" name="nama_wilayah" id="nama_wilayah_edit" value="{{ $wilayah->nama_wilayah }}" maxlength="30" autofocus
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Nama Wilayah / Rute">
        </div>
    </div>

    <!-- Select Cabang (jika user punya hak pilih cabang) -->
    @hasanyrole($roles_show_cabang)
        <div class="field-group">
            <label for="kode_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_edit"
                    class="select2Cabang w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ $wilayah->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                            {{ $c->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endhasanyrole

    <!-- Submit Button -->
    <div class="pt-1.5">
        <button type="submit" id="btnUpdateWilayah" data-loading-text="Memperbarui Wilayah..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Perbarui Wilayah</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditWilayah");
        const $nama = $form.find("#nama_wilayah_edit");
        const $cabang = $form.find("#kode_cabang_edit");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Init Select2 Cabang
        if ($.fn.select2) {
            $form.find(".select2Cabang").each(function() {
                var $this = $(this);
                $this.select2({
                    placeholder: "Pilih Cabang",
                    allowClear: true,
                    width: '100%'
                });
            });
        }

        // Helper untuk menandai input invalid
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.select2-selection--single').addClass('!border-rose-500 !ring-1 !ring-rose-500');
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
            $container.find('.select2-selection--single').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        // Event listener saat user memperbaiki isi input
        $nama.add($cabang).on('input change select2:select select2:clear', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            const nama = $nama.val().trim();
            let hasError = false;
            let firstInvalid = null;

            if (!nama) {
                setFieldError($nama, "Nama Wilayah wajib diisi!");
                hasError = true;
                if (!firstInvalid) firstInvalid = $nama;
            }

            if ($cabang.length && !$cabang.val()) {
                setFieldError($cabang, "Cabang wajib dipilih!");
                hasError = true;
                if (!firstInvalid) firstInvalid = $cabang;
            }

            if (hasError) {
                e.preventDefault();
                firstInvalid.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($form.find("#btnUpdateWilayah"), 'Memperbarui Wilayah...');
            } else {
                $form.find("#btnUpdateWilayah").prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
