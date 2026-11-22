<form action="{{ route('barangkeluarmtc.store') }}" method="POST" id="formBarangKeluar" class="space-y-2.5" autocomplete="off">
    @csrf

    <!-- SECTION PARAMETER DOKUMEN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-2.5 sm:p-3">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start">
            
            <!-- No. Bukti -->
            <div class="field-group">
                <label for="no_bukti_keluar" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    No. Bukti <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_bukti" id="no_bukti_keluar" placeholder="Contoh: BK-MTC-001"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tanggal Pengeluaran -->
            <div class="field-group">
                <label for="tanggal_keluar" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5 whitespace-nowrap">
                    Tanggal Pengeluaran <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal_keluar" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
            </div>

            <!-- Departemen -->
            <div class="field-group">
                <label for="kode_dept_keluar" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Departemen <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept_keluar"
                        class="select2Dept select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Departemen</option>
                        @foreach ($departemen as $d)
                            <option value="{{ $d->kode_dept }}">{{ $d->kode_dept }} - {{ $d->nama_dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>

    <!-- SECTION INPUT DETAIL ITEM (TAMBAH BARANG) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-2.5 sm:p-3 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 flex items-center gap-1.5">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Tambah Item Barang Keluar</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            
            <!-- Pilih Barang (Col-span 6) -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_barang_select" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Pilih Barang <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="box" class="w-3.5 h-3.5"></i>
                    </div>
                    <select id="kode_barang_select"
                        class="select2Barang select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->kode_barang }}" data-nama="{{ $b->nama_barang }}">
                                {{ $b->kode_barang }} - {{ $b->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jumlah (Col-span 3) -->
            <div class="sm:col-span-3 field-group">
                <label for="jumlah_input" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Jumlah / Qty <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="hash" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" id="jumlah_input" placeholder="0"
                        class="money w-full h-[34px] pl-8 pr-2.5 text-xs text-right font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tombol Tambah Barang (Col-span 3) with Anti-Layout Shift Spacer -->
            <div class="sm:col-span-3">
                <label class="hidden sm:block text-[11px] font-bold text-transparent mb-0.5 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="btnTambahItem"
                    class="w-full h-[34px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambahkan</span>
                </button>
            </div>

            <!-- Keterangan (Col-span 12) -->
            <div class="sm:col-span-12 field-group">
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" id="keterangan_input" placeholder="Keterangan peruntukan / pemakaian barang (opsional)"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[260px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tableDetail">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Strip (colspan=5) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-3.5 py-1.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Barang Keluar</span>
                                </div>
                                <span id="itemCountBadge" class="text-[10.5px] text-white/70 font-mono-num">0 item</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-1.5 px-3 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-1.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-1.5 px-3 text-right w-24 font-bold uppercase tracking-wider text-white/90 border-none">JUMLAH</th>
                        <th class="py-1.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                        <th class="py-1.5 px-3 text-center w-14 font-bold uppercase tracking-wider text-white/90 border-none">#</th>
                    </tr>
                </thead>
                <tbody id="loadDetail" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    <tr id="emptyRow">
                        <td colspan="5" class="py-6 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-medium">Belum ada barang yang ditambahkan</span>
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
        <span>Detail barang masih kosong! Harap tambahkan minimal 1 item barang sebelum menyimpan.</span>
    </div>

    <!-- FOOTER ACTIONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <input type="checkbox" id="agreement_check"
                class="w-4 h-4 rounded-sm border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 cursor-pointer">
            <label for="agreement_check" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                Konfirmasi data sudah benar
            </label>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpan"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span id="btnSimpanText">Simpan Data Barang Keluar</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formBarangKeluar');
        let itemCount = 0;

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Flatpickr Init
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Initialize Select2 Departemen & Barang
        $form.find('.select2Dept').select2({
            placeholder: "Pilih Departemen",
            allowClear: true,
            width: '100%'
        });

        $form.find('.select2Barang').select2({
            placeholder: "Pilih Barang",
            allowClear: true,
            width: '100%'
        });

        // Money formatting helper
        $form.find('.money').mask("#.##0", {
            reverse: true
        });

        // Helper Error Display
        function showError($field, message) {
            $field.addClass('border-rose-500 ring-1 ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500');
            $group.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearError($field) {
            $field.removeClass('border-rose-500 ring-1 ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500');
            $group.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $group.find('.field-error-msg').remove();
        }

        // Live cleanup on input & select
        $form.on('input change', 'input, select', function() {
            clearError($(this));
            $('#errorDetailEmpty').addClass('hidden');
        });

        $form.find('.select2Barang, .select2Dept').on('select2:select select2:clear', function() {
            clearError($(this));
        });

        function updateItemBadge() {
            const count = $form.find('#loadDetail tr:not(#emptyRow)').length;
            $form.find('#itemCountBadge').text(`${count} item`);
            if (count > 0) {
                $form.find('#emptyRow').hide();
                $('#errorDetailEmpty').addClass('hidden');
            } else {
                $form.find('#emptyRow').show();
            }
        }

        // Tambah Barang Detail
        $('#btnTambahItem').on('click', function(e) {
            e.preventDefault();
            const $kodeBarang = $form.find('#kode_barang_select');
            const $jumlah = $form.find('#jumlah_input');
            const $keterangan = $form.find('#keterangan_input');

            const kode = $kodeBarang.val();
            const nama = $kodeBarang.find('option:selected').data('nama');
            const jumlah = $jumlah.val();
            const ket = $keterangan.val();

            let hasError = false;

            if (!kode) {
                showError($kodeBarang, 'Pilih barang terlebih dahulu');
                hasError = true;
            }

            if (!jumlah || jumlah === '0') {
                showError($jumlah, 'Jumlah wajib diisi');
                hasError = true;
            }

            // Check if already in table
            if (kode && $form.find(`tr[data-kode="${kode}"]`).length > 0) {
                showError($kodeBarang, 'Barang ini sudah ada di dalam tabel');
                hasError = true;
            }

            if (hasError) return;

            // Append row
            itemCount++;
            const newRow = `
                <tr data-kode="${kode}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                    <td class="py-1.5 px-3">
                        <input type="hidden" name="kode_barang_item[]" value="${kode}">
                        <input type="hidden" name="jumlah_item[]" value="${jumlah}">
                        <input type="hidden" name="keterangan_item[]" value="${ket}">
                        <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">${kode}</span>
                    </td>
                    <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">${nama}</td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">${jumlah}</td>
                    <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">${ket || '-'}</td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="btnDeleteRow w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer mx-auto">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            $form.find('#loadDetail').append(newRow);
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Reset Item Form
            $kodeBarang.val('').trigger('change');
            $jumlah.val('');
            $keterangan.val('');
            updateItemBadge();
        });

        // Delete Row from table
        $form.on('click', '.btnDeleteRow', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            updateItemBadge();
        });

        // Submit Form
        $form.on('submit', function(e) {
            const $noBukti = $form.find('#no_bukti_keluar');
            const $tanggal = $form.find('#tanggal_keluar');
            const $kodeDept = $form.find('#kode_dept_keluar');
            const $agreement = $form.find('#agreement_check');
            let hasError = false;

            if (!$noBukti.val().trim()) {
                showError($noBukti, 'No. Bukti wajib diisi');
                hasError = true;
            }

            if (!$tanggal.val().trim()) {
                showError($tanggal, 'Tanggal pengeluaran wajib diisi');
                hasError = true;
            }

            if (!$kodeDept.val()) {
                showError($kodeDept, 'Pilih departemen tujuan');
                hasError = true;
            }

            const totalRows = $form.find('#loadDetail tr:not(#emptyRow)').length;
            if (totalRows === 0) {
                $('#errorDetailEmpty').removeClass('hidden');
                hasError = true;
            }

            if (!$agreement.is(':checked')) {
                $agreement.addClass('ring-2 ring-rose-500');
                hasError = true;
            } else {
                $agreement.removeClass('ring-2 ring-rose-500');
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            // Double submit prevention
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
