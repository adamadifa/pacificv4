<form action="{{ route('jurnalumum.update', Crypt::encrypt($jurnalumum->kode_ju)) }}" id="formJurnalumumEdit" method="POST" autocomplete="off" class="space-y-2.5">
    @csrf
    @method('PUT')

    <!-- Tanggal -->
    <div>
        <label for="tanggal_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tanggal <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tanggal" id="tanggal_edit" value="{{ $jurnalumum->tanggal }}" placeholder="Pilih Tanggal" readonly
                class="flatpickr-date-edit w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
        </div>
    </div>

    <!-- Akun (Select2 V2) -->
    <div>
        <label for="kode_akun_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Akun <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <select name="kode_akun" id="kode_akun_edit" class="select2KodeakunEdit w-full">
                <option value="">Pilih Akun</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}" @selected($jurnalumum->kode_akun == $d->kode_akun)>
                        {{ $d->kode_akun }} - {{ $d->nama_akun }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Keterangan -->
    <div>
        <label for="keterangan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" name="keterangan" id="keterangan_edit" value="{{ $jurnalumum->keterangan }}" placeholder="Keterangan jurnal"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>
    </div>

    <!-- Jumlah -->
    <div>
        <label for="jumlah_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Jumlah <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <input type="text" name="jumlah" id="jumlah_edit" value="{{ formatAngka($jurnalumum->jumlah) }}" placeholder="0"
                class="number-separator-edit w-full h-[36px] pl-9 pr-3 text-right font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Debet / Kredit -->
        <div>
            <label for="debet_kredit_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Debet / Kredit <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                </div>
                <select name="debet_kredit" id="debet_kredit_edit"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Debet / Kredit</option>
                    <option value="D" @selected($jurnalumum->debet_kredit == 'D')>Debet</option>
                    <option value="K" @selected($jurnalumum->debet_kredit == 'K')>Kredit</option>
                </select>
            </div>
        </div>

        <!-- Peruntukan -->
        <div>
            <label for="kode_peruntukan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Peruntukan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select name="kode_peruntukan" id="kode_peruntukan_edit"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Peruntukan</option>
                    <option value="MP" @selected($jurnalumum->kode_peruntukan == 'MP')>MP</option>
                    <option value="PC" @selected($jurnalumum->kode_peruntukan == 'PC')>PACIFIC</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Cabang (hanya tampil jika PC) -->
    <div id="cabang_wrap_edit" style="display: none;">
        <label for="kode_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Cabang <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="building" class="w-4 h-4"></i>
            </div>
            <select name="kode_cabang" id="kode_cabang_edit" class="select2KodecabangEdit w-full">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}" @selected($jurnalumum->kode_cabang == $d->kode_cabang)>
                        {{ textUpperCase($d->nama_cabang) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Footer Buttons (Right-Aligned) -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" onclick="window.closeModalJurnalUmum && window.closeModalJurnalUmum()"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanJurnalUmumEdit"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $('#formJurnalumumEdit');
        const $tanggal = $form.find('#tanggal_edit');
        const $kodeAkun = $form.find('#kode_akun_edit');
        const $keterangan = $form.find('#keterangan_edit');
        const $jumlah = $form.find('#jumlah_edit');
        const $debetKredit = $form.find('#debet_kredit_edit');
        const $peruntukan = $form.find('#kode_peruntukan_edit');
        const $cabang = $form.find('#kode_cabang_edit');
        const $cabangWrap = $form.find('#cabang_wrap_edit');

        $tanggal.flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        easyNumberSeparator({
            selector: '.number-separator-edit',
            separator: '.',
            decimalSeparator: ',',
        });

        $kodeAkun.select2({
            placeholder: 'Pilih Akun',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        $cabang.select2({
            placeholder: 'Pilih Cabang',
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

        function loadkodecabang() {
            if ($peruntukan.val() === 'PC') {
                $cabangWrap.show();
            } else {
                $cabangWrap.hide();
                clearFieldError($cabang);
            }
        }

        loadkodecabang();
        $peruntukan.on('change', loadkodecabang);

        $tanggal.add($keterangan).add($jumlah).add($debetKredit).add($peruntukan).on('input change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $kodeAkun.add($cabang).on('select2:select change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $form.on('submit', function(e) {
            let firstInvalid = null;

            if (!$tanggal.val()) { setFieldError($tanggal, 'Tanggal wajib diisi.'); firstInvalid = firstInvalid || $tanggal; }
            if (!$kodeAkun.val()) { setFieldError($kodeAkun, 'Akun wajib dipilih.'); firstInvalid = firstInvalid || $kodeAkun; }
            if (!$keterangan.val()) { setFieldError($keterangan, 'Keterangan wajib diisi.'); firstInvalid = firstInvalid || $keterangan; }
            if (!$jumlah.val()) { setFieldError($jumlah, 'Jumlah wajib diisi.'); firstInvalid = firstInvalid || $jumlah; }
            if (!$debetKredit.val()) { setFieldError($debetKredit, 'Debet/Kredit wajib dipilih.'); firstInvalid = firstInvalid || $debetKredit; }
            if (!$peruntukan.val()) { setFieldError($peruntukan, 'Peruntukan wajib dipilih.'); firstInvalid = firstInvalid || $peruntukan; }
            if ($peruntukan.val() === 'PC' && !$cabang.val()) { setFieldError($cabang, 'Cabang wajib dipilih.'); firstInvalid = firstInvalid || $cabang; }

            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return false;
            }

            const $btn = $('#btnSimpanJurnalUmumEdit');
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
