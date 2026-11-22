<form action="{{ route('fsthp.store') }}" id="formcreateFsthp" method="POST" class="space-y-3">
    @csrf
    <input type="hidden" id="cektutuplaporan">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- No. FSTHP -->
        <div class="field-group">
            <label for="no_mutasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. FSTHP <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_mutasi" id="no_mutasi"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-brand-600 dark:text-brand-400 focus:outline-hidden transition"
                    placeholder="[Auto-Generated]" readonly>
            </div>
        </div>

        <!-- Tanggal FSTHP -->
        <div class="field-group">
            <label for="tanggal_mutasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal FSTHP <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal_mutasi" id="tanggal_mutasi"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Pilih Tanggal Mutasi" readonly>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
        <!-- Unit -->
        <div class="sm:col-span-4 field-group">
            <label for="unit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Unit Produksi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="unit" id="unit"
                    class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Unit</option>
                    <option value="1">Unit 1</option>
                    <option value="2">Unit 2</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <!-- Produk -->
        <div class="sm:col-span-8 field-group">
            <label for="kode_produk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Produk <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="package" class="w-4 h-4"></i>
                </div>
                <select name="kode_produk" id="kode_produk" class="select2Kodeproduk w-full">
                    <option value="">Pilih Produk</option>
                    @foreach ($produk as $d)
                        <option value="{{ $d->kode_produk }}">{{ $d->kode_produk }} - {{ $d->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Shift -->
        <div class="field-group">
            <label for="shift" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Shift Kerja <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <select name="shift" id="shift"
                    class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Shift</option>
                    <option value="1">Shift 1</option>
                    <option value="2">Shift 2</option>
                    <option value="3">Shift 3</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <!-- Jumlah -->
        <div class="field-group">
            <label for="jumlah" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jumlah Serah Terima (Dus) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jumlah" id="jumlah"
                    class="money w-full h-[36px] pl-9 pr-3 text-xs font-bold text-right font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="0">
            </div>
        </div>
    </div>

    <!-- SUBMIT BUTTON -->
    <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" class="btn-cancel-modal h-[34px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer" data-bs-dismiss="modal">
            Batal
        </button>
        <button type="submit" id="btnSimpan"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan FSTHP</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formcreateFsthp");
        const $btnSimpan = $form.find("#btnSimpan");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Standard Error State Helpers
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');

            if ($input.hasClass('select2-hidden-accessible')) {
                $container.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }

            $container.find('.field-error-msg').remove();
            $container.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3 h-3 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');

            if ($input.hasClass('select2-hidden-accessible')) {
                $container.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }

            $container.find('.field-error-msg').remove();
        }

        // Live Cleanup on Type / Change
        $form.find('input, select').on('input change', function() {
            clearFieldError($(this));
        });

        // Flatpickr
        $form.find("#tanggal_mutasi").flatpickr({
            dateFormat: "Y-m-d",
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }],
            onChange: function() {
                clearFieldError($form.find("#tanggal_mutasi"));
            }
        });

        // MaskMoney
        if ($.fn.maskMoney) {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0
            });
        }

        // Select2
        $form.find('.select2Kodeproduk').select2({
            placeholder: 'Pilih Produk',
            width: '100%'
        }).on('change select2:select', function() {
            clearFieldError($(this));
        });

        function generatenofsthp() {
            const tanggal_mutasi = $form.find("#tanggal_mutasi").val();
            const kode_produk = $form.find("#kode_produk").val();
            const shift = $form.find("#shift").val();
            if (tanggal_mutasi && kode_produk && shift) {
                $.ajax({
                    type: 'POST',
                    url: '/fsthp/generatenofsthp',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal_mutasi: tanggal_mutasi,
                        kode_produk: kode_produk,
                        shift: shift
                    },
                    cache: false,
                    success: function(respond) {
                        $form.find("#no_mutasi").val(respond);
                        clearFieldError($form.find("#no_mutasi"));
                    }
                });
            }
        }

        function cektutuplaporan(tanggal, jenis_laporan) {
            $.ajax({
                type: "POST",
                url: "/tutuplaporan/cektutuplaporan",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: jenis_laporan
                },
                cache: false,
                success: function(respond) {
                    $form.find("#cektutuplaporan").val(respond);
                }
            });
        }

        $form.find("#kode_produk").on("change", function() {
            generatenofsthp();
        });

        $form.find("#tanggal_mutasi").on("change", function() {
            generatenofsthp();
            cektutuplaporan($(this).val(), "produksi");
        });

        $form.find("#shift").on("change", function() {
            generatenofsthp();
        });

        // Form Submit Validation
        $form.on("submit", function(e) {
            let hasError = false;
            let $firstErrorField = null;

            function validateField($field, condition, message) {
                if (condition) {
                    setFieldError($field, message);
                    if (!hasError) {
                        hasError = true;
                        $firstErrorField = $field;
                    }
                } else {
                    clearFieldError($field);
                }
            }

            const $noMutasi = $form.find("#no_mutasi");
            const $tglMutasi = $form.find("#tanggal_mutasi");
            const $unit = $form.find("#unit");
            const $kdProduk = $form.find("#kode_produk");
            const $shift = $form.find("#shift");
            const $jumlah = $form.find("#jumlah");
            const cektutuplaporan = $form.find("#cektutuplaporan").val();

            validateField($tglMutasi, !$tglMutasi.val(), "Tanggal FSTHP wajib dipilih");
            validateField($unit, !$unit.val(), "Unit produksi wajib dipilih");
            validateField($kdProduk, !$kdProduk.val(), "Produk wajib dipilih");
            validateField($shift, !$shift.val(), "Shift kerja wajib dipilih");
            validateField($jumlah, !$jumlah.val() || $jumlah.val() === "0", "Jumlah serah terima wajib diisi angka > 0");
            validateField($noMutasi, !$noMutasi.val(), "No. FSTHP belum terbentuk. Pastikan tanggal, produk, dan shift sudah dipilih");

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    if ($firstErrorField.hasClass('select2-hidden-accessible')) {
                        $firstErrorField.select2('open');
                    } else {
                        $firstErrorField.focus();
                    }
                }
                return false;
            }

            if (cektutuplaporan === '1') {
                e.preventDefault();
                Swal.fire("Oops!", "Laporan untuk periode tanggal ini sudah ditutup!", "warning");
                return false;
            }

            // Prevent Double Submit
            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan FSTHP...</span>
            `);
            return true;
        });
    });
</script>
