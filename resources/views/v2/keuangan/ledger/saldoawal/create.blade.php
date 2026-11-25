<form action="{{ request()->is('samutasibank*') ? route('samutasibank.store') : route('saledger.store') }}" id="formSaldoawalledger" method="POST" class="space-y-3" autocomplete="off">
    @csrf
    <input type="hidden" name="cekgetsaldo" id="cekgetsaldo" value="0">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Bulan -->
        <div class="space-y-1">
            <label for="bulan_sa" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Bulan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <select name="bulan" id="bulan_sa"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <p id="error-bulan-sa" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Bulan wajib dipilih</span>
            </p>
        </div>

        <!-- Tahun -->
        <div class="space-y-1">
            <label for="tahun_sa" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                </div>
                <select name="tahun" id="tahun_sa"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <p id="error-tahun-sa" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tahun wajib dipilih</span>
            </p>
        </div>
    </div>

    <!-- Bank Rekening -->
    <div class="space-y-1">
        <label for="kode_bank_sa" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Bank Rekening <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="landmark" class="w-4 h-4"></i>
            </div>
            <select name="kode_bank" id="kode_bank_sa" class="select2Kodebanksa w-full">
                <option value="">Pilih Bank</option>
                @foreach ($bank as $d)
                    <option value="{{ $d->kode_bank }}">
                        {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <p id="error-bank-sa" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Bank rekening wajib dipilih</span>
        </p>
    </div>

    <!-- Jumlah Saldo & Tombol Get Saldo (Anti-Layout Shift Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
        <div class="space-y-1 sm:col-span-8">
            <label for="jumlah_sa" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Jumlah Saldo Awal (Rp) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <span class="text-xs font-bold text-slate-400">Rp</span>
                </div>
                <input type="text" id="jumlah_sa" name="jumlah" readonly placeholder="0"
                    class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/80 text-xs font-black text-slate-800 dark:text-slate-100 text-right font-mono-num placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-jumlah-sa" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Jumlah saldo awal wajib diisi</span>
            </p>
        </div>

        <!-- Tombol Get Saldo (with spacer label to prevent layout jump) -->
        <div class="space-y-1 sm:col-span-4">
            <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
            <button type="button" id="btnGetSaldo"
                class="w-full h-[36px] px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                <span>Get Saldo</span>
            </button>
        </div>
    </div>

    <!-- Tombol Aksi (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelSaldoawal"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanSaldoawal"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Saldo Awal</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formSaldoawalledger");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        function convertToRupiah(number) {
            if (number !== null && number !== undefined) {
                var rupiah = "";
                var numberrev = number.toString().split("").reverse().join("");
                for (var i = 0; i < numberrev.length; i++) {
                    if (i % 3 === 0) rupiah += numberrev.substr(i, 3) + ".";
                }
                return rupiah.split("", rupiah.length - 1).reverse().join("");
            }
            return "0";
        }

        const $select2Bank = $form.find('.select2Kodebanksa');
        if ($select2Bank.length) {
            $select2Bank.select2({
                placeholder: 'Pilih Bank',
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

        $form.find("#bulan_sa").on('change', function() {
            clearError($(this), $form.find("#error-bulan-sa"));
            $form.find("#cekgetsaldo").val(0);
        });

        $form.find("#tahun_sa").on('change', function() {
            clearError($(this), $form.find("#error-tahun-sa"));
            $form.find("#cekgetsaldo").val(0);
        });

        $form.find("#kode_bank_sa").on('change select2:select', function() {
            clearError($(this), $form.find("#error-bank-sa"));
            $form.find("#cekgetsaldo").val(0);
        });

        $form.find("#jumlah_sa").on('input change', function() {
            clearError($(this), $form.find("#error-jumlah-sa"));
        });

        $form.find("#btnCancelSaldoawal").on('click', function() {
            if (typeof modalSaldoawalHelper !== 'undefined') {
                modalSaldoawalHelper.close();
            } else {
                $("#modalSaldoawal").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        // Trigger Get Saldo
        $form.find("#btnGetSaldo").on('click', function(e) {
            e.preventDefault();
            const bulan = $form.find("#bulan_sa").val();
            const tahun = $form.find("#tahun_sa").val();
            const kode_bank = $form.find("#kode_bank_sa").val();

            let hasError = false;
            let $firstError = null;

            if (!bulan) {
                showError($form.find("#bulan_sa"), $form.find("#error-bulan-sa"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#bulan_sa");
            }

            if (!tahun) {
                showError($form.find("#tahun_sa"), $form.find("#error-tahun-sa"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tahun_sa");
            }

            if (!kode_bank) {
                showError($form.find("#kode_bank_sa"), $form.find("#error-bank-sa"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_bank_sa");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.prop('disabled', true).html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Loading...</span>
            `);

            const urlGetSaldo = "{{ request()->is('samutasibank*') ? '/samutasibank/getsaldo' : '/saledger/getsaldo' }}";

            $.ajax({
                type: 'POST',
                url: urlGetSaldo,
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun,
                    kode_bank: kode_bank
                },
                cache: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalHtml);
                    if (typeof lucide !== 'undefined') lucide.createIcons();

                    if (response.data.ceksaldo == 0) {
                        $form.find("#jumlah_sa").prop('readonly', false).removeClass('bg-slate-100 dark:bg-slate-800/80').addClass('bg-white dark:bg-slate-800');
                        $form.find("#jumlah_sa").maskMoney({
                            thousands: '.',
                            decimal: ',',
                            precision: 0,
                            allowZero: true
                        });
                        $form.find("#jumlah_sa").focus();
                        $form.find("#cekgetsaldo").val(1);
                        clearError($form.find("#jumlah_sa"), $form.find("#error-jumlah-sa"));
                    } else if (response.data.ceksaldobulanlalu == 0 && response.data.ceksaldobulanini == 0) {
                        Swal.fire({
                            title: "Saldo Belum Di-Set!",
                            text: "Saldo bulan sebelumnya untuk bank ini belum di-set!",
                            icon: "warning",
                            confirmButtonColor: '#284c9a'
                        });
                        $form.find("#cekgetsaldo").val(0);
                    } else {
                        $form.find("#cekgetsaldo").val(1);
                        $form.find("#jumlah_sa").val(convertToRupiah(response.data.saldo));
                        $form.find("#jumlah_sa").prop('readonly', true).addClass('bg-slate-100 dark:bg-slate-800/80').removeClass('bg-white dark:bg-slate-800');
                        clearError($form.find("#jumlah_sa"), $form.find("#error-jumlah-sa"));
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).html(originalHtml);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    Swal.fire({
                        title: "Gagal!",
                        text: "Gagal mengambil saldo awal. Silakan coba kembali.",
                        icon: "error",
                        confirmButtonColor: '#284c9a'
                    });
                }
            });
        });

        // Submit listener
        $form.on('submit', function(e) {
            e.preventDefault();
            const cekgetsaldo = $form.find("#cekgetsaldo").val();
            const jumlah = $form.find("#jumlah_sa").val().trim();

            if (cekgetsaldo === '0' || !jumlah) {
                Swal.fire({
                    title: "Perhatian!",
                    text: "Silakan klik tombol 'Get Saldo' terlebih dahulu untuk menghitung atau memasukkan saldo awal!",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                });
                return false;
            }

            const $btn = $form.find("#btnSimpanSaldoawal");
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
