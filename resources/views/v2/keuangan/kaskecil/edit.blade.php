<form action="{{ route('kaskecil.update', Crypt::encrypt($kaskecil->id)) }}" method="POST" id="formeditKaskecil" class="space-y-3" autocomplete="off">
    @csrf
    @method('PUT')

    @hasanyrole($roles_show_cabang)
        <div class="space-y-1">
            <label for="kode_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_edit" class="select2Kodecabangedit w-full" {{ !empty($kaskecil->kode_klaim) ? 'disabled' : '' }}>
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}" {{ $kaskecil->kode_cabang == $d->kode_cabang ? 'selected' : '' }}>
                            {{ textuppercase($d->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
                @if (!empty($kaskecil->kode_klaim))
                    <input type="hidden" name="kode_cabang" value="{{ $kaskecil->kode_cabang }}">
                @endif
            </div>
            <p id="error-cabang-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Cabang wajib dipilih</span>
            </p>
        </div>
    @endhasanyrole

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- No. Bukti -->
        <div class="space-y-1">
            <label for="no_bukti_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                No. Bukti <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" id="no_bukti_edit" name="no_bukti" value="{{ $kaskecil->no_bukti }}"
                    {{ !empty($kaskecil->kode_klaim) ? 'readonly' : '' }}
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition {{ !empty($kaskecil->kode_klaim) ? 'bg-slate-100 dark:bg-slate-800/80 cursor-not-allowed text-slate-500' : '' }}">
            </div>
            <p id="error-nobukti-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>No. Bukti wajib diisi</span>
            </p>
        </div>

        <!-- Tanggal -->
        <div class="space-y-1">
            <label for="tanggal_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_edit" name="tanggal" value="{{ $kaskecil->tanggal }}" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal wajib diisi</span>
            </p>
        </div>
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
            <input type="text" id="keterangan_edit" name="keterangan" value="{{ $kaskecil->keterangan }}" placeholder="Keterangan transaksi"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-keterangan-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Keterangan wajib diisi</span>
        </p>
    </div>

    <!-- Akun Rekening (COA) -->
    <div class="space-y-1">
        <label for="kode_akun_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Akun Rekening <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <select name="kode_akun" id="kode_akun_edit" class="select2Kodeakunedit w-full">
                <option value="">Pilih Akun</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}" {{ $kaskecil->kode_akun == $d->kode_akun ? 'selected' : '' }}>
                        {{ $d->kode_akun }} - {{ $d->nama_akun }}
                    </option>
                @endforeach
            </select>
        </div>
        <p id="error-akun-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Akun rekening wajib dipilih</span>
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
                <select id="debet_kredit_edit" name="debet_kredit_edit" {{ !empty($kaskecil->kode_klaim) ? 'disabled' : '' }}
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer {{ !empty($kaskecil->kode_klaim) ? 'bg-slate-100 dark:bg-slate-800/80 cursor-not-allowed text-slate-500' : '' }}">
                    <option value="D" {{ $kaskecil->debet_kredit == 'D' ? 'selected' : '' }}>DEBET (Pengeluaran)</option>
                    <option value="K" {{ $kaskecil->debet_kredit == 'K' ? 'selected' : '' }}>KREDIT (Penerimaan)</option>
                </select>
                <input type="hidden" name="debet_kredit" value="{{ $kaskecil->debet_kredit }}">
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
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
                <input type="text" id="jumlah_edit" name="jumlah" value="{{ formatAngka($kaskecil->jumlah) }}"
                    {{ !empty($kaskecil->kode_klaim) ? 'readonly' : '' }}
                    class="money w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 text-right font-mono-num placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition {{ !empty($kaskecil->kode_klaim) ? 'bg-slate-100 dark:bg-slate-800/80 cursor-not-allowed text-slate-500' : '' }}">
            </div>
            <p id="error-jumlah-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Jumlah harus lebih besar dari 0</span>
            </p>
        </div>
    </div>

    <!-- Tombol Aksi (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelEditKaskecil"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnUpdateKaskecil"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditKaskecil");

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

        const $select2Cabang = $form.find('.select2Kodecabangedit');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        const $select2Akun = $form.find('.select2Kodeakunedit');
        if ($select2Akun.length) {
            $select2Akun.select2({
                placeholder: 'Pilih Akun',
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

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#kode_cabang_edit").on('change select2:select', function() {
            clearError($(this), $form.find("#error-cabang-edit"));
        });
        $form.find("#no_bukti_edit").on('input change', function() {
            clearError($(this), $form.find("#error-nobukti-edit"));
        });
        $form.find("#tanggal_edit").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-edit"));
        });
        $form.find("#keterangan_edit").on('input change', function() {
            clearError($(this), $form.find("#error-keterangan-edit"));
        });
        $form.find("#kode_akun_edit").on('change select2:select', function() {
            clearError($(this), $form.find("#error-akun-edit"));
        });
        $form.find("#jumlah_edit").on('input change', function() {
            clearError($(this), $form.find("#error-jumlah-edit"));
        });

        // Sync hidden debet_kredit when select changes
        $form.find("#debet_kredit_edit").on('change', function() {
            $form.find("input[name='debet_kredit']").val($(this).val());
        });

        $form.find("#btnCancelEditKaskecil").on('click', function() {
            if (typeof modalEditKaskecilHelper !== 'undefined') {
                modalEditKaskecilHelper.close();
            } else {
                $("#modalEditKaskecil").addClass('hidden');
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

            const $cabang = $form.find("#kode_cabang_edit");
            if ($cabang.length && !$cabang.is(':disabled') && !$cabang.val()) {
                showError($cabang, $form.find("#error-cabang-edit"));
                hasError = true;
                if (!$firstError) $firstError = $cabang;
            }

            const no_bukti = $form.find("#no_bukti_edit").val().trim();
            if (!no_bukti) {
                showError($form.find("#no_bukti_edit"), $form.find("#error-nobukti-edit"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#no_bukti_edit");
            }

            const tanggal = $form.find("#tanggal_edit").val().trim();
            if (!tanggal) {
                showError($form.find("#tanggal_edit"), $form.find("#error-tanggal-edit"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_edit");
            }

            const keterangan = $form.find("#keterangan_edit").val().trim();
            if (!keterangan) {
                showError($form.find("#keterangan_edit"), $form.find("#error-keterangan-edit"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#keterangan_edit");
            }

            const kode_akun = $form.find("#kode_akun_edit").val();
            if (!kode_akun) {
                showError($form.find("#kode_akun_edit"), $form.find("#error-akun-edit"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_akun_edit");
            }

            const jumlah = parseMoney($form.find("#jumlah_edit").val());
            if (jumlah <= 0) {
                showError($form.find("#jumlah_edit"), $form.find("#error-jumlah-edit"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jumlah_edit");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnUpdateKaskecil");
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
