<form action="{{ route('sakasbesar.store') }}" method="POST" id="formCreatesaldoawal" class="space-y-3.5" autocomplete="off">
    @csrf
    <input type="hidden" name="cekgetsaldo" id="cekgetsaldo" value="0">

    <!-- Row 1: Cabang -->
    <div class="space-y-1">
        <label for="kode_cabang_sa" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Cabang <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="building-2" class="w-4 h-4"></i>
            </div>
            <select name="kode_cabang" id="kode_cabang_sa" class="select2Kodecabang w-full">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $c)
                    <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        <p id="error-cabang-sa" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Cabang wajib dipilih</span>
        </p>
    </div>

    <!-- Row 2: Periode Bulan, Tahun, & Tombol Get Saldo (Anti-Layout Shift: items-start with spacer label) -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
        <!-- Bulan -->
        <div class="sm:col-span-5 space-y-1">
            <label for="bulan_sa" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Bulan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                </div>
                <select name="bulan" id="bulan_sa"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none">
                    <option value="">Pilih Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
            <p id="error-bulan-sa" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Bulan wajib dipilih</span>
            </p>
        </div>

        <!-- Tahun -->
        <div class="sm:col-span-4 space-y-1">
            <label for="tahun_sa" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <select name="tahun" id="tahun_sa"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none">
                    <option value="">Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
            <p id="error-tahun-sa" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tahun wajib dipilih</span>
            </p>
        </div>

        <!-- Tombol Get Saldo (Anti-Layout Shift with Spacer Label) -->
        <div class="sm:col-span-3">
            <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
            <button type="button" id="btnGetSaldo"
                class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                <span>Get Saldo</span>
            </button>
        </div>
    </div>

    <!-- Alert Cek Get Saldo -->
    <div id="alertGetSaldo" class="hidden rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 p-2.5 text-xs text-amber-700 dark:text-amber-300 flex items-center gap-2">
        <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600 shrink-0"></i>
        <span>Silakan klik tombol <b>Get Saldo</b> terlebih dahulu sebelum menyimpan data!</span>
    </div>

    <!-- Row 3: Rincian Saldo (Uang Kertas, Logam, Transfer, Giro) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 p-3 sm:p-3.5 space-y-3">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="wallet" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-200">Rincian Nominal Saldo Awal</h6>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <!-- Uang Kertas -->
            <div class="space-y-1">
                <label for="uang_kertas" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Uang Kertas (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="banknote" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="uang_kertas" name="uang_kertas" placeholder="0" readonly
                        class="w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white">
                </div>
            </div>

            <!-- Uang Logam -->
            <div class="space-y-1">
                <label for="uang_logam" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Uang Logam (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="coins" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="uang_logam" name="uang_logam" placeholder="0" readonly
                        class="w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white">
                </div>
            </div>

            <!-- Transfer -->
            <div class="space-y-1">
                <label for="transfer_sa" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Transfer (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="transfer_sa" name="transfer" placeholder="0" readonly
                        class="w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white">
                </div>
            </div>

            <!-- Giro -->
            <div class="space-y-1">
                <label for="giro_sa" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Giro (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="file-check-2" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="giro_sa" name="giro" placeholder="0" readonly
                        class="w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white">
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelSaldoAwal"
            class="h-[34px] px-4 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanSaldoAwal"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Saldo Awal</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formCreatesaldoawal");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Select2
        $form.find('.select2Kodecabang').select2({
            placeholder: 'Pilih Cabang',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number || 0);
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

        $form.find("#kode_cabang_sa").on('change', function() {
            clearError($form.find('.select2-container-v2'), $form.find("#error-cabang-sa"));
            $form.find("#cekgetsaldo").val(0);
        });
        $form.find("#bulan_sa").on('change', function() {
            clearError($(this), $form.find("#error-bulan-sa"));
            $form.find("#cekgetsaldo").val(0);
        });
        $form.find("#tahun_sa").on('change', function() {
            clearError($(this), $form.find("#error-tahun-sa"));
            $form.find("#cekgetsaldo").val(0);
        });

        $form.find("#btnCancelSaldoAwal").on('click', function() {
            if (typeof modalCreateSaldoAwal !== 'undefined') {
                modalCreateSaldoAwal.close();
            } else {
                $("#modalCreateSaldoAwal").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        // Get Saldo AJAX
        $form.find("#btnGetSaldo").on('click', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const kode_cabang = $form.find("#kode_cabang_sa").val();
            const bulan = $form.find("#bulan_sa").val();
            const tahun = $form.find("#tahun_sa").val();

            if (!kode_cabang) {
                showError($form.find('.select2-container-v2'), $form.find("#error-cabang-sa"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_cabang_sa");
            }
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

            if (hasError) {
                if ($firstError) $firstError.focus();
                return;
            }

            const $btn = $(this);
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Loading...</span>
            `);

            $.ajax({
                type: 'POST',
                url: '/sakasbesar/getsaldo',
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun,
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(response) {
                    $form.find("#uang_kertas").val(formatRupiah(response.data.uang_kertas));
                    $form.find("#uang_logam").val(formatRupiah(response.data.uang_logam));
                    $form.find("#transfer_sa").val(formatRupiah(response.data.transfer));
                    $form.find("#giro_sa").val(formatRupiah(response.data.giro));
                    $form.find("#cekgetsaldo").val(1);
                    $form.find("#alertGetSaldo").addClass('hidden');
                },
                error: function() {
                    $form.find("#alertGetSaldo").removeClass('hidden').html(`
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                        <span class="text-rose-600">Gagal mengambil data saldo. Pastikan parameter periode valid.</span>
                    `);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                },
                complete: function() {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                    $btn.html(`
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        <span>Get Saldo</span>
                    `);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        });

        // Submit form
        $form.on('submit', function(e) {
            e.preventDefault();
            const cekgetsaldo = $form.find("#cekgetsaldo").val();

            if (cekgetsaldo !== '1') {
                $form.find("#alertGetSaldo").removeClass('hidden');
                if (typeof lucide !== 'undefined') lucide.createIcons();
                $form.find("#btnGetSaldo").focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanSaldoAwal");
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
