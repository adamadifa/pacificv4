<form action="{{ route('barangkeluargudanglogistik.store') }}" method="POST" id="formBarangkeluargudanglogistik" class="space-y-3">
    @csrf
    <input type="hidden" id="cektutuplaporan" name="cektutuplaporan">

    <!-- HEADER FIELDS (3 COLUMNS) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
        <!-- No. Bukti -->
        <div class="field-group">
            <label for="no_bukti" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Bukti Pengeluaran <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_bukti" id="no_bukti"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Contoh: BK-202401-001">
            </div>
        </div>

        <!-- Tanggal -->
        <div class="field-group">
            <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Pengeluaran <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Pilih Tanggal" readonly>
            </div>
        </div>

        <!-- Jenis Pengeluaran -->
        <div class="field-group">
            <label for="kode_jenis_pengeluaran" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jenis Pengeluaran <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <select name="kode_jenis_pengeluaran" id="kode_jenis_pengeluaran"
                    class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Jenis Pengeluaran</option>
                    @foreach ($list_jenis_pengeluaran as $d)
                        <option value="{{ $d['kode_jenis_pengeluaran'] }}">
                            {{ $d['jenis_pengeluaran'] }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- CABANG SECTION (CONDITIONAL FOR CBG) -->
    <div id="cabang-section" class="field-group" style="display: none;">
        <label for="kode_cabang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Cabang Tujuan Utama <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <select name="kode_cabang" id="kode_cabang" class="select2Kodecabang w-full">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ $d->kode_cabang }} - {{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- DETAIL ITEMS BOX -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 p-3 space-y-2.5">
        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800 dark:text-slate-100">
            <i data-lucide="box" class="w-4 h-4 text-[#284c9a]"></i>
            <span>Input Rincian Barang</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
            <!-- Pilih Barang -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_barang" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Pilih Barang <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select name="kode_barang" id="kode_barang" class="select2Kodebarang w-full">
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $d)
                            <option value="{{ $d->kode_barang }}">{{ $d->kode_barang }} | {{ textUpperCase($d->nama_barang) }} ({{ $d->satuan }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="sm:col-span-2 field-group">
                <label for="jumlah" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Jumlah <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" name="jumlah" id="jumlah"
                        class="number-separator w-full h-[36px] px-3 text-right text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0">
                </div>
            </div>

            <!-- Cabang Detail (Optional) -->
            <div class="sm:col-span-4 field-group">
                <label for="kode_cabang_detail" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Cabang Tujuan (Opsional)
                </label>
                <div class="relative">
                    <select name="kode_cabang_detail" id="kode_cabang_detail" class="select2Kodecabangdetail w-full">
                        <option value="">Pilih Cabang (Opsional)</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ $d->kode_cabang }} - {{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
            <!-- Keterangan Item (No Label per Standard) -->
            <div class="sm:col-span-9 field-group">
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="keterangan" id="keterangan"
                        class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Keterangan barang (opsional)">
                </div>
            </div>

            <!-- Tombol Tambah Barang -->
            <div class="sm:col-span-3">
                <button type="button" id="tambahproduk"
                    class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Produk</span>
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE DETAIL ITEMS (STANDAR DUA BARIS THEAD V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabledetail">
                <thead>
                    <!-- Row 1: Title Strip (colspan=6) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Keluar</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-16 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">JUMLAH</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-36">CABANG</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                        <th class="py-2.5 px-3 text-center w-16 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody id="loaddetail" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    <tr id="emptyRow">
                        <td colspan="6" class="py-6 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1">
                                <i data-lucide="inbox" class="w-6 h-6 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-medium">Belum ada barang yang ditambahkan</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- AGREEMENT & SUBMIT -->
    <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-slate-100 dark:border-slate-800">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="aggrement" id="defaultCheck3" value="aggrement"
                class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300 dark:border-slate-700">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">Yakin data barang keluar sudah sesuai dan akan disimpan?</span>
        </label>

        <button type="submit" id="btnSimpan"
            class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer disabled:opacity-50">
            <i data-lucide="send" class="w-4 h-4"></i>
            <span>Simpan Data</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formBarangkeluargudanglogistik");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Error State Visual Helpers
        function markFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $container.addClass('has-error');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');

            if ($input.hasClass('select2-hidden-accessible') || $input.next().hasClass('select2')) {
                const $s2 = $input.next('.select2');
                $s2.addClass('is-invalid');
                $s2.find('.select2-selection').addClass('is-invalid border-rose-500 !border-rose-500');
                $container.find('.select2-selection').addClass('is-invalid border-rose-500 !border-rose-500');
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
            $container.removeClass('has-error');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');

            if ($input.hasClass('select2-hidden-accessible') || $input.next().hasClass('select2')) {
                const $s2 = $input.next('.select2');
                $s2.removeClass('is-invalid');
                $s2.find('.select2-selection').removeClass('is-invalid border-rose-500 !border-rose-500');
                $container.find('.select2-selection').removeClass('is-invalid border-rose-500 !border-rose-500');
            }

            $container.find('.field-error-msg').remove();
        }

        // Live error cleanup on input/change
        form.find('input, select').on('input change select2:select', function() {
            clearFieldError($(this));
        });

        form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            @if(!empty($start_periode) && !empty($end_periode))
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }],
            @endif
        });

        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ',',
            });
        }

        if ($.fn.select2) {
            form.find('.select2Kodebarang').select2({
                placeholder: 'Pilih Barang',
                allowClear: true,
                width: '100%'
            });
            form.find('.select2Kodecabang').select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                width: '100%'
            });
            form.find('.select2Kodecabangdetail').select2({
                placeholder: 'Pilih Cabang (Opsional)',
                allowClear: true,
                width: '100%'
            });
        }

        function loadkodecabang() {
            const kode_jenis_pengeluaran = form.find("#kode_jenis_pengeluaran").val();
            if (kode_jenis_pengeluaran === "CBG") {
                form.find("#cabang-section").slideDown(150);
            } else {
                form.find("#cabang-section").slideUp(150);
            }
        }
        loadkodecabang();

        form.find("#kode_jenis_pengeluaran").on("change", function() {
            loadkodecabang();
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
                    form.find("#cektutuplaporan").val(respond);
                }
            });
        }

        form.find("#tanggal").on("change", function(e) {
            cektutuplaporan($(this).val(), "gudanglogistik");
        });

        function addProduk() {
            const dataBarang = form.find("#kode_barang :selected");
            const kode_barang = dataBarang.val();
            const textBarang = dataBarang.text();
            const nama_barang = textBarang.includes("|") ? textBarang.split("|")[1].trim() : textBarang;
            const jumlah = form.find("#jumlah").val();
            const dataCabang = form.find("#kode_cabang_detail :selected");
            const kode_cbg = dataCabang.val();
            const nama_cbg = kode_cbg ? dataCabang.text().replace(kode_cbg + ' - ', '').trim() : '-';
            const keterangan = form.find("#keterangan").val() || '';

            let hasDetailError = false;
            let $firstDetailError = null;

            if (!kode_barang) {
                markFieldError(form.find("#kode_barang"), "Pilih barang terlebih dahulu");
                hasDetailError = true;
                if (!$firstDetailError) $firstDetailError = form.find("#kode_barang");
            }

            if (!jumlah || jumlah === "0") {
                markFieldError(form.find("#jumlah"), "Jumlah barang harus diisi");
                hasDetailError = true;
                if (!$firstDetailError) $firstDetailError = form.find("#jumlah");
            }

            if (hasDetailError) {
                if ($firstDetailError) $firstDetailError.focus();
                return false;
            }

            let duplicate = false;
            form.find('#loaddetail input[name="kode_barang[]"]').each(function() {
                if ($(this).val() === kode_barang) {
                    duplicate = true;
                    return false;
                }
            });

            if (duplicate) {
                Swal.fire({
                    title: "Peringatan",
                    text: "Barang sudah ada dalam daftar!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            const index = Math.floor(Math.random() * 100000);
            const row = `
                <tr id="index_${index}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                    <td class="py-1.5 px-3 font-bold font-mono-num text-[#284c9a]">
                        <input type="hidden" name="kode_barang[]" value="${kode_barang}"/>
                        ${kode_barang}
                    </td>
                    <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-200">${nama_barang}</td>
                    <td class="py-1.5 px-3 text-right font-mono-num">
                        <input type="hidden" name="jml[]" value="${jumlah}"/>
                        ${jumlah}
                    </td>
                    <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                        <input type="hidden" name="kode_cbg[]" value="${kode_cbg}"/>
                        ${nama_cbg}
                    </td>
                    <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                        <input type="hidden" name="ket[]" value="${keterangan}"/>
                        ${keterangan || '-'}
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="btnHapusItem w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 flex items-center justify-center transition shadow-2xs mx-auto cursor-pointer" data-id="${index}">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            form.find("#emptyRow").remove();
            form.find("#loaddetail").append(row);

            form.find("#kode_barang").val('').trigger('change');
            form.find("#jumlah").val('');
            form.find("#kode_cabang_detail").val('').trigger('change');
            form.find("#keterangan").val('');

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        form.find("#tambahproduk").on("click", function(e) {
            e.preventDefault();
            addProduk();
        });

        $(document).on("click", ".btnHapusItem", function(e) {
            e.preventDefault();
            const id = $(this).data("id");
            $(`#index_${id}`).remove();
            if (form.find('#loaddetail tr').length === 0) {
                form.find("#loaddetail").html(`
                    <tr id="emptyRow">
                        <td colspan="6" class="py-6 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1">
                                <i data-lucide="inbox" class="w-6 h-6 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-medium">Belum ada barang yang ditambahkan</span>
                            </div>
                        </td>
                    </tr>
                `);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });

        form.on("submit", function(e) {
            const no_bukti = form.find("#no_bukti").val().trim();
            const tanggal = form.find("#tanggal").val().trim();
            const jenis = form.find("#kode_jenis_pengeluaran").val().trim();
            const cektutup = form.find("#cektutuplaporan").val();
            const agreement = form.find("#defaultCheck3").is(":checked");
            const totalItems = form.find('#loaddetail input[name="kode_barang[]"]').length;

            let hasError = false;
            let $firstError = null;

            if (!no_bukti) {
                markFieldError(form.find("#no_bukti"), "No. Bukti pengeluaran wajib diisi");
                hasError = true;
                if (!$firstError) $firstError = form.find("#no_bukti");
            }

            if (!tanggal) {
                markFieldError(form.find("#tanggal"), "Tanggal pengeluaran wajib diisi");
                hasError = true;
                if (!$firstError) $firstError = form.find("#tanggal");
            }

            if (!jenis) {
                markFieldError(form.find("#kode_jenis_pengeluaran"), "Jenis pengeluaran wajib dipilih");
                hasError = true;
                if (!$firstError) $firstError = form.find("#kode_jenis_pengeluaran");
            }

            if (jenis === 'CBG' && !form.find("#kode_cabang").val()) {
                markFieldError(form.find("#kode_cabang"), "Cabang tujuan utama wajib dipilih");
                hasError = true;
                if (!$firstError) $firstError = form.find("#kode_cabang");
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) $firstError.focus();
                return false;
            }

            if (cektutup == 1) {
                e.preventDefault();
                Swal.fire({
                    title: "Peringatan",
                    text: "Periode Laporan Sudah Ditutup!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            if (totalItems === 0) {
                e.preventDefault();
                Swal.fire({
                    title: "Peringatan",
                    text: "Detail Barang masih kosong, tambahkan minimal 1 barang!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            if (!agreement) {
                e.preventDefault();
                Swal.fire({
                    title: "Peringatan",
                    text: "Silakan centang persetujuan simpan terlebih dahulu!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            const $btn = form.find("#btnSimpan");
            $btn.prop("disabled", true).addClass("opacity-75 cursor-not-allowed pointer-events-none");
            $btn.html(`
                <svg class="animate-spin h-4 w-4 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>