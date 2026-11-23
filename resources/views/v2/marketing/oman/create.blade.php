<form action="{{ route('oman.store') }}" method="POST" id="frmCreateOman" class="space-y-4">
    @csrf
    <input type="hidden" id="cektutuplaporan" value="0">

    <!-- FORM FILTER / SELEKSI PERIODE (50/50 SPLIT STANDAR V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3 sm:p-3.5 space-y-3">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Periode Permintaan OMAN</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
            
            <!-- 1. BULAN -->
            <div class="field-group">
                <label for="bulan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Bulan Permintaan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <select name="bulan" id="bulan"
                        class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Pilih Bulan</option>
                        @foreach ($list_bulan as $d)
                            <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- 2. TAHUN -->
            <div class="field-group">
                <label for="tahun" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tahun Permintaan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar-range" class="w-4 h-4"></i>
                    </div>
                    <select name="tahun" id="tahun"
                        class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Pilih Tahun</option>
                        @for ($t = $start_year; $t <= date('Y'); $t++)
                            <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- INFO CALLOUT -->
        <div class="flex items-start gap-2 p-2.5 rounded-lg bg-blue-50/80 dark:bg-blue-950/30 border border-blue-200/70 dark:border-blue-900/40 text-[11px] text-[#284c9a] dark:text-blue-300">
            <i data-lucide="info" class="w-3.5 h-3.5 shrink-0 mt-0.5 text-blue-500"></i>
            <div>
                Pilih Bulan dan Tahun untuk mengagregasi data OMAN seluruh cabang secara otomatis. Anda dapat mengklik angka pada kolom <span class="font-bold">Minggu ke 1-4</span> untuk menyesuaikan alokasi per cabang.
            </div>
        </div>
    </div>

    <!-- TABEL MATRIKS OMAN MARKETING (STANDAR V2 KOMPAK ~36px) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[380px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="mytable">
                <thead class="sticky top-0 z-10">
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="7" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="table-2" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Matriks Permintaan OMAN Marketing</span>
                                </div>
                                <span class="text-[10.5px] text-white/70" id="matrixProductCount">Konsolidasi Seluruh Cabang</span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Kolom Header -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE PRODUK</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">MINGGU 1</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">MINGGU 2</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">MINGGU 3</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">MINGGU 4</th>
                        <th class="py-2 px-3 text-center w-32 font-bold uppercase tracking-wider text-amber-300 border-none">TOTAL</th>
                    </tr>
                </thead>
                <tbody id="loadomancabang" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    <tr>
                        <td colspan="7" class="py-10 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="arrow-up-circle" class="w-7 h-7 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-medium">Silakan tentukan Bulan dan Tahun untuk memuat data OMAN cabang</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FOOTER ACTIONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" class="btn-close-modal h-[36px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitOman"
            class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan OMAN</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#frmCreateOman");
        const $bulan = $form.find("#bulan");
        const $tahun = $form.find("#tahun");
        const $btnSubmit = $form.find("#btnSubmitOman");

        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
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
            $container.find('.field-error-msg').remove();
        }

        $bulan.on('change input', function() {
            clearFieldError($(this));
            getomancabang();
        });

        $tahun.on('change input', function() {
            clearFieldError($(this));
            getomancabang();
        });

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
                    $("#cektutuplaporan").val(respond);
                }
            });
        }

        window.getomancabang = function() {
            const bulan = $bulan.val();
            const tahun = $tahun.val();

            if (!bulan || !tahun) {
                return;
            }

            const blnPad = bulan < 10 ? "0" + parseInt(bulan, 10) : bulan;
            const tanggal = tahun + "-" + blnPad + "-01";
            cektutuplaporan(tanggal, "penjualan");

            $("#loadomancabang").html(`
                <tr>
                    <td colspan="7" class="py-8 text-center text-slate-400">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-semibold">Mengambil data OMAN cabang...</span>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                type: "POST",
                url: "/omancabang/getomancabang",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $("#loadomancabang").html(respond);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $("#loadomancabang").html(`
                        <tr>
                            <td colspan="7" class="py-6 text-center text-rose-500 font-semibold text-xs">
                                Terjadi kesalahan saat memuat data OMAN cabang.
                            </td>
                        </tr>
                    `);
                }
            });
        };

        // Form Submit Validation
        $form.on("submit", function(e) {
            let hasError = false;
            let $firstError = null;

            const bulan = $bulan.val();
            const tahun = $tahun.val();
            const cektutuplaporan = $("#cektutuplaporan").val();

            if (!bulan) {
                setFieldError($bulan, "Bulan wajib dipilih");
                hasError = true;
                if (!$firstError) $firstError = $bulan;
            }

            if (!tahun) {
                setFieldError($tahun, "Tahun wajib dipilih");
                hasError = true;
                if (!$firstError) $firstError = $tahun;
            }

            if (hasError) {
                e.preventDefault();
                $firstError.focus();
                return false;
            }

            if (cektutuplaporan > 0) {
                e.preventDefault();
                Swal.fire({
                    title: "Periode Ditutup!",
                    text: "Laporan pada periode bulan & tahun ini sudah ditutup.",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                });
                return false;
            }

            const productCount = $("#loadomancabang input[name='kode_produk[]']").length;
            if (productCount === 0) {
                e.preventDefault();
                Swal.fire({
                    title: "Data Kosong!",
                    text: "Tidak ada data produk OMAN cabang untuk disimpan pada periode ini.",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                });
                return false;
            }

            // Anti Double Submit
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan OMAN...</span>
            `);
            return true;
        });
    });
</script>
