<form action="{{ route('logamtokertas.store') }}" id="formLogamtokertas" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Tanggal -->
    <div class="space-y-1">
        <label for="tanggal_logam" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_logam" name="tanggal" readonly placeholder="Pilih Tanggal"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-tanggal-logam" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Tanggal penukaran wajib diisi</span>
        </p>
    </div>

    <!-- Cabang (Jika multi-cabang) -->
    @hasanyrole($roles_show_cabang)
        <div class="space-y-1">
            <label for="kode_cabang_logam" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_logam" class="select2Kodecabang w-full">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
            <p id="error-cabang-logam" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Cabang wajib dipilih</span>
            </p>
        </div>
    @endhasanyrole

    <!-- Jumlah Nominal -->
    <div class="space-y-1">
        <label for="jumlah_logam" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jumlah Penukaran (Rp) <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="coins" class="w-4 h-4"></i>
            </div>
            <input type="text" id="jumlah_logam" name="jumlah" placeholder="0"
                class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-jumlah-logam" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Jumlah penukaran harus lebih besar dari 0</span>
        </p>
    </div>

    <!-- Tombol Aksi (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelLogam"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanLogam"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formLogamtokertas");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $form.find(".money-mask").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });

        $form.find('.select2Kodecabang').select2({
            placeholder: 'Pilih Cabang',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function parseMoney(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#tanggal_logam").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-logam"));
        });
        $form.find("#kode_cabang_logam").on('change', function() {
            clearError($form.find('.select2-container-v2'), $form.find("#error-cabang-logam"));
        });
        $form.find("#jumlah_logam").on('input keyup change', function() {
            clearError($(this), $form.find("#error-jumlah-logam"));
        });

        $form.find("#btnCancelLogam").on('click', function() {
            if (typeof modalLogamtokertas !== 'undefined') {
                modalLogamtokertas.close();
            } else {
                $("#modalLogamtokertas").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_logam").val();
            const $cabangSelect = $form.find("#kode_cabang_logam");
            const jumlah = parseMoney($form.find("#jumlah_logam").val());

            if (!tanggal) {
                showError($form.find("#tanggal_logam"), $form.find("#error-tanggal-logam"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_logam");
            }

            if ($cabangSelect.length && !$cabangSelect.val()) {
                showError($form.find('.select2-container-v2'), $form.find("#error-cabang-logam"));
                hasError = true;
                if (!$firstError) $firstError = $cabangSelect;
            }

            if (jumlah <= 0) {
                showError($form.find("#jumlah_logam"), $form.find("#error-jumlah-logam"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jumlah_logam");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanLogam");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);

            this.submit();
        });
    });
</script>
