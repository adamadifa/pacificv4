<form action="{{ route('sakasbesarkeuangan.store') }}" method="POST" id="formCreateSaldoKasBesarPusat" class="space-y-3" autocomplete="off">
    @csrf
    <input type="hidden" name="kode_cabang" value="PST">

    <div class="space-y-1">
        <label for="tanggal_skbp_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_skbp_create" name="tanggal" value="{{ date('Y-m-d') }}" readonly placeholder="Pilih Tanggal"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-tanggal-skbp-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Tanggal wajib diisi</span>
        </p>
    </div>

    <div class="space-y-1">
        <label for="keterangan_skbp_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" id="keterangan_skbp_create" name="keterangan" placeholder="Keterangan saldo kas besar pusat"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-keterangan-skbp-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Keterangan wajib diisi</span>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div class="space-y-1">
            <label for="debet_kredit_skbp_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Debet / Kredit <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                </div>
                <select name="debet_kredit" id="debet_kredit_skbp_create"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Debet / Kredit</option>
                    <option value="D">Debet</option>
                    <option value="K">Kredit</option>
                </select>
            </div>
            <p id="error-dk-skbp-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Debet / Kredit wajib dipilih</span>
            </p>
        </div>

        <div class="space-y-1">
            <label for="jumlah_skbp_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Jumlah Saldo <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                </div>
                <input type="text" id="jumlah_skbp_create" name="jumlah" placeholder="0"
                    class="money w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-jumlah-skbp-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Jumlah wajib diisi</span>
            </p>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelSkbpCreate"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanSkbpCreate"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Saldo</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formCreateSaldoKasBesarPusat");
        $form.find(".flatpickr-date").flatpickr({ dateFormat: "Y-m-d", allowInput: false });
        $form.find(".money").maskMoney({ thousands: '.', decimal: ',', precision: 0 });

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#tanggal_skbp_create").on('input change', function() { clearError($(this), $form.find("#error-tanggal-skbp-create")); });
        $form.find("#keterangan_skbp_create").on('input change', function() { clearError($(this), $form.find("#error-keterangan-skbp-create")); });
        $form.find("#debet_kredit_skbp_create").on('change', function() { clearError($(this), $form.find("#error-dk-skbp-create")); });
        $form.find("#jumlah_skbp_create").on('input change keyup', function() { clearError($(this), $form.find("#error-jumlah-skbp-create")); });

        $form.find("#btnCancelSkbpCreate").on('click', function() {
            if (typeof modalSaldoKasBesarPusatHelper !== 'undefined') modalSaldoKasBesarPusatHelper.close();
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false, $firstError = null;

            const $tgl = $form.find("#tanggal_skbp_create");
            if (!$tgl.val().trim()) { showError($tgl, $form.find("#error-tanggal-skbp-create")); hasError = true; if (!$firstError) $firstError = $tgl; }

            const $ket = $form.find("#keterangan_skbp_create");
            if (!$ket.val().trim()) { showError($ket, $form.find("#error-keterangan-skbp-create")); hasError = true; if (!$firstError) $firstError = $ket; }

            const $dk = $form.find("#debet_kredit_skbp_create");
            if (!$dk.val()) { showError($dk, $form.find("#error-dk-skbp-create")); hasError = true; if (!$firstError) $firstError = $dk; }

            const $jml = $form.find("#jumlah_skbp_create");
            if (!$jml.val().trim()) { showError($jml, $form.find("#error-jumlah-skbp-create")); hasError = true; if (!$firstError) $firstError = $jml; }

            if (hasError) { if ($firstError) $firstError.focus(); return false; }

            const $btn = $form.find("#btnSimpanSkbpCreate");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Menyimpan...</span>`);
            this.submit();
        });
    });
</script>
