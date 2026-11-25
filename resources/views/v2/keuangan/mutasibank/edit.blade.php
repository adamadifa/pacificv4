<form action="{{ route('mutasibank.update', Crypt::encrypt($mutasibank->no_bukti)) }}" method="POST" id="formeditMutasibank" class="space-y-3" autocomplete="off">
    @csrf
    @method('PUT')

    <!-- Bank Rekening -->
    <div class="space-y-1">
        <label for="kode_bank_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Bank Rekening <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="landmark" class="w-4 h-4"></i>
            </div>
            <select name="kode_bank" id="kode_bank_edit" class="select2Kodebankeditmutasi w-full">
                <option value="">Pilih Bank</option>
                @foreach ($bank as $d)
                    <option value="{{ $d->kode_bank }}" {{ $mutasibank->kode_bank == $d->kode_bank ? 'selected' : '' }}>
                        {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <p id="error-bank-edit-mutasi" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Bank rekening wajib dipilih</span>
        </p>
    </div>

    <!-- Tanggal -->
    <div class="space-y-1">
        <label for="tanggal_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal Transaksi <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_edit" name="tanggal" value="{{ $mutasibank->tanggal }}" readonly placeholder="Pilih Tanggal"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-tanggal-edit-mutasi" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Tanggal transaksi wajib diisi</span>
        </p>
    </div>

    <!-- Keterangan -->
    <div class="space-y-1">
        <label for="keterangan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" id="keterangan_edit" name="keterangan" value="{{ $mutasibank->keterangan }}" placeholder="Keterangan transaksi mutasi bank"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-keterangan-edit-mutasi" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Keterangan transaksi wajib diisi</span>
        </p>
    </div>

    <!-- Kode Akun (COA) -->
    <div class="space-y-1">
        <label for="kode_akun_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Kode Akun <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <select name="kode_akun" id="kode_akun_edit" class="select2Kodeakuneditmutasi w-full">
                <option value="">Pilih Kode Akun</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}" {{ $mutasibank->kode_akun == $d->kode_akun ? 'selected' : '' }}>
                        {{ $d->kode_akun }} {{ $d->nama_akun }}
                    </option>
                @endforeach
            </select>
        </div>
        <p id="error-akun-edit-mutasi" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Kode akun wajib dipilih</span>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Debet / Kredit -->
        <div class="space-y-1">
            <label for="debet_kredit_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Jenis Transaksi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                </div>
                <select id="debet_kredit_edit" name="debet_kredit"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Debet/Kredit</option>
                    <option value="D" {{ $mutasibank->debet_kredit == 'D' ? 'selected' : '' }}>Debet (Pengeluaran)</option>
                    <option value="K" {{ $mutasibank->debet_kredit == 'K' ? 'selected' : '' }}>Kredit (Penerimaan)</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <p id="error-dk-edit-mutasi" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Pilih jenis Debet/Kredit</span>
            </p>
        </div>

        <!-- Jumlah -->
        <div class="space-y-1">
            <label for="jumlah_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Jumlah (Rp) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <span class="text-xs font-bold text-slate-400">Rp</span>
                </div>
                <input type="text" id="jumlah_edit" name="jumlah" value="{{ formatAngka($mutasibank->jumlah) }}"
                    class="money w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 text-right font-mono-num placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-jumlah-edit-mutasi" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Jumlah harus lebih besar dari 0</span>
            </p>
        </div>
    </div>

    <!-- Tombol Aksi (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelEditMutasibank"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnUpdateMutasibank"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditMutasibank");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $form.find(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });

        const $select2Bank = $form.find('.select2Kodebankeditmutasi');
        if ($select2Bank.length) {
            $select2Bank.select2({
                placeholder: 'Pilih Bank',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        const $select2Akun = $form.find('.select2Kodeakuneditmutasi');
        if ($select2Akun.length) {
            $select2Akun.select2({
                placeholder: 'Pilih Kode Akun',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error, customText) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            if (customText) $error.find('span').text(customText);
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#kode_bank_edit").on('change select2:select', function() {
            clearError($(this), $form.find("#error-bank-edit-mutasi"));
        });
        $form.find("#tanggal_edit").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-edit-mutasi"));
        });
        $form.find("#keterangan_edit").on('input change', function() {
            clearError($(this), $form.find("#error-keterangan-edit-mutasi"));
        });
        $form.find("#kode_akun_edit").on('change select2:select', function() {
            clearError($(this), $form.find("#error-akun-edit-mutasi"));
        });
        $form.find("#debet_kredit_edit").on('change', function() {
            clearError($(this), $form.find("#error-dk-edit-mutasi"));
        });
        $form.find("#jumlah_edit").on('input change', function() {
            clearError($(this), $form.find("#error-jumlah-edit-mutasi"));
        });

        $form.find("#btnCancelEditMutasibank").on('click', function() {
            if (typeof modalEditMutasibankHelper !== 'undefined') {
                modalEditMutasibankHelper.close();
            } else {
                $("#modalEditMutasibank").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        function parseMoney(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const bank = $form.find("#kode_bank_edit").val();
            if (!bank) {
                showError($form.find("#kode_bank_edit"), $form.find("#error-bank-edit-mutasi"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_bank_edit");
            }

            const tanggal = $form.find("#tanggal_edit").val().trim();
            if (!tanggal) {
                showError($form.find("#tanggal_edit"), $form.find("#error-tanggal-edit-mutasi"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_edit");
            }

            const keterangan = $form.find("#keterangan_edit").val().trim();
            if (!keterangan) {
                showError($form.find("#keterangan_edit"), $form.find("#error-keterangan-edit-mutasi"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#keterangan_edit");
            }

            const kode_akun = $form.find("#kode_akun_edit").val();
            if (!kode_akun) {
                showError($form.find("#kode_akun_edit"), $form.find("#error-akun-edit-mutasi"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_akun_edit");
            }

            const debet_kredit = $form.find("#debet_kredit_edit").val();
            if (!debet_kredit) {
                showError($form.find("#debet_kredit_edit"), $form.find("#error-dk-edit-mutasi"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#debet_kredit_edit");
            }

            const jumlah = parseMoney($form.find("#jumlah_edit").val());
            if (jumlah <= 0) {
                showError($form.find("#jumlah_edit"), $form.find("#error-jumlah-edit-mutasi"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jumlah_edit");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnUpdateMutasibank");
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
