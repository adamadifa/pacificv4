<form action="{{ route('kontrakkerja.update', Crypt::encrypt($kontrak->no_kontrak)) }}" method="POST" id="formKontrakEdit" autocomplete="off" class="space-y-2.5">
    @csrf
    @method('PUT')

    <!-- No Kontrak & NIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. Kontrak</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_kontrak" value="{{ $kontrak->no_kontrak }}" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-bold text-slate-600 dark:text-slate-300">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">NIK Karyawan</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nik" id="nik_kontrak_edit" value="{{ $kontrak->nik }}" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-bold text-slate-600 dark:text-slate-300">
            </div>
        </div>
    </div>

    <!-- Perusahaan & Kantor -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="kode_perusahaan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Perusahaan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_perusahaan" id="kode_perusahaan_edit"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Perusahaan</option>
                    <option value="MP" @selected($kontrak->kode_perusahaan == 'MP')>Makmur Permata</option>
                    <option value="PC" @selected($kontrak->kode_perusahaan == 'PC')>Pacific</option>
                </select>
            </div>
        </div>

        <div>
            <label for="kode_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kantor / Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_edit" class="select2CabangEdit w-full">
                    <option value="">Pilih Kantor</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}" @selected($kontrak->kode_cabang == $d->kode_cabang)>{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Departemen & Jabatan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="kode_dept_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Departemen <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <select name="kode_dept" id="kode_dept_edit" class="select2DeptEdit w-full">
                    <option value="">Pilih Departemen</option>
                    @foreach ($departemen as $d)
                        <option value="{{ $d->kode_dept }}" @selected($kontrak->kode_dept == $d->kode_dept)>{{ textUpperCase($d->nama_dept) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label for="kode_jabatan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jabatan <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                </div>
                <select name="kode_jabatan" id="kode_jabatan_edit" class="select2JabatanEdit w-full">
                    <option value="">Pilih Jabatan</option>
                    @foreach ($jabatan as $d)
                        <option value="{{ $d->kode_jabatan }}" @selected($kontrak->kode_jabatan == $d->kode_jabatan)>{{ textUpperCase($d->nama_jabatan) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Tanggal Mulai & Selesai -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="dari_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Mulai <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_edit" value="{{ $kontrak->dari }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date-kontrak-edit w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>

        <div>
            <label for="sampai_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Selesai <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_edit" value="{{ $kontrak->sampai }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date-kontrak-edit w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- Card Data Gaji -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 p-3 sm:p-3.5">
        <div class="flex items-center gap-1.5 mb-2.5">
            <i data-lucide="banknote" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h5 class="text-xs font-bold text-slate-700 dark:text-slate-200">Data Gaji</h5>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            @foreach ([
                'gaji_pokok' => 'Gaji Pokok',
                't_jabatan' => 'Tunj. Jabatan',
                't_masakerja' => 'Tunj. Masa Kerja',
                't_tanggungjawab' => 'Tunj. Tangg. Jawab',
                't_makan' => 'Tunj. Makan',
                't_istri' => 'Tunj. Istri',
                't_skill' => 'Tunj. Skill',
            ] as $name => $label)
                <div>
                    <label for="{{ $name }}_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">{{ $label }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="banknote" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="{{ $name }}" id="{{ $name }}_edit"
                            value="{{ $gaji != null ? formatRupiah($gaji->$name) : 0 }}" placeholder="0"
                            class="money-kontrak-edit w-full h-[36px] pl-9 pr-3 text-right font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer Buttons (Right-Aligned) -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" onclick="window.closeModalKontrak && window.closeModalKontrak()"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanKontrakEdit"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $('#formKontrakEdit');
        const $perusahaan = $form.find('#kode_perusahaan_edit');
        const $cabang = $form.find('#kode_cabang_edit');
        const $dept = $form.find('#kode_dept_edit');
        const $jabatan = $form.find('#kode_jabatan_edit');
        const $dari = $form.find('#dari_edit');
        const $sampai = $form.find('#sampai_edit');

        $form.find('.money-kontrak-edit').maskMoney();
        $form.find('.flatpickr-date-kontrak-edit').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        $cabang.select2({ placeholder: 'Pilih Kantor', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
        $dept.select2({ placeholder: 'Pilih Departemen', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
        $jabatan.select2({ placeholder: 'Pilih Jabatan', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });

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

        $perusahaan.add($dari).add($sampai).on('input change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $cabang.add($dept).add($jabatan).on('select2:select change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $form.on('submit', function(e) {
            let firstInvalid = null;

            if (!$perusahaan.val()) { setFieldError($perusahaan, 'Perusahaan wajib dipilih.'); firstInvalid = firstInvalid || $perusahaan; }
            if (!$cabang.val()) { setFieldError($cabang, 'Kantor wajib dipilih.'); firstInvalid = firstInvalid || $cabang; }
            if (!$dept.val()) { setFieldError($dept, 'Departemen wajib dipilih.'); firstInvalid = firstInvalid || $dept; }
            if (!$jabatan.val()) { setFieldError($jabatan, 'Jabatan wajib dipilih.'); firstInvalid = firstInvalid || $jabatan; }
            if (!$dari.val()) { setFieldError($dari, 'Tanggal mulai wajib diisi.'); firstInvalid = firstInvalid || $dari; }
            if (!$sampai.val()) { setFieldError($sampai, 'Tanggal selesai wajib diisi.'); firstInvalid = firstInvalid || $sampai; }

            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return false;
            }

            const $btn = $('#btnSimpanKontrakEdit');
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
