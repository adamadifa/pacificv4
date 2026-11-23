@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $user = auth()->user();
    $hasAccessAllCabang = $user->hasRole($roles_show_cabang);
@endphp

<form action="{{ route('penjualan.cetaksuratjalanrange') }}" target="_blank" method="POST" id="formCetakfaktur" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- SECTION 1: PERIODE TANGGAL -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="calendar-range" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Periode Surat Jalan</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
            <!-- Dari -->
            <div class="field-group">
                <label for="dari_cetak" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Dari Tanggal <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="dari" id="dari_cetak" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
                <p class="field-error-msg hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3 h-3 text-rose-500 inline shrink-0"></i>
                    <span class="error-text">Tanggal awal wajib diisi</span>
                </p>
            </div>

            <!-- Sampai -->
            <div class="field-group">
                <label for="sampai_cetak" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Sampai Tanggal <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_cetak" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
                <p class="field-error-msg hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3 h-3 text-rose-500 inline shrink-0"></i>
                    <span class="error-text">Tanggal akhir wajib diisi</span>
                </p>
            </div>
        </div>
    </div>

    <!-- SECTION 2: CABANG & SALESMAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="filter" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Filter Cabang &amp; Salesman</span>
        </div>

        <div class="space-y-2.5">
            @if ($hasAccessAllCabang)
                <!-- Cabang -->
                <div class="field-group">
                    <label for="kode_cabang_cetak" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Cabang
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang_cetak"
                            class="select2KodecabangCetak select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ $c->kode_cabang }} - {{ textUpperCase($c->nama_cabang) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <!-- Salesman -->
            <div class="field-group">
                <label for="kode_salesman_cetak" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Salesman
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_salesman" id="kode_salesman_cetak"
                        class="select2KodesalesmanCetak select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Semua Salesman</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-100 dark:border-slate-800">
        <button type="button" class="btnCancel h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitCetak"
            class="h-[34px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
            <span>Cetak Surat Jalan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formCetakfaktur");
        const $dari = $form.find("#dari_cetak");
        const $sampai = $form.find("#sampai_cetak");
        const $btnSubmit = $form.find("#btnSubmitCetak");

        // Tutup modal via tombol Batal
        $form.find(".btnCancel").on('click', function() {
            $("#modalPenjualan").addClass("hidden").removeClass("flex");
        });

        // Initialize Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        // Initialize Select2 Cabang
        const $select2Cabang = $form.find('.select2KodecabangCetak');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Semua Cabang',
                allowClear: true,
                width: '100%'
            });
        }

        // Initialize Select2 Salesman
        const $select2Salesman = $form.find('.select2KodesalesmanCetak');
        if ($select2Salesman.length) {
            $select2Salesman.select2({
                placeholder: 'Semua Salesman',
                allowClear: true,
                width: '100%'
            });
        }

        // Load Salesman by Cabang
        function getsalesmanbyCabang() {
            const kode_cabang = $form.find("#kode_cabang_cetak").val();
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_salesman_cetak").html('<option value="">Semua Salesman</option>' + respond);
                    if ($select2Salesman.length) {
                        $select2Salesman.trigger('change.select2');
                    }
                }
            });
        }

        $form.find("#kode_cabang_cetak").on('change', function() {
            getsalesmanbyCabang();
        });
        getsalesmanbyCabang();

        // Live cleanup
        $form.find("#dari_cetak, #sampai_cetak").on('input change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $(this).closest('.field-group').find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $(this).closest('.field-group').find('.field-error-msg').addClass('hidden');
        });

        $form.on('submit', function(e) {
            let hasError = false;
            let firstError = null;

            const dariVal = $dari.val().trim();
            const sampaiVal = $sampai.val().trim();

            if (!dariVal) {
                hasError = true;
                $dari.addClass('border-rose-500 ring-1 ring-rose-500');
                $dari.closest('.field-group').find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
                $dari.closest('.field-group').find('.field-error-msg').removeClass('hidden').find('.error-text').text('Periode Dari wajib diisi!');
                if (!firstError) firstError = $dari;
            }

            if (!sampaiVal) {
                hasError = true;
                $sampai.addClass('border-rose-500 ring-1 ring-rose-500');
                $sampai.closest('.field-group').find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
                $sampai.closest('.field-group').find('.field-error-msg').removeClass('hidden').find('.error-text').text('Periode Sampai wajib diisi!');
                if (!firstError) firstError = $sampai;
            } else if (dariVal && new Date(dariVal) > new Date(sampaiVal)) {
                hasError = true;
                $sampai.addClass('border-rose-500 ring-1 ring-rose-500');
                $sampai.closest('.field-group').find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
                $sampai.closest('.field-group').find('.field-error-msg').removeClass('hidden').find('.error-text').text('Periode Sampai tidak boleh sebelum Dari!');
                if (!firstError) firstError = $sampai;
            }

            if (hasError) {
                e.preventDefault();
                if (firstError) firstError.focus();
                return false;
            }

            // Spinner dengan timeout 2.5s karena target _blank
            const originalContent = $btnSubmit.html();
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mencetak...</span>
            `);

            setTimeout(() => {
                $btnSubmit.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                $btnSubmit.html(originalContent);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }, 2500);
        });
    });
</script>
