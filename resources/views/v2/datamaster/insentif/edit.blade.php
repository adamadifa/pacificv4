<form action="{{ route('insentif.update', Crypt::encrypt($insentif->kode_insentif)) }}" id="formeditInsentif" method="POST" class="space-y-2.5">
    @csrf
    @method('PUT')

    <!-- Tanggal Berlaku & Karyawan (2 COLS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Tanggal Berlaku -->
        <div class="field-group">
            <label for="tanggal_berlaku_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Berlaku <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal_berlaku" id="tanggal_berlaku_edit" readonly
                    value="{{ $insentif->tanggal_berlaku }}"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer font-mono-num"
                    placeholder="Pilih Tanggal">
            </div>
        </div>

        <!-- Karyawan -->
        <div class="field-group">
            <label for="nik_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Karyawan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <select name="nik" id="nik_edit"
                    class="select2Karyawan w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100">
                    <option value="">Pilih Karyawan</option>
                    @foreach ($karyawan as $k)
                        <option value="{{ $k->nik }}" {{ $insentif->nik == $k->nik ? 'selected' : '' }}>
                            {{ $k->nik }} - {{ $k->nama_karyawan }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Divider Section: Insentif Umum -->
    <div class="pt-1">
        <div class="flex items-center gap-2 mb-2">
            <div class="h-px bg-slate-200 dark:bg-slate-700 flex-1"></div>
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                <i data-lucide="coins" class="w-3.5 h-3.5 text-brand-500"></i>
                Insentif Umum
            </span>
            <div class="h-px bg-slate-200 dark:bg-slate-700 flex-1"></div>
        </div>

        <div class="space-y-2.5">
            <!-- IU Masa Kerja -->
            <div class="field-group">
                <label for="iu_masakerja_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Masa Kerja (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="iu_masakerja" id="iu_masakerja_edit"
                        value="{{ formatRupiah($insentif->iu_masakerja) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- IU Lembur -->
            <div class="field-group">
                <label for="iu_lembur_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Lembur (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="timer" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="iu_lembur" id="iu_lembur_edit"
                        value="{{ formatRupiah($insentif->iu_lembur) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- IU Penempatan -->
            <div class="field-group">
                <label for="iu_penempatan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Penempatan (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="iu_penempatan" id="iu_penempatan_edit"
                        value="{{ formatRupiah($insentif->iu_penempatan) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- IU KPI -->
            <div class="field-group">
                <label for="iu_kpi_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    KPI (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="target" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="iu_kpi" id="iu_kpi_edit"
                        value="{{ formatRupiah($insentif->iu_kpi) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>
        </div>
    </div>

    <!-- Divider Section: Insentif Manager -->
    <div class="pt-1">
        <div class="flex items-center gap-2 mb-2">
            <div class="h-px bg-slate-200 dark:bg-slate-700 flex-1"></div>
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                <i data-lucide="briefcase" class="w-3.5 h-3.5 text-brand-500"></i>
                Insentif Manager
            </span>
            <div class="h-px bg-slate-200 dark:bg-slate-700 flex-1"></div>
        </div>

        <div class="space-y-2.5">
            <!-- IM Ruang Lingkup -->
            <div class="field-group">
                <label for="im_ruanglingkup_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Ruang Lingkup (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="globe" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="im_ruanglingkup" id="im_ruanglingkup_edit"
                        value="{{ formatRupiah($insentif->im_ruanglingkup) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- IM Penempatan -->
            <div class="field-group">
                <label for="im_penempatan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Penempatan (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="im_penempatan" id="im_penempatan_edit"
                        value="{{ formatRupiah($insentif->im_penempatan) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- IM Kinerja -->
            <div class="field-group">
                <label for="im_kinerja_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Kinerja (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="im_kinerja" id="im_kinerja_edit"
                        value="{{ formatRupiah($insentif->im_kinerja) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>

            <!-- IM Kendaraan -->
            <div class="field-group">
                <label for="im_kendaraan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Kendaraan (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="im_kendaraan" id="im_kendaraan_edit"
                        value="{{ formatRupiah($insentif->im_kendaraan) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                        placeholder="0">
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSubmitEditInsentif" data-loading-text="Menyimpan Insentif..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditInsentif");
        const $tgl = $form.find("#tanggal_berlaku_edit");
        const $nik = $form.find("#nik_edit");
        const $iuMasakerja = $form.find("#iu_masakerja_edit");
        const $iuLembur = $form.find("#iu_lembur_edit");
        const $iuPenempatan = $form.find("#iu_penempatan_edit");
        const $iuKpi = $form.find("#iu_kpi_edit");
        const $imRuanglingkup = $form.find("#im_ruanglingkup_edit");
        const $imPenempatan = $form.find("#im_penempatan_edit");
        const $imKinerja = $form.find("#im_kinerja_edit");
        const $imKendaraan = $form.find("#im_kendaraan_edit");
        const $btnSubmit = $form.find("#btnSubmitEditInsentif");

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

            checkRequired($imKendaraan, "Insentif kendaraan wajib diisi!");
            checkRequired($imKinerja, "Insentif kinerja wajib diisi!");
            checkRequired($imPenempatan, "Insentif manager penempatan wajib diisi!");
            checkRequired($imRuanglingkup, "Insentif ruang lingkup wajib diisi!");
            checkRequired($iuKpi, "Insentif KPI wajib diisi!");
            checkRequired($iuPenempatan, "Insentif umum penempatan wajib diisi!");
            checkRequired($iuLembur, "Insentif lembur wajib diisi!");
            checkRequired($iuMasakerja, "Insentif masa kerja wajib diisi!");
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
                window.setButtonSubmitting($btnSubmit, 'Menyimpan Insentif...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
