<form action="{{ route('penilaiankaryawan.createpenilaian') }}" method="POST" id="formPenilaianCreateModal" autocomplete="off" class="space-y-2.5">
    @csrf

    <!-- Tanggal -->
    <div>
        <label for="tanggal_pk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tanggal <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tanggal" id="tanggal_pk_create" placeholder="Pilih Tanggal" readonly
                class="flatpickr-date-pk w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
        </div>
    </div>

    <!-- Karyawan (Select2 V2) -->
    <div>
        <label for="nik_pk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Karyawan <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <select name="nik" id="nik_pk_create" class="select2NikPk w-full">
                <option value="">Pilih Karyawan</option>
                @foreach ($karyawan as $d)
                    <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- No. Kontrak -->
    <div>
        <label for="no_kontrak_pk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            No. Kontrak <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" name="no_kontrak" id="no_kontrak_pk_create" placeholder="Otomatis terisi saat pilih karyawan" readonly
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-semibold text-slate-700 dark:text-slate-200 focus:outline-none">
        </div>
    </div>

    <!-- Dari & Sampai -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div>
            <label for="dari_pk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kontrak Dari
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_pk_create" placeholder="-" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num text-slate-600 dark:text-slate-300">
            </div>
        </div>

        <div>
            <label for="sampai_pk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kontrak Sampai
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_pk_create" placeholder="-" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num text-slate-600 dark:text-slate-300">
            </div>
        </div>
    </div>

    <!-- Footer Buttons (Right-Aligned) -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" onclick="window.closeModalPenilaian && window.closeModalPenilaian()"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitCreatePk"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            <span>Lanjutkan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $('#formPenilaianCreateModal');
        const $tanggal = $form.find('#tanggal_pk_create');
        const $nik = $form.find('#nik_pk_create');
        const $noKontrak = $form.find('#no_kontrak_pk_create');
        const $dari = $form.find('#dari_pk_create');
        const $sampai = $form.find('#sampai_pk_create');

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

        $tanggal.on('input change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $nik.on('select2:select change', function() {
            const nik = $(this).val();
            if (nik) {
                clearFieldError($(this));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('kontrakkerja.getlastkontrak') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        nik: nik
                    },
                    success: function(response) {
                        if (response && response.data) {
                            $noKontrak.val(response.data.no_kontrak || '');
                            $dari.val(response.data.dari || '');
                            $sampai.val(response.data.sampai || '');
                            if (response.data.no_kontrak) {
                                clearFieldError($noKontrak);
                            }
                        }
                    }
                });
            } else {
                $noKontrak.val('');
                $dari.val('');
                $sampai.val('');
            }
        });

        $form.on('submit', function(e) {
            let firstInvalid = null;

            if (!$tanggal.val()) {
                setFieldError($tanggal, 'Tanggal wajib diisi.');
                firstInvalid = firstInvalid || $tanggal;
            }
            if (!$nik.val()) {
                setFieldError($nik, 'Karyawan wajib dipilih.');
                firstInvalid = firstInvalid || $nik;
            }
            if (!$noKontrak.val()) {
                setFieldError($noKontrak, 'No. Kontrak tidak ditemukan.');
                firstInvalid = firstInvalid || $noKontrak;
            }

            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return false;
            }

            const $btn = $('#btnSubmitCreatePk');
            $btn.prop('disabled', true)
                .addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html(`
                    <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memproses...</span>
                `);
        });
    });
</script>
