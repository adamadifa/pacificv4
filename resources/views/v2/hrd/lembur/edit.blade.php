<form action="{{ route('lembur.update', Crypt::encrypt($lembur->kode_lembur)) }}" method="POST" id="formLemburEdit" autocomplete="off" class="space-y-2.5">
    @method('PUT')
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kode Lembur</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_lembur" id="no_lembur_edit" value="{{ $lembur->kode_lembur }}" disabled
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
                <input type="text" name="tanggal" id="tanggal_edit" value="{{ $lembur->tanggal }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
        <div class="sm:col-span-8">
            <label for="dari_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_edit" value="{{ date('Y-m-d', strtotime($lembur->tanggal_dari)) }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>

        <div class="sm:col-span-4">
            <label for="jam_mulai_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jam Mulai <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jam_mulai" id="jam_mulai_edit" value="{{ date('H:i', strtotime($lembur->tanggal_dari)) }}" placeholder="HH:mm"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
        <div class="sm:col-span-8">
            <label for="sampai_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_edit" value="{{ date('Y-m-d', strtotime($lembur->tanggal_sampai)) }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>

        <div class="sm:col-span-4">
            <label for="jam_selesai_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jam Selesai <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jam_selesai" id="jam_selesai_edit" value="{{ date('H:i', strtotime($lembur->tanggal_sampai)) }}" placeholder="HH:mm"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="kategori_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kategori Lembur
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select name="kategori" id="kategori_edit"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Kategori Lembur</option>
                    <option value="1" @selected($lembur->kategori == 1)>Lembur Reguler</option>
                    <option value="2" @selected($lembur->kategori == 2)>Lembur Hari Libur</option>
                </select>
            </div>
        </div>

        <div>
            <label for="istirahat_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Istirahat <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="coffee" class="w-4 h-4"></i>
                </div>
                <select name="istirahat" id="istirahat_edit"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Istirahat / Tidak Istirahat</option>
                    <option value="1" @selected($lembur->istirahat == 1)>Istirahat</option>
                    <option value="2" @selected($lembur->istirahat == 2)>Tidak Istirahat</option>
                </select>
            </div>
        </div>
    </div>

    @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi']))
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
            <div>
                <label for="kode_dept_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Departemen <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept_edit"
                        class="select2KodeDeptLemburEdit w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                        <option value="">Pilih Departemen</option>
                        @foreach ($departemen as $dept)
                            <option value="{{ $dept->kode_dept }}" @selected($lembur->kode_dept == $dept->kode_dept)>
                                {{ strtoupper($dept->nama_dept) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="posisi_ajuan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Posisi Ajuan
                </label>
                <div class="relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                    </div>
                    <select name="posisi_ajuan" id="posisi_ajuan_edit"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Posisi Ajuan</option>
                        @foreach ($roles_approve as $role)
                            <option value="{{ $role }}" @selected($lembur->posisi_ajuan == $role)>
                                {{ textCamelCase($role) }}
                            </option>
                        @endforeach
                        <option value="" @selected($lembur->posisi_ajuan == null && $lembur->status == '1')>DIREKTUR (SELESAI)</option>
                    </select>
                </div>
            </div>
        </div>
    @endif

    <div>
        <label for="keterangan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <textarea name="keterangan" id="keterangan_edit" rows="2" placeholder="Masukkan keterangan lembur..."
                class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">{{ $lembur->keterangan }}</textarea>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalLembur && window.closeModalLembur()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanLemburEdit"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Update</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formLemburEdit");

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        if ($.fn.mask) {
            $form.find("#jam_mulai_edit, #jam_selesai_edit").mask("99:99");
        }

        if ($.fn.select2) {
            $form.find(".select2KodeDeptLemburEdit").select2({
                placeholder: 'Pilih Departemen',
                width: '100%'
            });
        }

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

        $form.find('input, select, textarea').on('input change select2:select', function() {
            clearError($(this));
        });

        $form.on('submit', function(e) {
            const $tanggal = $form.find("#tanggal_edit");
            const $dari = $form.find("#dari_edit");
            const $jam_mulai = $form.find("#jam_mulai_edit");
            const $sampai = $form.find("#sampai_edit");
            const $jam_selesai = $form.find("#jam_selesai_edit");
            const $kode_dept = $form.find("#kode_dept_edit");
            const $istirahat = $form.find("#istirahat_edit");
            const $keterangan = $form.find("#keterangan_edit");
            let firstError = null;

            if (!$tanggal.val()) { showError($tanggal, 'Tanggal wajib diisi.'); if (!firstError) firstError = $tanggal; }
            if (!$dari.val()) { showError($dari, 'Tanggal mulai wajib diisi.'); if (!firstError) firstError = $dari; }
            if (!$jam_mulai.val()) { showError($jam_mulai, 'Jam mulai wajib diisi.'); if (!firstError) firstError = $jam_mulai; }
            if (!$sampai.val()) { showError($sampai, 'Tanggal selesai wajib diisi.'); if (!firstError) firstError = $sampai; }
            if (!$jam_selesai.val()) { showError($jam_selesai, 'Jam selesai wajib diisi.'); if (!firstError) firstError = $jam_selesai; }
            if ($kode_dept.length && !$kode_dept.val()) { showError($kode_dept, 'Departemen wajib dipilih.'); if (!firstError) firstError = $kode_dept; }
            if (!$istirahat.val()) { showError($istirahat, 'Pilihan istirahat wajib diisi.'); if (!firstError) firstError = $istirahat; }
            if (!$.trim($keterangan.val())) { showError($keterangan, 'Keterangan wajib diisi.'); if (!firstError) firstError = $keterangan; }

            if (firstError) {
                e.preventDefault();
                firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanLemburEdit");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memperbarui...</span>
            `);
        });
    });
</script>
