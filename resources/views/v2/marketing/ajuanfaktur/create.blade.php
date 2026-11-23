<form action="{{ route('ajuanfaktur.store') }}" id="formAjuanfaktur" method="POST" autocomplete="off">
    @csrf

    <div class="space-y-3">
        <!-- BARIS 1: NO PENGAJUAN & TANGGAL -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
            <!-- No Pengajuan (Auto) -->
            <div class="field-group">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    No. Pengajuan <span class="text-[10px] font-normal text-slate-400">(Otomatis)</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_pengajuan" id="no_pengajuan" placeholder="[Auto Generated]" disabled
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800 text-slate-500 cursor-not-allowed">
                </div>
            </div>

            <!-- Tanggal Pengajuan -->
            <div class="field-group">
                <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tanggal Pengajuan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                </div>
            </div>
        </div>

        <!-- BARIS 2: PILIH PELANGGAN (KODE PELANGGAN & LOOKUP BUTTON) -->
        <div class="field-group">
            <label for="kode_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Pilih Pelanggan <span class="text-rose-500">*</span>
            </label>
            <div class="grid grid-cols-12 gap-1.5 items-start">
                <div class="col-span-9 sm:col-span-10 relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="kode_pelanggan" id="kode_pelanggan" readonly placeholder="Klik Cari Pelanggan..."
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 cursor-pointer">
                </div>
                <div class="col-span-3 sm:col-span-2">
                    <button type="button" id="kode_pelanggan_search"
                        class="w-full h-[34px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer shrink-0">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- RINGKASAN DATA PELANGGAN TERPILIH (EXECUTIVE SUMMARY STRIP) -->
        <div id="card_info_pelanggan" class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/60 dark:bg-slate-900/60 space-y-2">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i data-lucide="user-check" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Informasi Pelanggan Terpilih</span>
                </div>
                <span id="badge_pelanggan_status" class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                    Menunggu Pilihan
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-3 gap-y-1.5 text-xs">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Nama Pelanggan</span>
                    <span id="text_nama_pelanggan" class="font-bold text-slate-800 dark:text-slate-100">-</span>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Limit Kredit Saat Ini</span>
                    <span id="text_limit_pelanggan" class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">Rp 0</span>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Salesman & Cabang</span>
                    <span id="text_salesman_cabang" class="font-medium text-slate-700 dark:text-slate-300">-</span>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">No. HP</span>
                    <span id="text_no_hp" class="font-mono-num text-slate-700 dark:text-slate-300">-</span>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Alamat</span>
                    <span id="text_alamat" class="text-slate-600 dark:text-slate-400 line-clamp-2">-</span>
                </div>
            </div>
        </div>

        <!-- BARIS 3: JUMLAH FAKTUR -->
        <div class="field-group">
            <label for="jumlah_faktur" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jumlah Faktur <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="jumlah_faktur" id="jumlah_faktur" placeholder="Contoh: 1, 2"
                    class="money w-full h-[34px] pl-8 pr-2.5 text-xs font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
            </div>
        </div>

        <!-- BARIS 4: KETERANGAN -->
        <div class="field-group">
            <label for="keterangan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Keterangan
            </label>
            <textarea name="keterangan" id="keterangan" rows="2" placeholder="Catatan atau pertimbangan pengajuan faktur kredit..."
                class="w-full p-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition"></textarea>
        </div>

        <!-- BARIS 5: CASH ON DELIVERY (COD) CHECKBOX -->
        <div class="rounded-lg border border-slate-200 dark:border-slate-800 p-2.5 bg-slate-50/50 dark:bg-slate-900/50">
            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                <input type="checkbox" name="cod" id="cod" value="1"
                    class="rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a] mt-0.5 cursor-pointer">
                <div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block">
                        Pembayaran Saat Turun Barang Selanjutnya (COD)
                    </span>
                    <span class="text-[11px] text-slate-400 block mt-0.5">
                        Centang jika faktur ini menggunakan mekanisme COD / pelunasan saat pengiriman barang berikutnya.
                    </span>
                </div>
            </label>
        </div>
    </div>

    <!-- FORM FOOTER BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-3 mt-3 border-t border-slate-100 dark:border-slate-800">
        <button type="button" class="btn-close-modal h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="submitAjuanfaktur"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span id="btnSimpanText">Simpan Pengajuan Faktur</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formAjuanfaktur");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Flatpickr & Money Mask
        if (typeof flatpickr !== 'undefined') {
            $form.find(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    clearError($(instance.element));
                }
            });
        }

        if ($.fn.maskMoney) {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0
            });
        }

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number.toString().split("").reverse().join("");
                for (var i = 0; i < numberrev.length; i++) {
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                }
                return rupiah.split("", rupiah.length - 1).reverse().join("");
            } else {
                return '0';
            }
        }

        // Get Pelanggan Details
        function getPelanggan(kode_pelanggan) {
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    if (response && response.data) {
                        const status_aktif = response.data.status_aktif_pelanggan;
                        if (status_aktif === '0') {
                            Swal.fire({
                                title: "Oops!",
                                text: "Pelanggan Tidak Aktif, silakan hubungi admin!",
                                icon: "warning"
                            });
                        } else {
                            $form.find("#kode_pelanggan").val(response.data.kode_pelanggan);
                            $form.find("#text_nama_pelanggan").text(response.data.nama_pelanggan);
                            $form.find("#text_alamat").text(response.data.alamat_pelanggan || '-');
                            $form.find("#text_no_hp").text(response.data.no_hp_pelanggan || '-');
                            $form.find("#text_salesman_cabang").text((response.data.nama_salesman || '-') + " (" + (response.data.nama_cabang || '-') + ")");
                            $form.find("#text_limit_pelanggan").text("Rp " + convertToRupiah(response.data.limit_pelanggan));

                            $form.find("#badge_pelanggan_status")
                                .removeClass('bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300')
                                .addClass('bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400')
                                .text('Aktif');

                            $('#modalPelanggan').addClass('hidden');

                            // Bersihkan error jika sebelumnya field kode_pelanggan error
                            clearError($form.find("#kode_pelanggan"));
                        }
                    }
                }
            });
        }

        // Klik Baris / Aksi Pelanggan di Modal Pelanggan
        $(document).off('click', '.pilihpelanggan').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            const kode = $(this).attr('kode_pelanggan');
            getPelanggan(kode);
        });

        // Helper Error State Interaktif (Standar V2 GEMINI.md - Anti Swal Alert)
        function showError($field, message) {
            $field.addClass('!border-rose-500 !ring-1 !ring-rose-500 focus:!border-rose-500 focus:!ring-rose-500/20');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearError($field) {
            $field.removeClass('!border-rose-500 !ring-1 !ring-rose-500 focus:!border-rose-500 focus:!ring-rose-500/20');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $group.find('.field-error-msg').remove();
        }

        // Live Cleanup saat mengetik / mengubah data
        $form.on('input change', 'input, select, textarea', function() {
            clearError($(this));
        });

        // Form Submit Validation Standar V2
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstErrorField = null;

            function checkField($field, message) {
                if (!$field.length || $field.val() === null || $field.val() === undefined || $.trim($field.val()) === '') {
                    showError($field, message);
                    hasError = true;
                    if (!$firstErrorField) {
                        $firstErrorField = $field;
                    }
                }
            }

            // 1. Tanggal Pengajuan
            checkField($form.find("#tanggal"), "Tanggal pengajuan wajib diisi");

            // 2. Kode Pelanggan
            checkField($form.find("#kode_pelanggan"), "Pilih pelanggan terlebih dahulu");

            // 3. Jumlah Faktur
            const $jmlFaktur = $form.find("#jumlah_faktur");
            let jmlVal = ($jmlFaktur.val() || '0').replace(/\./g, '');
            if (parseInt(jmlVal) <= 0) {
                showError($jmlFaktur, "Jumlah faktur harus lebih dari 0");
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $jmlFaktur;
            }

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    $firstErrorField.focus();
                }
                return false;
            }

            // Double submit prevention (Standar V2 GEMINI.md)
            const $btn = $form.find('#submitAjuanfaktur');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
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
