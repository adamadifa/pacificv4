<form action="{{ route('barangmasukgudangbahan.update', Crypt::encrypt($barangmasuk->no_bukti)) }}" method="POST" id="formeditBarangmasukgudangbahan" class="space-y-3">
    @csrf
    @method('PUT')
    <input type="hidden" id="cektutuplaporan" name="cektutuplaporan">

    <!-- HEADER FIELDS (3 COLUMNS) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
        <!-- No. Bukti -->
        <div class="field-group">
            <label for="no_bukti" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Bukti Pemasukan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_bukti" id="no_bukti" value="{{ $barangmasuk->no_bukti }}" readonly
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500">
            </div>
        </div>

        <!-- Tanggal -->
        <div class="field-group">
            <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" value="{{ $barangmasuk->tanggal }}"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Pilih Tanggal" readonly>
            </div>
        </div>

        <!-- Asal Barang -->
        <div class="field-group">
            <label for="kode_asal_barang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Asal Barang <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
                <select name="kode_asal_barang" id="kode_asal_barang"
                    class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Asal Barang</option>
                    @foreach ($list_asal_barang as $d)
                        <option value="{{ $d['kode_asal_barang'] }}" {{ $barangmasuk->kode_asal_barang == $d['kode_asal_barang'] ? 'selected' : '' }}>
                            {{ $d['asal_barang'] }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
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
            <div class="sm:col-span-5 field-group">
                <label for="kode_barang" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Pilih Barang <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select name="kode_barang" id="kode_barang" class="select2Kodebarang w-full">
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $d)
                            <option value="{{ $d->kode_barang }}">{{ $d->kode_barang }} | {{ textUpperCase($d->nama_barang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Qty Unit -->
            <div class="sm:col-span-2 field-group">
                <label for="qty_unit" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Qty Unit
                </label>
                <div class="relative">
                    <input type="text" name="qty_unit" id="qty_unit"
                        class="number-separator w-full h-[36px] px-3 text-right text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0">
                </div>
            </div>

            <!-- Qty Berat -->
            <div class="sm:col-span-2 field-group">
                <label for="qty_berat" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Qty Berat
                </label>
                <div class="relative">
                    <input type="text" name="qty_berat" id="qty_berat"
                        class="number-separator w-full h-[36px] px-3 text-right text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0">
                </div>
            </div>

            <!-- Qty Lebih -->
            <div class="sm:col-span-3 field-group">
                <label for="qty_lebih" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Qty Lebih
                </label>
                <div class="relative">
                    <input type="text" name="qty_lebih" id="qty_lebih"
                        class="number-separator w-full h-[36px] px-3 text-right text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
            <!-- Keterangan Item -->
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
                    <!-- Row 1: Title Strip (colspan=7) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="7" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Masuk</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-16 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">QTY UNIT</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">QTY BERAT</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">QTY LEBIH</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                        <th class="py-2.5 px-3 text-center w-16 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody id="loaddetail" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        @php $index = rand(10, 100000); @endphp
                        <tr id="index_{{ $index }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a]">
                                <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
                                {{ $d->kode_barang }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-200">
                                {{ textUpperCase($d->nama_barang) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num">
                                <input type="text" name="qty_unit[]" value="{{ formatAngkaDesimal($d->qty_unit) }}"
                                    class="qty_unit number-separator w-20 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded px-1.5 py-0.5 text-xs font-semibold focus:border-brand-500 focus:outline-hidden">
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num">
                                <input type="text" name="qty_berat[]" value="{{ formatAngkaDesimal($d->qty_berat) }}"
                                    class="qty_berat number-separator w-20 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded px-1.5 py-0.5 text-xs font-semibold focus:border-brand-500 focus:outline-hidden">
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num">
                                <input type="text" name="qty_lebih[]" value="{{ formatAngkaDesimal($d->qty_lebih) }}"
                                    class="qty_lebih number-separator w-20 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded px-1.5 py-0.5 text-xs font-semibold focus:border-brand-500 focus:outline-hidden">
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                <input type="hidden" name="ket[]" value="{{ $d->keterangan }}">
                                {{ $d->keterangan ?: '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <button type="button" class="btnHapusItem w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 flex items-center justify-center transition shadow-2xs mx-auto" data-id="{{ $index }}">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="7" class="py-6 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1">
                                    <i data-lucide="inbox" class="w-6 h-6 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Belum ada barang yang ditambahkan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- AGREEMENT & SUBMIT -->
    <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-slate-100 dark:border-slate-800">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="aggrement" id="defaultCheck3" value="aggrement"
                class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300 dark:border-slate-700">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">Yakin perubahan data barang masuk sudah sesuai dan akan disimpan?</span>
        </label>

        <button type="submit" id="btnSimpan"
            class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer disabled:opacity-50">
            <i data-lucide="send" class="w-4 h-4"></i>
            <span>Update Data</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formeditBarangmasukgudangbahan");

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
                $input.removeClass('is-invalid');
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
            onChange: function() {
                clearFieldError(form.find("#tanggal"));
            }
        });

        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ',',
            });
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
                    form.find("#cektutuplaporan").val(respond);
                }
            });
        }

        form.find("#tanggal").on("change", function(e) {
            cektutuplaporan($(this).val(), "gudangbahan");
        });

        // Select2 Floating Dropdown
        const select2Kodebarang = form.find('.select2Kodebarang');
        if (select2Kodebarang.length && $.fn.select2) {
            select2Kodebarang.select2({
                placeholder: 'Pilih Barang',
                allowClear: true,
                width: '100%'
            }).on('select2:select select2:clear', function() {
                clearFieldError($(this));
            });
        }

        function addProduk() {
            const dataBarang = form.find("#kode_barang :selected");
            const kode_barang = dataBarang.val();
            const textBarang = dataBarang.text();
            const nama_barang = textBarang.includes("|") ? textBarang.split("|")[1].trim() : textBarang;
            const qty_unit = form.find("#qty_unit").val() || 0;
            const qty_berat = form.find("#qty_berat").val() || 0;
            const qty_lebih = form.find("#qty_lebih").val() || 0;
            const keterangan = form.find("#keterangan").val() || '';

            if (!kode_barang) {
                markFieldError(form.find("#kode_barang"), "Pilih barang terlebih dahulu!");
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
                    text: "Barang sudah ada dalam daftar rincian!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            const index = Math.floor(Math.random() * 100000);
            const row = `
                <tr id="index_${index}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                    <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a]">
                        <input type="hidden" name="kode_barang[]" value="${kode_barang}"/>
                        ${kode_barang}
                    </td>
                    <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-200">${nama_barang}</td>
                    <td class="py-1.5 px-3 text-right font-mono-num">
                        <input type="text" name="qty_unit[]" value="${qty_unit}" class="qty_unit number-separator w-20 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded px-1.5 py-0.5 text-xs font-semibold focus:border-brand-500 focus:outline-hidden" />
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num">
                        <input type="text" name="qty_berat[]" value="${qty_berat}" class="qty_berat number-separator w-20 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded px-1.5 py-0.5 text-xs font-semibold focus:border-brand-500 focus:outline-hidden" />
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num">
                        <input type="text" name="qty_lebih[]" value="${qty_lebih}" class="qty_lebih number-separator w-20 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded px-1.5 py-0.5 text-xs font-semibold focus:border-brand-500 focus:outline-hidden" />
                    </td>
                    <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                        <input type="hidden" name="ket[]" value="${keterangan}" />
                        ${keterangan || '-'}
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="btnHapusItem w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 flex items-center justify-center transition shadow-2xs mx-auto" data-id="${index}">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            form.find("#emptyRow").remove();
            form.find("#loaddetail").append(row);

            form.find("#kode_barang").val('').trigger('change');
            form.find("#qty_unit").val('');
            form.find("#qty_berat").val('');
            form.find("#qty_lebih").val('');
            form.find("#keterangan").val('');

            if (typeof lucide !== 'undefined') lucide.createIcons();
            if (typeof easyNumberSeparator !== 'undefined') {
                easyNumberSeparator({
                    selector: '.number-separator',
                    separator: '.',
                    decimalSeparator: ',',
                });
            }
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
                        <td colspan="7" class="py-6 text-center text-slate-400">
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
            let hasError = false;
            let firstError = null;

            const no_bukti = form.find("#no_bukti").val().trim();
            const tanggal = form.find("#tanggal").val().trim();
            const kode_asal_barang = form.find("#kode_asal_barang").val().trim();
            const cektutup = form.find("#cektutuplaporan").val();
            const agreement = form.find("#defaultCheck3").is(":checked");
            const totalItems = form.find('#loaddetail input[name="kode_barang[]"]').length;

            if (cektutup == 1) {
                Swal.fire({
                    title: "Peringatan",
                    text: "Periode Laporan Sudah Ditutup!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            if (!tanggal) {
                markFieldError(form.find("#tanggal"), "Tanggal transaksi wajib dipilih!");
                hasError = true;
                if (!firstError) firstError = form.find("#tanggal");
            }

            if (!kode_asal_barang) {
                markFieldError(form.find("#kode_asal_barang"), "Asal Barang wajib dipilih!");
                hasError = true;
                if (!firstError) firstError = form.find("#kode_asal_barang");
            }

            if (hasError) {
                firstError.focus();
                return false;
            }

            if (totalItems === 0) {
                Swal.fire({
                    title: "Peringatan",
                    text: "Rincian Barang masih kosong, tambahkan minimal 1 barang!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            if (!agreement) {
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
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memperbarui...</span>
            `);
        });
    });
</script>
