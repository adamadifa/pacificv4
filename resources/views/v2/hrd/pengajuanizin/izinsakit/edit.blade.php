<form action="{{ route('izinsakit.update', Crypt::encrypt($izinsakit->kode_izin_sakit)) }}" method="POST" id="formIzinSakitEdit" enctype="multipart/form-data" autocomplete="off" class="space-y-2.5">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kode Izin Sakit</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_izin_sakit" id="kode_izin_sakit_edit" value="{{ $izinsakit->kode_izin_sakit }}" disabled
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-bold text-[#284c9a] dark:text-blue-400 cursor-not-allowed">
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
            <select name="nik" id="nik_edit" disabled
                class="select2NikSakitEdit w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-200">
                <option value="">Pilih Karyawan</option>
                @foreach ($karyawan as $d)
                    <option value="{{ $d->nik }}" @selected($izinsakit->nik == $d->nik)>{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="dari_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_edit" value="{{ $izinsakit->dari }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>

        <div>
            <label for="sampai_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_edit" value="{{ $izinsakit->sampai }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>
    </div>

    <div>
        <label for="jml_hari_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Jumlah Hari</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="sun" class="w-4 h-4"></i>
            </div>
            <input type="text" name="jml_hari" id="jml_hari_edit" value="0" disabled
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-bold text-slate-600 dark:text-slate-300 cursor-not-allowed">
        </div>
    </div>

    <div>
        <label for="keterangan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <textarea name="keterangan" id="keterangan_edit" rows="2" placeholder="Masukkan keterangan sakit..."
                class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">{{ $izinsakit->keterangan }}</textarea>
        </div>
    </div>

    <div>
        <label for="sid_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Surat Izin Dokter (SID)</label>
        <input type="file" name="sid" id="sid_edit" accept="image/*"
            class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#284c9a] hover:file:bg-blue-100 border border-slate-200 dark:border-slate-700 rounded-lg p-1 cursor-pointer">
        @if (!empty($izinsakit->doc_sid) && Storage::disk('public')->exists('/uploads/sid/' . $izinsakit->doc_sid))
            <div class="mt-2">
                <img src="{{ getSid($izinsakit->doc_sid) }}" alt="SID" class="h-24 rounded-lg border border-slate-200 dark:border-slate-700 object-cover">
            </div>
        @endif
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalIzinSakit && window.closeModalIzinSakit()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanIzinSakitEdit"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Update</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formIzinSakitEdit');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d', allowInput: false });
        if ($.fn.select2) {
            $form.find('.select2NikSakitEdit').select2({ placeholder: 'Pilih Karyawan', width: '100%' });
        }

        function hitungHari(startDate, endDate) {
            if (startDate && endDate) {
                return (new Date(endDate) - new Date(startDate) + (1000 * 3600 * 24)) / (1000 * 3600 * 24);
            }
            return 0;
        }

        $form.find('#jml_hari_edit').val(hitungHari($form.find('#dari_edit').val(), $form.find('#sampai_edit').val()));

        $form.find('#dari_edit, #sampai_edit').on('change', function() {
            $form.find('#jml_hari_edit').val(hitungHari($form.find('#dari_edit').val(), $form.find('#sampai_edit').val()));
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

        $form.find('input, select, textarea').on('input change', function() {
            clearError($(this));
        });

        $form.on('submit', function(e) {
            const $dari = $form.find('#dari_edit');
            const $sampai = $form.find('#sampai_edit');
            const $ket = $form.find('#keterangan_edit');
            let firstError = null;

            if (!$dari.val()) { showError($dari, 'Tanggal Dari wajib diisi.'); if (!firstError) firstError = $dari; }
            if (!$sampai.val()) {
                showError($sampai, 'Tanggal Sampai wajib diisi.');
                if (!firstError) firstError = $sampai;
            } else if ($dari.val() && $sampai.val() < $dari.val()) {
                showError($sampai, 'Periode sakit tidak valid.');
                if (!firstError) firstError = $sampai;
            }
            if (!$.trim($ket.val())) { showError($ket, 'Keterangan wajib diisi.'); if (!firstError) firstError = $ket; }

            if (firstError) {
                e.preventDefault();
                firstError.focus();
                return false;
            }

            const $btn = $form.find('#btnSimpanIzinSakitEdit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Memperbarui...</span>`);
        });
    });
</script>
