<form action="{{ route('permintaankiriman.store') }}" method="POST" id="formcreatePermintaankiriman" class="space-y-2.5" autocomplete="off">
    @csrf
    <input type="hidden" id="cektutuplaporan" value="0">
    <input type="hidden" id="cekdetailtemp" value="0">

    <!-- SECTION PARAMETER PERMINTAAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-2.5 sm:p-3">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            
            <!-- Tanggal Permintaan (Col-span 6 - Full Width Bagi 2) -->
            <div class="sm:col-span-6 field-group">
                <label for="tanggal_pk" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5 whitespace-nowrap">
                    Tanggal Permintaan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal_pk" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
            </div>

            <!-- Cabang (Col-span 6 - Full Width Bagi 2) -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_cabang_pk" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Cabang Tujuan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_pk"
                        class="select2Kodecabang select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}">{{ $c->kode_cabang }} - {{ textUpperCase($c->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Salesman (Col-span 12, Conditional TSM) -->
            <div class="sm:col-span-12 field-group hidden" id="salesmanGroup">
                <label for="kode_salesman_pk" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Salesman (TSM)
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_salesman" id="kode_salesman_pk"
                        class="select2Kodesalesman select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Salesman</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan (Col-span 12 or 8) -->
            <div class="sm:col-span-12 field-group">
                <label for="keterangan_pk" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Keterangan Permintaan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="keterangan" id="keterangan_pk" placeholder="Contoh: Permintaan stok reguler cabang Tasikmalaya"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

        </div>
    </div>

    <!-- SECTION INPUT DETAIL ITEM (TAMBAH PRODUK) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Tambah Rincian Produk</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            
            <!-- Pilih Produk (Col-span 7) -->
            <div class="sm:col-span-7 field-group">
                <label for="kode_produk_select" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Pilih Produk <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="box" class="w-3.5 h-3.5"></i>
                    </div>
                    <select id="kode_produk_select"
                        class="select2Kodeproduk select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Produk</option>
                        @foreach ($produk as $p)
                            <option value="{{ $p->kode_produk }}">{{ $p->kode_produk }} - {{ $p->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jumlah (Col-span 3) -->
            <div class="sm:col-span-3 field-group">
                <label for="jumlah_input" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Jumlah <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="hash" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" id="jumlah_input" placeholder="0"
                        class="money w-full h-[34px] pl-8 pr-2.5 text-xs text-right font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tombol Tambahkan Produk (Col-span 2) with Anti-Layout Shift Spacer -->
            <div class="sm:col-span-2">
                <label class="hidden sm:block text-[11px] font-bold text-transparent mb-0.5 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="btnTambahProduk"
                    class="w-full h-[34px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah</span>
                </button>
            </div>

        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[260px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tableDetailTemp">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Strip Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Daftar Produk Permintaan</span>
                                </div>
                                <span id="itemCountBadge" class="text-[10.5px] text-white/70 font-mono-num">0 item</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right w-28 font-bold uppercase tracking-wider text-white/90 border-none">JUMLAH</th>
                        <th class="py-2 px-3 text-center w-14 font-bold uppercase tracking-wider text-white/90 border-none">#</th>
                    </tr>
                </thead>
                <tbody id="loaddetailtemp" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    <tr id="emptyRow">
                        <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-medium">Memuat data produk...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ERROR CONTAINER SUMMARY JIKA DETAIL KOSONG -->
    <div id="errorDetailEmpty" class="hidden rounded-lg p-2.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-700 dark:text-rose-400 text-xs font-semibold flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-500"></i>
        <span>Detail produk masih kosong! Harap tambahkan minimal 1 produk sebelum menyimpan.</span>
    </div>

    <!-- FOOTER ACTIONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3">
        <div class="field-group">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="agreement_check"
                    class="w-4 h-4 rounded-sm border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 cursor-pointer">
                <label for="agreement_check" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                    Konfirmasi data permintaan sudah benar
                </label>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpan"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span id="btnSimpanText">Simpan Permintaan</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formcreatePermintaankiriman');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Flatpickr Init
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Initialize Select2 (Floating Mount to document.body per GEMINI.md)
        $form.find('.select2Kodecabang').select2({
            placeholder: "Pilih Cabang",
            allowClear: true,
            width: '100%'
        });

        $form.find('.select2Kodeproduk').select2({
            placeholder: "Pilih Produk",
            allowClear: true,
            width: '100%'
        });

        $form.find('.select2Kodesalesman').select2({
            placeholder: "Pilih Salesman",
            allowClear: true,
            width: '100%'
        });

        // Money Masking
        if (typeof $.fn.maskMoney !== 'undefined') {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        // Helper Error Display
        function showError($field, message) {
            $field.addClass('!border-rose-500 !ring-1 !ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('!text-rose-500');
            $group.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearError($field) {
            $field.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('!text-rose-500');
            $group.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $group.find('.field-error-msg').remove();
        }

        // Live cleanup on input & select
        $form.on('input change', 'input, select', function() {
            clearError($(this));
            $('#errorDetailEmpty').addClass('hidden');
        });

        $form.find('.select2Kodecabang, .select2Kodeproduk, .select2Kodesalesman').on('select2:select select2:clear', function() {
            clearError($(this));
        });

        $form.find('#agreement_check').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).removeClass('!ring-2 !ring-rose-500');
                $(this).closest('.field-group').find('.field-error-msg').remove();
            }
        });

        // Check CekTutupLaporan
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

        // Check & Load Detail Temp
        function cekdetailtemp() {
            $.ajax({
                type: 'POST',
                url: "{{ route('permintaankiriman.cekdetailtemp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                cache: false,
                success: function(respond) {
                    $form.find("#cekdetailtemp").val(respond);
                    updateBadgeCount(parseInt(respond) || 0);
                }
            });
        }

        function loaddetailtemp() {
            $form.find("#loaddetailtemp").load("{{ route('permintaankiriman.getdetailtemp') }}", function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                cekdetailtemp();
            });
        }

        function updateBadgeCount(count) {
            $form.find('#itemCountBadge').text(`${count} item`);
            if (count > 0) {
                $('#errorDetailEmpty').addClass('hidden');
            }
        }

        // Salesman TSM Dynamic Handler
        function getsalesmanbyCabang() {
            const kode_cabang = $form.find("#kode_cabang_pk").val();
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_salesman_pk").html(respond).trigger('change');
                }
            });
        }

        function showhideSalesman() {
            const kode_cabang = $form.find("#kode_cabang_pk").val();
            if (kode_cabang === "TSM") {
                $form.find("#salesmanGroup").removeClass('hidden');
            } else {
                $form.find("#salesmanGroup").addClass('hidden');
                $form.find('#kode_salesman_pk').val('').trigger("change");
            }
        }

        // Initial Load
        loaddetailtemp();
        showhideSalesman();
        cektutuplaporan($form.find("#tanggal_pk").val(), "gudangjadi");

        $form.find("#tanggal_pk").on('change', function() {
            cektutuplaporan($(this).val(), "gudangjadi");
        });

        $form.find("#kode_cabang_pk").on('change select2:select', function() {
            const kode_cabang = $(this).val();
            showhideSalesman();
            if (kode_cabang === "TSM") {
                getsalesmanbyCabang();
            }
        });

        // Tambah Produk Temp Click
        $form.find("#btnTambahProduk").on('click', function(e) {
            e.preventDefault();
            const $kodeProduk = $form.find("#kode_produk_select");
            const $jumlah = $form.find("#jumlah_input");

            const kode = $kodeProduk.val();
            const jumlah = $jumlah.val();

            let hasAddError = false;
            let $firstAddError = null;

            if (!kode) {
                showError($kodeProduk, 'Pilih produk terlebih dahulu');
                hasAddError = true;
                $firstAddError = $kodeProduk;
            }

            if (!jumlah || jumlah === "0") {
                showError($jumlah, 'Jumlah wajib diisi');
                hasAddError = true;
                if (!$firstAddError) $firstAddError = $jumlah;
            }

            if (hasAddError) {
                if ($firstAddError) {
                    if ($firstAddError.hasClass('select2Kodeproduk')) {
                        $firstAddError.select2('open');
                    } else {
                        $firstAddError.focus();
                    }
                }
                return;
            }

            const $btn = $(this);
            $btn.prop('disabled', true).addClass('opacity-75');

            $.ajax({
                type: "POST",
                url: "{{ route('permintaankiriman.storedetailtemp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_produk: kode,
                    jumlah: jumlah
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        $jumlah.val("");
                        $kodeProduk.val('').trigger("change");
                        clearError($kodeProduk);
                        clearError($jumlah);
                        loaddetailtemp();
                    } else {
                        showError($kodeProduk, respond.message || 'Gagal menambahkan produk');
                    }
                    $btn.prop('disabled', false).removeClass('opacity-75');
                },
                error: function() {
                    showError($kodeProduk, 'Terjadi kesalahan sistem');
                    $btn.prop('disabled', false).removeClass('opacity-75');
                }
            });
        });

        // Delete Produk Temp Click
        $form.on('click', '.btnDeleteTemp', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: "{{ route('permintaankiriman.deletetemp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loaddetailtemp();
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: respond.message,
                            icon: "error"
                        });
                    }
                }
            });
        });

        // Submit Form Validation
        $form.on('submit', function(e) {
            const $tanggal = $form.find("#tanggal_pk");
            const $kodeCabang = $form.find("#kode_cabang_pk");
            const $keterangan = $form.find("#keterangan_pk");
            const cektutup = $form.find("#cektutuplaporan").val();
            const cekdetail = parseInt($form.find("#cekdetailtemp").val()) || 0;
            const $agreement = $form.find('#agreement_check');

            let hasError = false;
            let $firstError = null;

            if (!$tanggal.val().trim()) {
                showError($tanggal, 'Tanggal permintaan wajib diisi');
                hasError = true;
                if (!$firstError) $firstError = $tanggal;
            }

            if (!$kodeCabang.val()) {
                showError($kodeCabang, 'Pilih cabang tujuan');
                hasError = true;
                if (!$firstError) $firstError = $kodeCabang;
            }

            if (!$keterangan.val().trim()) {
                showError($keterangan, 'Keterangan permintaan wajib diisi');
                hasError = true;
                if (!$firstError) $firstError = $keterangan;
            }

            if (cektutup === '1') {
                Swal.fire({
                    title: "Peringatan",
                    text: "Laporan untuk periode tanggal ini sudah ditutup!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            if (cekdetail === 0) {
                $('#errorDetailEmpty').removeClass('hidden');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_produk_select");
            } else {
                $('#errorDetailEmpty').addClass('hidden');
            }

            if (!$agreement.is(':checked')) {
                $agreement.addClass('!ring-2 !ring-rose-500');
                const $agGroup = $agreement.closest('.field-group');
                $agGroup.find('.field-error-msg').remove();
                $agGroup.append(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Centang konfirmasi terlebih dahulu</span>
                    </p>
                `);
                hasError = true;
                if (!$firstError) $firstError = $agreement;
            } else {
                $agreement.removeClass('!ring-2 !ring-rose-500');
                $agreement.closest('.field-group').find('.field-error-msg').remove();
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) {
                    if ($firstError.hasClass('select2Kodecabang') || $firstError.hasClass('select2Kodeproduk')) {
                        $firstError.select2('open');
                    } else {
                        $firstError.focus();
                    }
                }
                return false;
            }

            // Anti-Double Submit
            const $btn = $form.find('#btnSimpan');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.find('i').remove();
            $form.find('#btnSimpanText').html(`
                <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `);
        });

    });
</script>
