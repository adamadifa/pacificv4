<form action="{{ route('izinkeluar.storeupdatejamkembali', Crypt::encrypt($izinkeluar->kode_izin_keluar)) }}" method="POST" id="formUpdateJamKembali" autocomplete="off" class="space-y-3">
    @csrf

    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-3.5 divide-y divide-dashed divide-slate-200 dark:divide-slate-700 text-xs">
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Kode</span>
            <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $izinkeluar->kode_izin_keluar }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Tanggal</span>
            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ DateToIndo($izinkeluar->tanggal) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">NIK</span>
            <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $izinkeluar->nik }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Nama Karyawan</span>
            <span class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ $izinkeluar->nama_karyawan }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Jabatan</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $izinkeluar->nama_jabatan }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Departemen</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $izinkeluar->nama_dept }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Cabang</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ textUpperCase($izinkeluar->nama_cabang) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Jam Keluar</span>
            <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ date('H:i', strtotime($izinkeluar->jam_keluar)) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Keperluan</span>
            <span>
                @if ($izinkeluar->keperluan == 'K')
                    <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-bold">Kantor</span>
                @else
                    <span class="px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10.5px] font-bold">Pribadi</span>
                @endif
            </span>
        </div>
        <div class="py-1.5 flex items-start justify-between gap-2">
            <span class="text-slate-400 shrink-0">Keterangan</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $izinkeluar->keterangan }}</span>
        </div>
    </div>

    <div>
        <label for="jam_kembali_update" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Jam Kembali <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="clock" class="w-4 h-4"></i>
            </div>
            <input type="text" name="jam_kembali" id="jam_kembali_update"
                value="{{ !empty($izinkeluar->jam_kembali) ? $izinkeluar->jam_kembali : '' }}"
                placeholder="Pilih Tanggal & Jam Kembali" readonly
                class="flatpickr-datetime w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalIzinKeluar && window.closeModalIzinKeluar()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanJamKembali"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Update</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formUpdateJamKembali');
        $form.find('.flatpickr-datetime').flatpickr({
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            allowInput: false
        });

        function clearError($el) {
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $rel.siblings('.field-error-msg').remove();
        }

        function showError($el, msg) {
            clearError($el);
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $rel.after(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        $form.find('input').on('input change', function() { clearError($(this)); });

        $form.on('submit', function(e) {
            const $jam = $form.find('#jam_kembali_update');
            if (!$jam.val()) {
                e.preventDefault();
                showError($jam, 'Jam kembali wajib diisi.');
                $jam.focus();
                return false;
            }
            const $btn = $form.find('#btnSimpanJamKembali');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Memperbarui...</span>`);
        });
    });
</script>
