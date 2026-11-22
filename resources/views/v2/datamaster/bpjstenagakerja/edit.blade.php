<form action="{{ route('bpjstenagakerja.update', Crypt::encrypt($bpjstenagakerja->kode_bpjs_tenagakerja)) }}" id="formeditBpjstenagakerja" method="POST" class="space-y-2.5">
    @csrf
    @method('PUT')

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
                value="{{ $bpjstenagakerja->tanggal_berlaku }}"
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
                    <option value="{{ $k->nik }}" {{ $bpjstenagakerja->nik == $k->nik ? 'selected' : '' }}>
                        {{ $k->nik }} - {{ $k->nama_karyawan }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Iuran -->
    <div class="field-group">
        <label for="iuran_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Iuran BPJS Tenaga Kerja (Rp) <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <input type="text" name="iuran" id="iuran_edit"
                value="{{ formatRupiah($bpjstenagakerja->iuran) }}"
                class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                placeholder="0">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnUpdateBpjstenagakerja" data-loading-text="Memperbarui BPJS Tenaga Kerja..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Perbarui Data BPJS Tenaga Kerja</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditBpjstenagakerja");
        const $tanggal = $form.find("#tanggal_berlaku_edit");
        const $nik = $form.find("#nik_edit");
        const $iuran = $form.find("#iuran_edit");
        const $btnSubmit = $form.find("#btnUpdateBpjstenagakerja");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Init Flatpickr
        if (typeof flatpickr !== 'undefined') {
            flatpickr($tanggal[0], {
                dateFormat: 'Y-m-d',
                allowInput: false
            });
        }

        // Init Money Mask
        if ($.fn.maskMoney) {
            $iuran.maskMoney({
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

        // Live cleanup saat user mengetik atau memilih
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

            checkRequired($tanggal, "Tanggal berlaku wajib dipilih!");
            checkRequired($nik, "Karyawan wajib dipilih!");
            checkRequired($iuran, "Iuran BPJS Tenaga Kerja wajib diisi!");

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                firstInvalid.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Memperbarui BPJS Tenaga Kerja...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
