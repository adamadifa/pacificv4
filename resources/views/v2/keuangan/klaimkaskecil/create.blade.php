<form action="{{ route('klaimkaskecil.store') }}" id="formCreateKlaimKasKecil" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- SECTION 1: FILTER GET DATA -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 space-y-3">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="filter" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-700 dark:text-slate-200">Filter Periode Transaksi Kas Kecil</h6>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 @hasanyrole($roles_show_cabang) lg:grid-cols-12 @else lg:grid-cols-9 @endhasanyrole gap-2.5 items-start">
            <!-- Dari Tanggal -->
            <div class="space-y-1 @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-3 @endhasanyrole">
                <label for="dari_klaim" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Dari Tanggal <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="dari_klaim" name="dari" readonly placeholder="Pilih Tanggal"
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
                <p id="error-dari-klaim" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Dari tanggal wajib diisi</span>
                </p>
            </div>

            <!-- Sampai Tanggal -->
            <div class="space-y-1 @hasanyrole($roles_show_cabang) lg:col-span-3 @else lg:col-span-3 @endhasanyrole">
                <label for="sampai_klaim" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Sampai Tanggal <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="sampai_klaim" name="sampai" readonly placeholder="Pilih Tanggal"
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
                <p id="error-sampai-klaim" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Sampai tanggal wajib diisi</span>
                </p>
            </div>

            <!-- Cabang (Jika Multi-Cabang) -->
            @hasanyrole($roles_show_cabang)
                <div class="space-y-1 lg:col-span-4">
                    <label for="kode_cabang_klaim" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                        Cabang <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative select2-container-v2">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang_klaim" class="select2Kodecabangklaim w-full">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $d)
                                <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p id="error-cabang-klaim" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>Cabang wajib dipilih</span>
                    </p>
                </div>
            @endhasanyrole

            <!-- Tombol Get Data -->
            <div class="space-y-1 @hasanyrole($roles_show_cabang) lg:col-span-2 @else lg:col-span-3 @endhasanyrole">
                <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="btngetData"
                    class="w-full h-[36px] px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Get Data</span>
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION 2: INFORMASI KLAIM -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Tanggal Klaim -->
        <div class="space-y-1">
            <label for="tanggal_pengajuan_klaim" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Pengajuan Klaim <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_pengajuan_klaim" name="tanggal" readonly placeholder="Pilih Tanggal Klaim"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-pengajuan" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal pengajuan klaim wajib diisi</span>
            </p>
        </div>

        <!-- Keterangan Klaim -->
        <div class="space-y-1">
            <label for="keterangan_klaim" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Keterangan Klaim <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
                <input type="text" id="keterangan_klaim" name="keterangan" placeholder="Contoh: Klaim Kas Kecil Periode..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-keterangan-klaim" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Keterangan klaim wajib diisi</span>
            </p>
        </div>
    </div>

    <!-- SECTION 3: HASIL GET DATA TRANSAKSI -->
    <div id="loaddata" class="space-y-2">
        <div class="rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 p-8 text-center text-slate-400 dark:text-slate-500">
            <div class="flex flex-col items-center justify-center gap-2">
                <i data-lucide="search" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                <span class="text-xs font-semibold">Silakan tentukan periode tanggal & cabang, lalu klik tombol "Get Data" untuk memuat daftar transaksi kas kecil</span>
            </div>
        </div>
    </div>

    <!-- TOMBOL AKSI SUBMIT (Right-Aligned) -->
    <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
        <div>
            <span id="selectedCountBadge" class="hidden px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 font-mono-num">
                0 Dipilih
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="btnCancelCreateKlaim"
                class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpanKlaim"
                class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Buat Klaim Kas Kecil</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formCreateKlaimKasKecil");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        const $select2Cabang = $form.find('.select2Kodecabangklaim');
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

        $form.find("#dari_klaim").on('input change', function() {
            clearError($(this), $form.find("#error-dari-klaim"));
        });
        $form.find("#sampai_klaim").on('input change', function() {
            clearError($(this), $form.find("#error-sampai-klaim"));
        });
        $form.find("#kode_cabang_klaim").on('change select2:select', function() {
            clearError($(this), $form.find("#error-cabang-klaim"));
        });
        $form.find("#tanggal_pengajuan_klaim").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-pengajuan"));
        });
        $form.find("#keterangan_klaim").on('input change', function() {
            clearError($(this), $form.find("#error-keterangan-klaim"));
        });

        $form.find("#btnCancelCreateKlaim").on('click', function() {
            if (typeof modalKlaimHelper !== 'undefined') {
                modalKlaimHelper.close();
            } else {
                $("#modalKlaim").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        // Action: Get Data
        $form.find("#btngetData").on("click", function(e) {
            e.preventDefault();
            const dari = $form.find("#dari_klaim").val();
            const sampai = $form.find("#sampai_klaim").val();
            const $cabang = $form.find("#kode_cabang_klaim");
            const kode_cabang = $cabang.length ? $cabang.val() : '';

            let hasError = false;
            let $firstError = null;

            if (!dari) {
                showError($form.find("#dari_klaim"), $form.find("#error-dari-klaim"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#dari_klaim");
            }

            if (!sampai) {
                showError($form.find("#sampai_klaim"), $form.find("#error-sampai-klaim"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#sampai_klaim");
            }

            if ($cabang.length && !kode_cabang) {
                showError($cabang, $form.find("#error-cabang-klaim"));
                hasError = true;
                if (!$firstError) $firstError = $cabang;
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $(this);
            const originalText = $btn.html();
            $btn.prop('disabled', true).html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat Data...</span>
            `);

            $form.find("#loaddata").html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-500 font-semibold">Mengambil data transaksi kas kecil...</span>
                </div>
            `);

            $.ajax({
                type: 'POST',
                url: '/klaimkaskecil/getdata',
                data: {
                    _token: "{{ csrf_token() }}",
                    dari: dari,
                    sampai: sampai,
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(data) {
                    $form.find("#loaddata").html(data);
                    $btn.prop('disabled', false).html(originalText);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                },
                error: function() {
                    $btn.prop('disabled', false).html(originalText);
                    $form.find("#loaddata").html(`
                        <div class="rounded-xl border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/30 p-4 text-center text-rose-600 dark:text-rose-400 text-xs font-semibold">
                            Gagal memuat data transaksi. Silakan coba lagi.
                        </div>
                    `);
                }
            });
        });

        // Submit listener
        $form.on('submit', function(e) {
            e.preventDefault();
            const tanggal = $form.find("#tanggal_pengajuan_klaim").val().trim();
            const keterangan = $form.find("#keterangan_klaim").val().trim();
            const checkedCount = $form.find("input[name='id_kaskecil[]']:checked").length;

            let hasError = false;
            let $firstError = null;

            if (!tanggal) {
                showError($form.find("#tanggal_pengajuan_klaim"), $form.find("#error-tanggal-pengajuan"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_pengajuan_klaim");
            }

            if (!keterangan) {
                showError($form.find("#keterangan_klaim"), $form.find("#error-keterangan-klaim"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#keterangan_klaim");
            }

            if (checkedCount === 0) {
                Swal.fire({
                    title: "Pilih Transaksi Kas Kecil!",
                    text: "Silakan pilih minimal 1 transaksi kas kecil dengan mencentang kotak pada kolom paling kanan sebelum menyimpan pengajuan klaim.",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                });
                return false;
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanKlaim");
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
