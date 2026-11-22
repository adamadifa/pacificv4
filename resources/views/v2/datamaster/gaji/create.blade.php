<form action="{{ route('gaji.store') }}" id="formcreateGaji" method="POST" class="space-y-2.5">
    @csrf

    <!-- Tanggal Berlaku & Karyawan (2 COLS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Tanggal Berlaku -->
        <div class="field-group">
            <label for="tanggal_berlaku_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Berlaku <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal_berlaku" id="tanggal_berlaku_create" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer font-mono-num"
                    placeholder="Pilih Tanggal">
            </div>
        </div>

        <!-- Karyawan -->
        <div class="field-group">
            <label for="nik_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Karyawan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <select name="nik" id="nik_create"
                    class="select2Karyawan w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100">
                    <option value="">Pilih Karyawan</option>
                    @foreach ($karyawan as $k)
                        <option value="{{ $k->nik }}">{{ $k->nik }} - {{ $k->nama_karyawan }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Gaji Pokok -->
    <div class="field-group">
        <label for="gaji_pokok_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Gaji Pokok (Rp) <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <input type="text" name="gaji_pokok" id="gaji_pokok_create"
                class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                placeholder="0">
        </div>
    </div>

    <!-- Divider Section: Rincian Tunjangan -->
    <div class="pt-1">
        <div class="flex items-center gap-2 mb-2">
            <div class="h-px bg-slate-200 dark:bg-slate-700 flex-1"></div>
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                <i data-lucide="gift" class="w-3.5 h-3.5 text-brand-500"></i>
                Rincian Tunjangan
            </span>
            <div class="h-px bg-slate-200 dark:bg-slate-700 flex-1"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <!-- Tunj. Jabatan -->
            <div class="field-group">
                <label for="t_jabatan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tunjangan Jabatan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="award" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="t_jabatan" id="t_jabatan_create"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- Tunj. Masa Kerja -->
            <div class="field-group">
                <label for="t_masakerja_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tunjangan Masa Kerja <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="t_masakerja" id="t_masakerja_create"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- Tunj. Tanggung Jawab -->
            <div class="field-group">
                <label for="t_tanggungjawab_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tunjangan Tanggung Jawab <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="shield" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="t_tanggungjawab" id="t_tanggungjawab_create"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- Tunj. Makan -->
            <div class="field-group">
                <label for="t_makan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tunjangan Makan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="utensils" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="t_makan" id="t_makan_create"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- Tunj. Istri -->
            <div class="field-group">
                <label for="t_istri_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tunjangan Istri <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="t_istri" id="t_istri_create"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- Tunj. Skill -->
            <div class="field-group">
                <label for="t_skill_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tunjangan Skill <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="zap" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="t_skill" id="t_skill_create"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSubmitGaji" data-loading-text="Menyimpan Gaji..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Data Gaji</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formcreateGaji");
        const $tgl = $form.find("#tanggal_berlaku_create");
        const $nik = $form.find("#nik_create");
        const $gapok = $form.find("#gaji_pokok_create");
        const $tJabatan = $form.find("#t_jabatan_create");
        const $tMasakerja = $form.find("#t_masakerja_create");
        const $tTanggungjawab = $form.find("#t_tanggungjawab_create");
        const $tMakan = $form.find("#t_makan_create");
        const $tIstri = $form.find("#t_istri_create");
        const $tSkill = $form.find("#t_skill_create");
        const $btnSubmit = $form.find("#btnSubmitGaji");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Init flatpickr
        if (typeof flatpickr !== 'undefined') {
            $form.find(".flatpickr-date").each(function() {
                if (!this._flatpickr) {
                    flatpickr(this, {
                        dateFormat: "Y-m-d",
                        allowInput: false
                    });
                }
            });
        }

        // Init maskMoney
        if ($.fn.maskMoney) {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        // Init Select2 Karyawan
        if ($.fn.select2) {
            $form.find(".select2Karyawan").each(function() {
                var $this = $(this);
                $this.select2({
                    placeholder: "Pilih Karyawan",
                    allowClear: true,
                    width: '100%'
                });
            });
        }

        // Helper untuk menandai input invalid
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.select2-selection--single').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $container.find('.field-error-msg').remove();
            $container.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        // Helper untuk membersihkan tanda error
        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.select2-selection--single').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        // Event listener saat user memperbaiki isi input
        $form.find('input, select').on('input change select2:select select2:clear', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            let hasError = false;
            let firstInvalid = null;

            function checkRequired($input, msg) {
                if (!$input.val() || !$input.val().toString().trim()) {
                    setFieldError($input, msg);
                    hasError = true;
                    if (!firstInvalid) firstInvalid = $input;
                }
            }

            checkRequired($tSkill, "Tunjangan skill wajib diisi!");
            checkRequired($tIstri, "Tunjangan istri wajib diisi!");
            checkRequired($tMakan, "Tunjangan makan wajib diisi!");
            checkRequired($tTanggungjawab, "Tunjangan tanggung jawab wajib diisi!");
            checkRequired($tMasakerja, "Tunjangan masa kerja wajib diisi!");
            checkRequired($tJabatan, "Tunjangan jabatan wajib diisi!");
            checkRequired($gapok, "Gaji pokok wajib diisi!");
            checkRequired($nik, "Karyawan wajib dipilih!");
            checkRequired($tgl, "Tanggal berlaku wajib diisi!");

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                firstInvalid.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Menyimpan Gaji...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>