<form action="{{ route('izinpulang.store') }}" method="POST" id="formIzinPulangCreate" autocomplete="off" class="space-y-2.5">
    @csrf

    <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kode Izin Pulang</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_izin" id="kode_izin_create" value="Auto" disabled
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-semibold text-slate-500 dark:text-slate-400 cursor-not-allowed">
        </div>
    </div>

    <div>
        <label for="nik_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Karyawan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <select name="nik" id="nik_create"
                class="select2NikPulangCreate w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                <option value="">Pilih Karyawan</option>
                @foreach ($karyawan as $d)
                    <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="tanggal_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_create" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>

        <div>
            <label for="jam_pulang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jam Pulang <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jam_pulang" id="jam_pulang_create" placeholder="HH:mm"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>
    </div>

    <div>
        <label for="keterangan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <textarea name="keterangan" id="keterangan_create" rows="2" placeholder="Masukkan keterangan izin pulang..."
                class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalIzinPulang && window.closeModalIzinPulang()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanIzinPulangCreate"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formIzinPulangCreate');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d', allowInput: false });
        if ($.fn.mask) $form.find('#jam_pulang_create').mask('00:00');
        if ($.fn.select2) $form.find('.select2NikPulangCreate').select2({ placeholder: 'Pilih Karyawan', allowClear: true, width: '100%' });

        function clearError($el) {
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $rel.find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $rel.siblings('.field-error-msg').remove();
        }

        function showError($el, msg) {
            clearError($el);
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $rel.find('.prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $rel.after(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        $form.find('input, select, textarea').on('input change select2:select', function() { clearError($(this)); });

        $form.on('submit', function(e) {
            const $nik = $form.find('#nik_create');
            const $tanggal = $form.find('#tanggal_create');
            const $jam = $form.find('#jam_pulang_create');
            const $ket = $form.find('#keterangan_create');
            let firstError = null;

            if (!$nik.val()) { showError($nik, 'Karyawan wajib dipilih.'); if (!firstError) firstError = $nik; }
            if (!$tanggal.val()) { showError($tanggal, 'Tanggal wajib diisi.'); if (!firstError) firstError = $tanggal; }
            if (!$jam.val()) { showError($jam, 'Jam pulang wajib diisi.'); if (!firstError) firstError = $jam; }
            if (!$.trim($ket.val())) { showError($ket, 'Keterangan wajib diisi.'); if (!firstError) firstError = $ket; }

            if (firstError) { e.preventDefault(); firstError.focus(); return false; }

            const $btn = $form.find('#btnSimpanIzinPulangCreate');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Menyimpan...</span>`);
        });
    });
</script>
