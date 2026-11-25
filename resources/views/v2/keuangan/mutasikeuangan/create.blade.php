<form action="{{ route('mutasikeuangan.store') }}" method="POST" id="formCreateMutasiKeuangan" class="space-y-3" autocomplete="off">
    @csrf
    <input type="hidden" id="cektutuplaporan">

    @if ($level_user == 'staff keuangan 2')
        <input type="hidden" name="kode_bank" id="kode_bank_mk_create" value="BK070">
    @else
        <!-- Bank Rekening -->
        <div class="space-y-1">
            <label for="kode_bank_mk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Bank Rekening <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="landmark" class="w-4 h-4"></i>
                </div>
                <select name="kode_bank" id="kode_bank_mk_create" class="select2Kodebankmk w-full">
                    <option value="">Pilih Bank</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}">
                            {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p id="error-bank-mk-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Bank rekening wajib dipilih</span>
            </p>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Tanggal -->
        <div class="space-y-1">
            <label for="tanggal_mk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Transaksi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_mk_create" name="tanggal" value="{{ date('Y-m-d') }}" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-mk-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal transaksi wajib diisi</span>
            </p>
        </div>

        <!-- No. Bukti -->
        <div class="space-y-1">
            <label for="no_bukti_mk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                No. Bukti
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" id="no_bukti_mk_create" name="no_bukti" placeholder="No. Bukti (opsional)"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="space-y-1">
        <label for="keterangan_mk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" id="keterangan_mk_create" name="keterangan" placeholder="Keterangan mutasi keuangan"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-keterangan-mk-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Keterangan wajib diisi</span>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Debet / Kredit -->
        <div class="space-y-1">
            <label for="debet_kredit_mk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Jenis Transaksi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                </div>
                <select id="debet_kredit_mk_create" name="debet_kredit"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Debet / Kredit</option>
                    <option value="D">Debet (Pengeluaran)</option>
                    <option value="K">Kredit (Penerimaan)</option>
                </select>
            </div>
            <p id="error-dk-mk-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Debet / Kredit wajib dipilih</span>
            </p>
        </div>

        <!-- Jumlah -->
        <div class="space-y-1">
            <label for="jumlah_mk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Jumlah <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                </div>
                <input type="text" id="jumlah_mk_create" name="jumlah" placeholder="0"
                    class="money w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-jumlah-mk-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Jumlah wajib diisi lebih dari 0</span>
            </p>
        </div>
    </div>

    <!-- Kategori -->
    <div class="space-y-1">
        <label for="kode_kategori_mk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Kategori Mutasi <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="tag" class="w-4 h-4"></i>
            </div>
            <select id="kode_kategori_mk_create" name="kode_kategori"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                <option value="">Pilih Kategori</option>
                @foreach ($kategori as $d)
                    <option value="{{ $d->kode_kategori }}">{{ $d->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <p id="error-kategori-mk-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Kategori wajib dipilih</span>
        </p>
    </div>

    <!-- Tombol Aksi Kanan (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelMkCreate"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanMkCreate"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Mutasi</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formCreateMutasiKeuangan");

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $form.find(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0
        });

        const $select2Bank = $form.find('.select2Kodebankmk');
        if ($select2Bank.length) {
            $select2Bank.select2({
                placeholder: 'Pilih Bank',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#kode_bank_mk_create").on('change select2:select', function() {
            clearError($(this), $form.find("#error-bank-mk-create"));
        });
        $form.find("#tanggal_mk_create").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-mk-create"));
        });
        $form.find("#keterangan_mk_create").on('input change', function() {
            clearError($(this), $form.find("#error-keterangan-mk-create"));
        });
        $form.find("#debet_kredit_mk_create").on('change', function() {
            clearError($(this), $form.find("#error-dk-mk-create"));
        });
        $form.find("#jumlah_mk_create").on('input change keyup', function() {
            clearError($(this), $form.find("#error-jumlah-mk-create"));
        });
        $form.find("#kode_kategori_mk_create").on('change', function() {
            clearError($(this), $form.find("#error-kategori-mk-create"));
        });

        $form.find("#btnCancelMkCreate").on('click', function() {
            if (typeof modalMutasiKeuanganHelper !== 'undefined') {
                modalMutasiKeuanganHelper.close();
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const $bank = $form.find("#kode_bank_mk_create");
            if (!$bank.val()) {
                showError($bank, $form.find("#error-bank-mk-create"));
                hasError = true;
                if (!$firstError) $firstError = $bank;
            }

            const $tanggal = $form.find("#tanggal_mk_create");
            if (!$tanggal.val().trim()) {
                showError($tanggal, $form.find("#error-tanggal-mk-create"));
                hasError = true;
                if (!$firstError) $firstError = $tanggal;
            }

            const $ket = $form.find("#keterangan_mk_create");
            if (!$ket.val().trim()) {
                showError($ket, $form.find("#error-keterangan-mk-create"));
                hasError = true;
                if (!$firstError) $firstError = $ket;
            }

            const $dk = $form.find("#debet_kredit_mk_create");
            if (!$dk.val()) {
                showError($dk, $form.find("#error-dk-mk-create"));
                hasError = true;
                if (!$firstError) $firstError = $dk;
            }

            const $jml = $form.find("#jumlah_mk_create");
            const jmlVal = $jml.val() ? parseInt($jml.val().replace(/\./g, '')) || 0 : 0;
            if (jmlVal <= 0) {
                showError($jml, $form.find("#error-jumlah-mk-create"));
                hasError = true;
                if (!$firstError) $firstError = $jml;
            }

            const $kat = $form.find("#kode_kategori_mk_create");
            if (!$kat.val()) {
                showError($kat, $form.find("#error-kategori-mk-create"));
                hasError = true;
                if (!$firstError) $firstError = $kat;
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanMkCreate");
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
