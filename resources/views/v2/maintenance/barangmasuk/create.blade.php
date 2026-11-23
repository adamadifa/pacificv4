<form action="{{ route('barangmasukmtc.store') }}" method="POST" id="formBarangMasuk" class="space-y-2.5" autocomplete="off">
    @csrf

    <!-- SECTION PARAMETER DOKUMEN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-2.5 sm:p-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 items-start">
            
            <!-- No. Bukti -->
            <div class="field-group">
                <label for="no_bukti_masuk" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    No. Bukti <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_bukti" id="no_bukti_masuk" placeholder="Contoh: BM-MTC-001"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tanggal Diterima -->
            <div class="field-group">
                <label for="tanggal_masuk" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5 whitespace-nowrap">
                    Tanggal Diterima <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal_masuk" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
            </div>

        </div>
    </div>

    <!-- SECTION INPUT DETAIL ITEM (TAMBAH BARANG) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Tambah Item Barang</span>
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
                    <input type="text" id="keterangan_input" placeholder="Keterangan item barang (opsional)"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[300px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tableDetail">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Strip (colspan=5) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Daftar Barang yang Diterima</span>
                                </div>
                                <span id="itemCountBadge" class="text-[10.5px] text-white/70 font-mono-num">0 item</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2 px-3 text-right w-24 font-bold uppercase tracking-wider text-white/90 border-none">JUMLAH</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                        <th class="py-2 px-3 text-center w-14 font-bold uppercase tracking-wider text-white/90 border-none">#</th>
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

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-100 dark:border-slate-800/80">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanBarangMasuk"
            class="inline-flex items-center justify-center gap-2 h-[34px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Data</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $('#formBarangMasuk');
        const $btnSimpan = $('#btnSimpanBarangMasuk');

        // Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Masking
        if (typeof $.fn.maskMoney !== 'undefined') {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        // Select2 Floating Mount to document.body per GEMINI.md
        $form.find('.select2Barang').select2({
            placeholder: 'Pilih Barang',
            allowClear: true,
            width: '100%'
        });

        // Live cleanup for No Bukti
        $form.find('#no_bukti_masuk').on('input change', function() {
            clearFieldError($(this));
        });

        // Live cleanup for Detail Input
        $form.find('#kode_barang_select').on('change select2:select', function() {
            clearFieldError($(this));
        });
        $form.find('#jumlah_input').on('input change', function() {
            clearFieldError($(this));
        });

        function showFieldError($input, msg) {
            $input.addClass('!border-rose-500 !ring-1 !ring-rose-500');
            const $group = $input.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('!text-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        function clearFieldError($input) {
            $input.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            const $group = $input.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('!text-rose-500');
            $group.find('.field-error-msg').remove();
            if ($input.hasClass('select2Barang')) {
                $group.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
        }

        function updateBadgeCount() {
            const count = $form.find('#loadDetail tr:not(#emptyRow)').length;
            $form.find('#itemCountBadge').text(`${count} item`);
            if (count > 0) {
                $form.find('#emptyRow').hide();
                $form.find('#errorDetailEmpty').addClass('hidden');
            } else {
                $form.find('#emptyRow').show();
            }
        }

        // Tambah Item Button Click
        $form.find('#btnTambahItem').on('click', function(e) {
            e.preventDefault();
            const $kodeSelect = $form.find('#kode_barang_select');
            const $jumlahInput = $form.find('#jumlah_input');
            const $ketInput = $form.find('#keterangan_input');

            const kode = $kodeSelect.val();
            const nama = $kodeSelect.find(':selected').data('nama');
            const jumlah = $jumlahInput.val();
            const ket = $ketInput.val() || '';

            let hasAddError = false;

            if (!kode) {
                showFieldError($kodeSelect, 'Barang wajib dipilih');
                $kodeSelect.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
                hasAddError = true;
            } else {
                clearFieldError($kodeSelect);
            }

            if (!jumlah || jumlah === '0') {
                showFieldError($jumlahInput, 'Jumlah wajib diisi');
                if (!hasAddError) $jumlahInput.focus();
                hasAddError = true;
            } else {
                clearFieldError($jumlahInput);
            }

            if (hasAddError) return;

            // Cek duplikasi
            if ($form.find(`#row_${kode}`).length > 0) {
                showFieldError($kodeSelect, 'Barang sudah ditambahkan dalam daftar');
                return;
            }

            // Append row
            $form.find('#loadDetail').append(`
                <tr id="row_${kode}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                    <td class="py-1.5 px-3">
                        <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">${kode}</span>
                        <input type="hidden" name="kode_barang[]" value="${kode}">
                    </td>
                    <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                        ${nama}
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                        ${jumlah}
                        <input type="hidden" name="jumlah[]" value="${jumlah}">
                    </td>
                    <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400 text-[11px]">
                        ${ket ? ket : '-'}
                        <input type="hidden" name="keterangan[]" value="${ket}">
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="btnRemoveItem w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer"
                            data-id="${kode}" title="Hapus Baris">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `);

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Reset inputs
            $kodeSelect.val('').trigger('change');
            $jumlahInput.val('');
            $ketInput.val('');
            updateBadgeCount();
        });

        // Hapus item row
        $form.on('click', '.btnRemoveItem', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            $form.find(`#row_${id}`).remove();
            updateBadgeCount();
        });

        // Submit Form Validation (Anti-Double Submit)
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;

            const $noBukti = $form.find('#no_bukti_masuk');
            if (!$noBukti.val().trim()) {
                showFieldError($noBukti, 'No. Bukti wajib diisi');
                hasError = true;
                $firstError = $noBukti;
            } else {
                clearFieldError($noBukti);
            }

            // Validasi tabel detail
            const rowCount = $form.find('#loadDetail tr:not(#emptyRow)').length;
            if (rowCount === 0) {
                $form.find('#errorDetailEmpty').removeClass('hidden');
                hasError = true;
                if (!$firstError) $firstError = $form.find('#kode_barang_select');
            } else {
                $form.find('#errorDetailEmpty').addClass('hidden');
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) $firstError.focus();
                return false;
            }

            // Loading state on submit
            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });

    });
</script>
