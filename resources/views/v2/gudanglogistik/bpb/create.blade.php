<form action="{{ route('bpb.store') }}" method="POST" id="formcreatebpb" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- TANGGAL BPB -->
    <div class="field-group max-w-sm">
        <label for="tanggal_bpb" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tanggal Permintaan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tanggal" id="tanggal_bpb" value="{{ date('Y-m-d') }}" readonly
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                placeholder="Pilih Tanggal">
        </div>
    </div>

    <!-- DETAIL BARANG INPUT SECTION -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3.5 bg-slate-50/50 dark:bg-slate-800/30 space-y-2.5">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60 dark:border-slate-700/60 text-xs font-bold text-slate-800 dark:text-slate-100">
            <i data-lucide="package-plus" class="w-4 h-4 text-[#284c9a]"></i>
            <span>Tambah Item Permintaan Barang</span>
        </div>

        <!-- Row 1: Barang & Jumlah -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
            <!-- Pilih Barang -->
            <div class="sm:col-span-8 field-group">
                <label for="kode_barang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Pilih Barang <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="box" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_barang" id="kode_barang" class="select2Kodebarang w-full">
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->kode_barang }}">
                                {{ $b->kode_barang }} | {{ strtoupper($b->nama_barang) }} ({{ strtoupper($b->satuan) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="sm:col-span-4 field-group">
                <label for="jumlah" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Jumlah <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="jumlah" id="jumlah"
                        class="number-separator w-full h-[36px] pl-9 pr-3 text-right text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0">
                </div>
            </div>
        </div>

        <!-- Row 2: Keterangan (No Label per Standard) & Tombol Tambah -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
            <div class="sm:col-span-9 field-group">
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="keterangan" id="keterangan"
                        class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Keterangan keperluan barang (opsional)">
                </div>
            </div>

            <div class="sm:col-span-3">
                <button type="button" id="tambahproduk"
                    class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 px-3 rounded-lg bg-[#284c9a] hover:bg-blue-800 active:bg-blue-900 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Item</span>
                </button>
            </div>
        </div>
    </div>

    <!-- TABLE DETAIL ITEMS (STANDAR DUA BARIS THEAD V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[350px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabledetail">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Title Strip (colspan=5) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Barang yang Diajukan</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">JUMLAH</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                        <th class="py-2.5 px-3 text-center w-16 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody id="loaddetail" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    <tr id="emptyRow">
                        <td colspan="5" class="py-6 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1">
                                <i data-lucide="inbox" class="w-6 h-6 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-medium">Belum ada barang yang diajukan</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- AGREEMENT & SUBMIT BUTTON -->
    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-3">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="aggrement" id="aggrement" value="aggrement"
                class="agreement w-4 h-4 rounded text-[#284c9a] border-slate-300 focus:ring-[#284c9a] cursor-pointer">
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Yakin data Bukti Permintaan Barang ini sudah benar dan akan disimpan?</span>
        </label>

        <div id="saveButton" class="hidden">
            <button type="submit" id="btnSimpan"
                class="w-full h-[38px] inline-flex items-center justify-center gap-2 px-6 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="send" class="w-4 h-4"></i>
                <span>Simpan & Ajukan BPB</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const formCreate = $("#formcreatebpb");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        formCreate.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }]
        });

        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ',',
            });
        }

        // Initialize Select2 with V2 design
        formCreate.find('.select2Kodebarang').select2({
            placeholder: 'Pilih Barang',
            allowClear: true,
            width: '100%'
        });

        // Error helper
        function markFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $container.addClass('has-error');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid')
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
            $container.removeClass('has-error');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        formCreate.find('input, select').on('input change select2:select', function() {
            clearFieldError($(this));
        });

        function addProduk() {
            const $selectBarang = formCreate.find("#kode_barang");
            const kode_barang = $selectBarang.val();
            const textBarang = $selectBarang.find("option:selected").text().trim();
            const parts = textBarang.split("|");
            const nama_barang = parts.length > 1 ? parts[1].trim() : textBarang;
            const jumlah = formCreate.find("#jumlah").val();
            const keterangan = formCreate.find("#keterangan").val();

            if (formCreate.find(`#tabledetail #index_${kode_barang}`).length > 0) {
                Swal.fire({
                    title: "Peringatan",
                    text: "Barang ini sudah ada dalam daftar!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return;
            }

            formCreate.find("#emptyRow").remove();

            const row = `
                <tr id="index_${kode_barang}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                    <td class="py-1.5 px-3 text-center font-mono-num font-bold text-[#284c9a]">
                        <input type="hidden" name="kode_barang[]" value="${kode_barang}"/>
                        ${kode_barang}
                    </td>
                    <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                        ${nama_barang}
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                        <input type="hidden" name="jml[]" value="${jumlah}"/>
                        ${jumlah}
                    </td>
                    <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400 text-xs">
                        <input type="hidden" name="ket[]" value="${keterangan}"/>
                        ${keterangan || '-'}
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="btnDeleteRow w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer border-0 p-0" title="Hapus">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            formCreate.find('#loaddetail').prepend(row);
            $selectBarang.val('').trigger('change');
            formCreate.find("#jumlah").val("");
            formCreate.find("#keterangan").val("");

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        formCreate.find("#tambahproduk").on("click", function(e) {
            e.preventDefault();
            const $kode_barang = formCreate.find("#kode_barang");
            const $jumlah = formCreate.find("#jumlah");
            let hasError = false;

            if (!$kode_barang.val()) {
                markFieldError($kode_barang, "Silakan pilih barang terlebih dahulu");
                hasError = true;
            }
            if (!$jumlah.val()) {
                markFieldError($jumlah, "Jumlah barang harus diisi");
                hasError = true;
            }

            if (!hasError) {
                addProduk();
            }
        });

        formCreate.on('click', '.btnDeleteRow', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            if (formCreate.find('#loaddetail tr').length === 0) {
                formCreate.find('#loaddetail').html(`
                    <tr id="emptyRow">
                        <td colspan="5" class="py-6 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1">
                                <i data-lucide="inbox" class="w-6 h-6 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-medium">Belum ada barang yang diajukan</span>
                            </div>
                        </td>
                    </tr>
                `);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        });

        formCreate.find('.agreement').on('change', function() {
            if (this.checked) {
                formCreate.find("#saveButton").removeClass('hidden');
            } else {
                formCreate.find("#saveButton").addClass('hidden');
            }
        });

        formCreate.on('submit', function(e) {
            const tanggal = formCreate.find("#tanggal_bpb").val();

            if (formCreate.find('#loaddetail tr').length === 0 || formCreate.find('#emptyRow').length > 0) {
                e.preventDefault();
                Swal.fire({
                    title: "Peringatan",
                    text: "Daftar barang masih kosong! Silakan tambahkan minimal satu barang.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            if (!tanggal) {
                e.preventDefault();
                markFieldError(formCreate.find("#tanggal_bpb"), "Tanggal permintaan harus diisi");
                return false;
            }

            const $btn = formCreate.find("#btnSimpan");
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
