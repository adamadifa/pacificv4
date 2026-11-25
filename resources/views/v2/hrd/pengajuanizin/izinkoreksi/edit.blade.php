<form action="{{ route('izinkoreksi.update', Crypt::encrypt($izinkoreksi->kode_izin_koreksi)) }}" method="POST" id="formIzinKoreksiEdit" autocomplete="off" class="space-y-2.5">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kode Izin Koreksi</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="kode_izin" id="kode_izin_edit" value="{{ $izinkoreksi->kode_izin_koreksi }}" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-bold text-[#284c9a] dark:text-blue-400 cursor-not-allowed">
            </div>
        </div>

        <div>
            <label for="tanggal_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_edit" value="{{ $izinkoreksi->tanggal }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>
    </div>

    <div>
        <label for="nik_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Karyawan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <select name="nik" id="nik_edit"
                class="select2NikKoreksiEdit w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                <option value="">Pilih Karyawan</option>
                @foreach ($karyawan as $d)
                    <option value="{{ $d->nik }}" @selected($izinkoreksi->nik == $d->nik)>{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="kode_jadwal_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jadwal
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                </div>
                <select name="kode_jadwal" id="kode_jadwal_edit"
                    class="select2KodejadwalEdit w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                    <option value="">Pilih Jadwal</option>
                </select>
            </div>
        </div>

        <div>
            <label for="kode_jam_kerja_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jam Kerja
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <select name="kode_jam_kerja" id="kode_jam_kerja_edit"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Jam Kerja</option>
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="jam_masuk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jam Masuk <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jam_masuk" id="jam_masuk_edit" value="{{ date('H:i', strtotime($izinkoreksi->jam_masuk)) }}" placeholder="HH:mm"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>

        <div>
            <label for="jam_pulang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jam Pulang <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jam_pulang" id="jam_pulang_edit" value="{{ date('H:i', strtotime($izinkoreksi->jam_pulang)) }}" placeholder="HH:mm"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>
    </div>

    <div>
        <label for="id_alasan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Alasan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
            </div>
            <select name="id_alasan" id="id_alasan_edit"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <option value="">Pilih Alasan</option>
                @foreach ($alasan_koreksi as $a)
                    <option value="{{ $a->id }}" @selected($izinkoreksi->id_alasan == $a->id)>{{ $a->alasan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="keterangan_edit_wrap" class="hidden">
        <label for="keterangan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <textarea name="keterangan" id="keterangan_edit" rows="2" placeholder="Masukkan keterangan koreksi..."
                class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">{{ $izinkoreksi->keterangan }}</textarea>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalIzinKoreksi && window.closeModalIzinKoreksi()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanIzinKoreksiEdit"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Update</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formIzinKoreksiEdit');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d', allowInput: false });
        if ($.fn.mask) $form.find('#jam_masuk_edit, #jam_pulang_edit').mask('00:00');
        if ($.fn.select2) {
            $form.find('.select2NikKoreksiEdit').select2({ placeholder: 'Pilih Karyawan', allowClear: true, width: '100%' });
            $form.find('.select2KodejadwalEdit').select2({ placeholder: 'Pilih Jadwal', allowClear: true, width: '100%' });
        }

        function getpresensi() {
            const nik = $form.find('#nik_edit').val();
            const tanggal = $form.find('#tanggal_edit').val();
            if (!nik || !tanggal) return;
            $.ajax({
                url: "{{ route('izinkoreksi.getpresensi') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", nik: nik, tanggal: tanggal },
                success: function(respond) {
                    $form.find('#jam_masuk_edit').val(respond.jam_in);
                    $form.find('#jam_pulang_edit').val(respond.jam_out);
                }
            });
        }

        $form.find('#nik_edit, #tanggal_edit').on('change', getpresensi);

        function toggleKeterangan() {
            const alasanText = $form.find('#id_alasan_edit option:selected').text().toLowerCase();
            if (alasanText.includes('lainnya')) {
                $form.find('#keterangan_edit_wrap').removeClass('hidden');
            } else {
                $form.find('#keterangan_edit_wrap').addClass('hidden');
            }
        }
        toggleKeterangan();
        $form.find('#id_alasan_edit').on('change', function() {
            toggleKeterangan();
            if ($form.find('#keterangan_edit_wrap').hasClass('hidden')) {
                $form.find('#keterangan_edit').val('');
            }
        });

        $form.find('#kode_jadwal_edit').load('/getjadwalkerja/{{ $izinkoreksi->kode_jadwal }}');
        $form.find('#kode_jam_kerja_edit').load('/getjamkerja/{{ $izinkoreksi->kode_jadwal }}/{{ $izinkoreksi->kode_jam_kerja }}');
        $form.find('#kode_jadwal_edit').on('change', function() {
            $form.find('#kode_jam_kerja_edit').load('/getjamkerja/' + $(this).val() + '/0');
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
            const $nik = $form.find('#nik_edit');
            const $tanggal = $form.find('#tanggal_edit');
            const $jam_masuk = $form.find('#jam_masuk_edit');
            const $jam_pulang = $form.find('#jam_pulang_edit');
            const $id_alasan = $form.find('#id_alasan_edit');
            const $ketWrap = $form.find('#keterangan_edit_wrap');
            const $ket = $form.find('#keterangan_edit');
            let firstError = null;

            if (!$nik.val()) { showError($nik, 'Karyawan wajib dipilih.'); if (!firstError) firstError = $nik; }
            if (!$tanggal.val()) { showError($tanggal, 'Tanggal wajib diisi.'); if (!firstError) firstError = $tanggal; }
            if (!$jam_masuk.val()) { showError($jam_masuk, 'Jam masuk wajib diisi.'); if (!firstError) firstError = $jam_masuk; }
            if (!$jam_pulang.val()) { showError($jam_pulang, 'Jam pulang wajib diisi.'); if (!firstError) firstError = $jam_pulang; }
            if (!$id_alasan.val()) { showError($id_alasan, 'Alasan wajib dipilih.'); if (!firstError) firstError = $id_alasan; }
            if (!$ketWrap.hasClass('hidden') && !$.trim($ket.val())) { showError($ket, 'Keterangan wajib diisi.'); if (!firstError) firstError = $ket; }

            if (firstError) { e.preventDefault(); firstError.focus(); return false; }

            const $btn = $form.find('#btnSimpanIzinKoreksiEdit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Memperbarui...</span>`);
        });
    });
</script>
