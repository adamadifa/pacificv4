<form action="{{ route('resign.store') }}" method="POST" id="formResignCreate" autocomplete="off" class="space-y-2.5">
    @csrf

    <!-- Kode Resign -->
    <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kode Resign</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_resign" value="Auto" disabled
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-semibold text-slate-500">
        </div>
    </div>

    <!-- Tanggal -->
    <div>
        <label for="tanggal_resign_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tanggal <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tanggal" id="tanggal_resign_create" placeholder="Pilih Tanggal" readonly
                class="flatpickr-date-resign w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
        </div>
    </div>

    <!-- Karyawan (Select2 V2) -->
    <div>
        <label for="nik_resign_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Karyawan <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <select name="nik" id="nik_resign_create" class="select2NikResignCreate w-full">
                <option value="">Pilih Karyawan</option>
                @foreach ($karyawan as $d)
                    <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Kategori JMK -->
    <div>
        <label for="kode_kategori_resign_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kategori JMK <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="tag" class="w-4 h-4"></i>
            </div>
            <select name="kode_kategori" id="kode_kategori_resign_create"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <option value="">Pilih Kategori JMK</option>
                @foreach ($kategori_jmk as $d)
                    <option value="{{ $d->kode_kategori }}">{{ $d->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Keterangan -->
    <div>
        <label for="keterangan_resign_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" name="keterangan" id="keterangan_resign_create" placeholder="Keterangan pengunduran diri"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>
    </div>

    <!-- Piutang Checkboxes -->
    <div class="pt-1">
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">Tanggungan Piutang</label>
        <div class="flex flex-wrap gap-3">
            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-200">
                <input class="w-4 h-4 rounded border-slate-300 text-[#284c9a]" type="checkbox" name="pjp" id="pjp_create" value="1">
                <span>PJP</span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-200">
                <input class="w-4 h-4 rounded border-slate-300 text-[#284c9a]" type="checkbox" name="kasbon" id="kasbon_create" value="1">
                <span>Kasbon</span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-200">
                <input class="w-4 h-4 rounded border-slate-300 text-[#284c9a]" type="checkbox" name="piutang_lainnya" id="piutang_lainnya_create" value="1">
                <span>Piutang Lainnya</span>
            </label>
        </div>
    </div>

    <!-- Footer Buttons (Right-Aligned) -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" onclick="window.closeModalResign && window.closeModalResign()"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanResign"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $('#formResignCreate');
        const $tanggal = $form.find('#tanggal_resign_create');
        const $nik = $form.find('#nik_resign_create');
        const $kategori = $form.find('#kode_kategori_resign_create');
        const $keterangan = $form.find('#keterangan_resign_create');

        $tanggal.flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        $nik.select2({
            placeholder: 'Pilih Karyawan',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function setFieldError($el, msg) {
            const $col = $el.closest('div').parent();
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            }
            $el.closest('.relative').find('.prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $col.find('.field-error-msg').remove();
            $col.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        function clearFieldError($el) {
            const $col = $el.closest('div').parent();
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            }
            $el.closest('.relative').find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $col.find('.field-error-msg').remove();
        }

        $tanggal.add($kategori).add($keterangan).on('input change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $nik.on('select2:select change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $form.on('submit', function(e) {
            let firstInvalid = null;

            if (!$tanggal.val()) { setFieldError($tanggal, 'Tanggal wajib diisi.'); firstInvalid = firstInvalid || $tanggal; }
            if (!$nik.val()) { setFieldError($nik, 'Karyawan wajib dipilih.'); firstInvalid = firstInvalid || $nik; }
            if (!$kategori.val()) { setFieldError($kategori, 'Kategori JMK wajib dipilih.'); firstInvalid = firstInvalid || $kategori; }
            if (!$keterangan.val()) { setFieldError($keterangan, 'Keterangan wajib diisi.'); firstInvalid = firstInvalid || $keterangan; }

            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return false;
            }

            const $btn = $('#btnSimpanResign');
            $btn.prop('disabled', true)
                .addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html(`
                    <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Menyimpan...</span>
                `);
        });
    });
</script>
