<form action="{{ route('rekening.update', Crypt::encrypt($karyawan->nik)) }}" id="formeditRekening" method="POST" class="space-y-3">
    @csrf
    @method('PUT')

    <!-- Karyawan Summary Card -->
    <div class="rounded-lg bg-slate-50 dark:bg-slate-800/60 p-3 border border-slate-200/80 dark:border-slate-700 space-y-1.5">
        <div class="flex items-center justify-between">
            <span class="text-[11px] text-slate-400 font-medium">NIK / Identitas</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $karyawan->nik }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-[11px] text-slate-400 font-medium">Nama Karyawan</span>
            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $karyawan->nama_karyawan }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-[11px] text-slate-400 font-medium">Jabatan</span>
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $karyawan->nama_jabatan }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-[11px] text-slate-400 font-medium">Departemen</span>
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $karyawan->nama_dept }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-[11px] text-slate-400 font-medium">Cabang</span>
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $karyawan->kode_cabang }}</span>
        </div>
    </div>

    <!-- Input No Rekening -->
    <div class="field-group">
        <label for="no_rekening_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nomor Rekening Bank <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="credit-card" class="w-4 h-4"></i>
            </div>
            <input type="text" name="no_rekening" id="no_rekening_edit" value="{{ $karyawan->no_rekening }}" autofocus maxlength="50"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                placeholder="Nomor Rekening Bank Payroll">
        </div>
        <p class="text-[10px] text-slate-400 mt-0.5 pl-1 helper-text">Pastikan nomor rekening aktif dan sesuai buku tabungan.</p>
    </div>

    <!-- Submit Button -->
    <div class="pt-1.5">
        <button type="submit" id="btnUpdateRekening" data-loading-text="Menyimpan Rekening..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Simpan Nomor Rekening</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditRekening");
        const $rek = $form.find("#no_rekening_edit");
        const $btnSubmit = $form.find("#btnUpdateRekening");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Helper untuk menandai input invalid
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $container.find('.helper-text').addClass('hidden');
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
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.helper-text').removeClass('hidden');
            $container.find('.field-error-msg').remove();
        }

        // Event listener saat user mengetik
        $rek.on('input change', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            const rekVal = $rek.val().trim();

            if (!rekVal) {
                e.preventDefault();
                setFieldError($rek, "Nomor rekening wajib diisi!");
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                $rek.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Menyimpan Rekening...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>