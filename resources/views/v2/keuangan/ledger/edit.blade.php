<form action="{{ route('ledger.update', Crypt::encrypt($ledger->no_bukti)) }}" method="POST" id="formeditLedger" class="space-y-3" autocomplete="off">
    @csrf
    @method('PUT')
    <input type="hidden" id="cektutuplaporan_edit" value="0">

    <!-- BANK REKENING -->
    <div class="space-y-1">
        <label for="kode_bank_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Bank Rekening <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="landmark" class="w-4 h-4"></i>
            </div>
            <select name="kode_bank" id="kode_bank_edit" class="select2Kodebankedit w-full">
                <option value="">Pilih Bank</option>
                @foreach ($bank as $d)
                    <option value="{{ $d->kode_bank }}" {{ $ledger->kode_bank == $d->kode_bank ? 'selected' : '' }}>
                        {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <p id="error-bank-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Bank rekening wajib dipilih</span>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Tanggal -->
        <div class="space-y-1">
            <label for="tanggal_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_edit" name="tanggal" value="{{ $ledger->tanggal }}" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal wajib diisi</span>
            </p>
        </div>

        <!-- Pelanggan -->
        <div class="space-y-1">
            <label for="pelanggan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Pelanggan (Opsional)
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" id="pelanggan_edit" name="pelanggan" value="{{ $ledger->pelanggan }}" placeholder="Nama pelanggan / pihak ketiga"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
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
            <input type="text" id="keterangan_edit" name="keterangan" value="{{ $ledger->keterangan }}" placeholder="Keterangan transaksi"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-keterangan-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Keterangan wajib diisi</span>
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
            <select name="kode_akun" id="kode_akun_edit" class="select2Kodeakunedit w-full">
                <option value="">Pilih Kode Akun</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}" {{ $ledger->kode_akun == $d->kode_akun ? 'selected' : '' }}>
                        {{ $d->kode_akun }} - {{ $d->nama_akun }}
                    </option>
                @endforeach
            </select>
        </div>
        <p id="error-akun-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Kode akun wajib dipilih</span>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Debet / Kredit -->
        <div class="space-y-1">
            <label for="debet_kredit_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Debet / Kredit <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                </div>
                <select id="debet_kredit_edit" name="debet_kredit"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="D" {{ $ledger->debet_kredit == 'D' ? 'selected' : '' }}>Debet (Keluar)</option>
                    <option value="K" {{ $ledger->debet_kredit == 'K' ? 'selected' : '' }}>Kredit (Masuk)</option>
                </select>
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
                <input type="text" id="jumlah_edit" name="jumlah" value="{{ formatAngka($ledger->jumlah) }}"
                    class="money w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 text-right font-mono-num placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-jumlah-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Jumlah harus lebih besar dari 0</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Peruntukan -->
        <div class="space-y-1">
            <label for="kode_peruntukan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Peruntukan
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select id="kode_peruntukan_edit" name="kode_peruntukan"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih</option>
                    <option value="MP" {{ $ledger->kode_peruntukan == 'MP' ? 'selected' : '' }}>MP</option>
                    <option value="PC" {{ $ledger->kode_peruntukan == 'PC' ? 'selected' : '' }}>PACIFIC</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>

        <!-- Cabang (Keterangan Peruntukan Jika PC) -->
        <div id="containerCabangEdit" class="{{ $ledger->kode_peruntukan == 'PC' ? '' : 'hidden' }} space-y-1">
            <label for="kode_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_edit" class="select2Kodecabangedit w-full">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}" {{ $ledger->keterangan_peruntukan == $d->kode_cabang ? 'selected' : '' }}>
                            {{ textUpperCase($d->nama_cabang) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p id="error-cabang-edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Cabang wajib dipilih</span>
            </p>
        </div>
    </div>

    <!-- Tombol Aksi (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelEditLedger"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnUpdateLedger"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditLedger");

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

        const $select2Bank = $form.find('.select2Kodebankedit');
        if ($select2Bank.length) {
            $select2Bank.select2({
                placeholder: 'Pilih Bank',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        const $select2Akun = $form.find('.select2Kodeakunedit');
        if ($select2Akun.length) {
            $select2Akun.select2({
                placeholder: 'Pilih Kode Akun',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        const $select2Cabang = $form.find('.select2Kodecabangedit');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Pilih Cabang',
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
            clearError($(this), $form.find("#error-bank-edit"));
        });
        $form.find("#tanggal_edit").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-edit"));
            checkTutupLaporanEdit();
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
        $form.find("#kode_cabang_edit").on('change select2:select', function() {
            clearError($(this), $form.find("#error-cabang-edit"));
        });

        $form.find("#kode_peruntukan_edit").on('change', function() {
            if ($(this).val() === 'PC') {
                $form.find("#containerCabangEdit").removeClass('hidden');
            } else {
                $form.find("#containerCabangEdit").addClass('hidden');
                $form.find("#kode_cabang_edit").val('').trigger('change');
            }
        });

        $form.find("#btnCancelEditLedger").on('click', function() {
            if (typeof modalEditLedgerHelper !== 'undefined') {
                modalEditLedgerHelper.close();
            } else {
                $("#modalEditLedger").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        function checkTutupLaporanEdit() {
            const tanggal = $form.find("#tanggal_edit").val();
            if (!tanggal) return;
            $.ajax({
                type: 'POST',
                url: '/tutuplaporan/cektutuplaporan',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: "ledger"
                },
                cache: false,
                success: function(response) {
                    $form.find("#cektutuplaporan_edit").val(response);
                    if (parseInt(response) > 0) {
                        showError($form.find("#tanggal_edit"), $form.find("#error-tanggal-edit"), "Periode laporan sudah ditutup!");
                    }
                }
            });
        }

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
                showError($form.find("#kode_bank_edit"), $form.find("#error-bank-edit"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_bank_edit");
            }

            const cektutup = parseInt($form.find("#cektutuplaporan_edit").val()) || 0;
            const tanggal = $form.find("#tanggal_edit").val().trim();
            if (cektutup > 0) {
                showError($form.find("#tanggal_edit"), $form.find("#error-tanggal-edit"), "Periode laporan sudah ditutup!");
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_edit");
            } else if (!tanggal) {
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

            const kode_peruntukan = $form.find("#kode_peruntukan_edit").val();
            const kode_cabang = $form.find("#kode_cabang_edit").val();
            if (kode_peruntukan === 'PC' && !kode_cabang) {
                showError($form.find("#kode_cabang_edit"), $form.find("#error-cabang-edit"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_cabang_edit");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnUpdateLedger");
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
