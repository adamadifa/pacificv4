<form action="{{ route('izinkoreksi.store') }}" method="POST" id="formIzinKoreksiCreate" autocomplete="off" class="space-y-2.5">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kode Izin Koreksi</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="kode_izin" id="kode_izin_create" value="Auto" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-semibold text-slate-500 dark:text-slate-400 cursor-not-allowed">
            </div>
        </div>

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
                class="select2NikKoreksiCreate w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                <option value="">Pilih Karyawan</option>
                @foreach ($karyawan as $d)
                    <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="kode_jadwal_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jadwal
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                </div>
                <select name="kode_jadwal" id="kode_jadwal_create"
                    class="select2KodejadwalCreate w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                    <option value="">Pilih Jadwal</option>
                </select>
            </div>
        </div>

        <div>
            <label for="kode_jam_kerja_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jam Kerja
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <select name="kode_jam_kerja" id="kode_jam_kerja_create"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Jam Kerja</option>
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="jam_masuk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jam Masuk <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jam_masuk" id="jam_masuk_create" placeholder="HH:mm"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
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
        <label for="id_alasan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Alasan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
            </div>
            <select name="id_alasan" id="id_alasan_create"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <option value="">Pilih Alasan</option>
                @foreach ($alasan_koreksi as $a)
                    <option value="{{ $a->id }}">{{ $a->alasan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="keterangan_create_wrap" class="hidden">
        <label for="keterangan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <textarea name="keterangan" id="keterangan_create" rows="2" placeholder="Masukkan keterangan koreksi..."
                class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalIzinKoreksi && window.closeModalIzinKoreksi()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanIzinKoreksiCreate"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formIzinKoreksiCreate');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d', allowInput: false });
        if ($.fn.mask) $form.find('#jam_masuk_create, #jam_pulang_create').mask('00:00');
        if ($.fn.select2) {
            $form.find('.select2NikKoreksiCreate').select2({ placeholder: 'Pilih Karyawan', allowClear: true, width: '100%' });
            $form.find('.select2KodejadwalCreate').select2({ placeholder: 'Pilih Jadwal', allowClear: true, width: '100%' });
        }

        function getpresensi() {
            const nik = $form.find('#nik_create').val();
            const tanggal = $form.find('#tanggal_create').val();
            if (!nik || !tanggal) return;
            $.ajax({
                url: "{{ route('izinkoreksi.getpresensi') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", nik: nik, tanggal: tanggal },
                success: function(respond) {
                    $form.find('#jam_masuk_create').val(respond.jam_in);
                    $form.find('#jam_pulang_create').val(respond.jam_out);
                }
            });
        }

        $form.find('#nik_create, #tanggal_create').on('change', getpresensi);

        $form.find('#id_alasan_create').on('change', function() {
            const alasanText = $(this).find('option:selected').text().toLowerCase();
            if (alasanText.includes('lainnya')) {
                $form.find('#keterangan_create_wrap').removeClass('hidden');
            } else {
                $form.find('#keterangan_create_wrap').addClass('hidden');
                $form.find('#keterangan_create').val('');
            }
        });

        $form.find('#kode_jadwal_create').load('/getjadwalkerja/0');
        $form.find('#kode_jadwal_create').on('change', function() {
            const kode_jadwal = $(this).val();
            $form.find('#kode_jam_kerja_create').load('/getjamkerja/' + kode_jadwal + '/0');
        });

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
            const $jam_masuk = $form.find('#jam_masuk_create');
            const $jam_pulang = $form.find('#jam_pulang_create');
            const $id_alasan = $form.find('#id_alasan_create');
            const $ketWrap = $form.find('#keterangan_create_wrap');
            const $ket = $form.find('#keterangan_create');
            let firstError = null;

            if (!$nik.val()) { showError($nik, 'Karyawan wajib dipilih.'); if (!firstError) firstError = $nik; }
            if (!$tanggal.val()) { showError($tanggal, 'Tanggal wajib diisi.'); if (!firstError) firstError = $tanggal; }
            if (!$jam_masuk.val()) { showError($jam_masuk, 'Jam masuk wajib diisi.'); if (!firstError) firstError = $jam_masuk; }
            if (!$jam_pulang.val()) { showError($jam_pulang, 'Jam pulang wajib diisi.'); if (!firstError) firstError = $jam_pulang; }
            if (!$id_alasan.val()) { showError($id_alasan, 'Alasan wajib dipilih.'); if (!firstError) firstError = $id_alasan; }
            if (!$ketWrap.hasClass('hidden') && !$.trim($ket.val())) { showError($ket, 'Keterangan wajib diisi.'); if (!firstError) firstError = $ket; }

            if (firstError) { e.preventDefault(); firstError.focus(); return false; }

            const $btn = $form.find('#btnSimpanIzinKoreksiCreate');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Menyimpan...</span>`);
        });
    });
</script>
