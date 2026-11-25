<form action="{{ route('pembayarangiro.storegroup') }}" method="POST" id="formCreategroupgiro" enctype="multipart/form-data" class="space-y-3" autocomplete="off">
    @csrf

    <!-- Row 1: No Giro (Auto) & Tanggal Pembayaran -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="space-y-1">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Kode Giro
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="kode_giro" value="Auto" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-semibold text-slate-500 cursor-not-allowed">
            </div>
        </div>

        <div class="space-y-1">
            <label for="tanggal_giro_group" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Pembayaran <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_giro_group" name="tanggal" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal pembayaran wajib diisi</span>
            </p>
        </div>
    </div>

    <!-- Row 2: Pelanggan Selector -->
    <div class="space-y-1">
        <label for="kode_pelanggan_giro_group" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Pelanggan <span class="text-rose-500">*</span>
        </label>
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" id="kode_pelanggan_giro_group" name="kode_pelanggan" readonly placeholder="Klik Cari Pelanggan..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-bold font-mono-num text-slate-800 dark:text-slate-100 placeholder:text-slate-400 cursor-pointer">
            </div>
            <button type="button" id="btnCariPelangganGiro"
                class="h-[36px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shrink-0">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Cari</span>
            </button>
        </div>
        <p id="error-kode_pelanggan-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Pelanggan wajib dipilih</span>
        </p>
    </div>

    <!-- Strip Info Pelanggan Terpilih -->
    <div id="infoPelangganContainerGiro" class="hidden rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3 shadow-2xs">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
            <div>
                <span class="text-[10px] font-medium text-slate-400 block uppercase">Nama Pelanggan</span>
                <span id="label_nama_pelanggan_giro" class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate">-</span>
            </div>
            <div>
                <span class="text-[10px] font-medium text-slate-400 block uppercase">Alamat</span>
                <span id="label_alamat_pelanggan_giro" class="font-medium text-slate-700 dark:text-slate-300 mt-0.5 block truncate">-</span>
            </div>
            <div>
                <span class="text-[10px] font-medium text-slate-400 block uppercase">No. HP</span>
                <span id="label_no_hp_pelanggan_giro" class="font-bold font-mono-num text-slate-800 dark:text-slate-200 mt-0.5 block">-</span>
            </div>
            <div>
                <span class="text-[10px] font-medium text-slate-400 block uppercase">Salesman Wilayah</span>
                <span id="label_nama_salesman_giro" class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate">-</span>
            </div>
        </div>
    </div>

    <!-- Row 3: No. Giro & Salesman Penagih -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="space-y-1">
            <label for="no_giro_group" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Nomor Giro <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <input type="text" id="no_giro_group" name="no_giro" placeholder="Nomor Warkat Giro"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-no-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Nomor giro wajib diisi</span>
            </p>
        </div>

        <div class="space-y-1">
            <label for="kode_salesman_penagih_giro" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Salesman Penagih <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
                <select name="kode_salesman" id="kode_salesman_penagih_giro" class="select2Kodesalesman w-full">
                    <option value="">Pilih Salesman Penagih</option>
                </select>
            </div>
            <p id="error-salesman-penagih-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Salesman penagih wajib dipilih</span>
            </p>
        </div>
    </div>

    <!-- Row 4: Bank Pengirim & Jatuh Tempo -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="space-y-1">
            <label for="bank_pengirim_giro" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Bank Pengirim <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="building" class="w-4 h-4"></i>
                </div>
                <input type="text" id="bank_pengirim_giro" name="bank_pengirim" placeholder="Contoh: BCA / Mandiri / BRI"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-bank-pengirim-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Bank pengirim wajib diisi</span>
            </p>
        </div>

        <div class="space-y-1">
            <label for="jatuh_tempo_giro" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Jatuh Tempo Giro <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                </div>
                <input type="text" id="jatuh_tempo_giro" name="jatuh_tempo" readonly placeholder="Pilih Jatuh Tempo"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-jatuh-tempo-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Jatuh tempo wajib diisi</span>
            </p>
        </div>
    </div>

    <!-- Row 5: Keterangan & Upload Foto Dokumen Giro -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="space-y-1">
            <label for="keterangan_giro_group" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Keterangan
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
                <input type="text" id="keterangan_giro_group" name="keterangan" placeholder="Keterangan giro (opsional)"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
        </div>

        <div class="space-y-1">
            <label for="foto_giro" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Upload Foto Dokumen Giro (Opsional)
            </label>
            <div class="relative">
                <input type="file" id="foto_giro" name="foto" accept="image/*"
                    class="w-full h-[36px] px-3 py-1.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-700 dark:text-slate-300 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-[#284c9a] hover:file:bg-blue-100 cursor-pointer">
            </div>
        </div>
    </div>

    <!-- RINCIAN FAKTUR CARD -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30 p-3 sm:p-3.5 space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h6 class="text-xs font-bold text-slate-800 dark:text-slate-200">Tambah Faktur ke Daftar Giro</h6>
            </div>
        </div>

        <!-- Baris Grid Input Faktur (items-start with spacer label on button column) -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
            <div class="sm:col-span-6 space-y-1">
                <label for="no_faktur_giro_group" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Pilih Faktur <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                    </div>
                    <select id="no_faktur_giro_group" name="no_faktur" class="select2Nofaktur w-full">
                        <option value="">Pilih Faktur</option>
                    </select>
                    <input type="hidden" id="sisa_piutang_giro">
                </div>
                <p id="error-no-faktur-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Faktur belum dipilih</span>
                </p>
            </div>

            <div class="sm:col-span-4 space-y-1">
                <label for="jumlah_faktur_giro" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Jumlah Bayar <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="banknote" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="jumlah_faktur_giro" name="jumlah" placeholder="0"
                        class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
                <p id="error-jumlah-faktur-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span id="error-jumlah-faktur-text-giro">Jumlah bayar wajib diisi</span>
                </p>
            </div>

            <div class="sm:col-span-2">
                <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="tambahfakturgiro"
                    class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah</span>
                </button>
            </div>
        </div>

        <!-- Tabel Faktur Terpilih -->
        <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs mt-2">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap" id="tabelfakturgiro">
                    <thead class="bg-[#1e3a78] text-white/90">
                        <tr>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Faktur</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Jumlah</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-12">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="loaddetailfakturgiro" class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                        <!-- Dynamic items -->
                    </tbody>
                    <tfoot id="footertabelgiro" class="hidden bg-slate-50/90 dark:bg-slate-800/60 font-bold border-t border-slate-200/80 dark:border-slate-700">
                        <tr>
                            <td class="py-2 px-3 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">Total Giro</td>
                            <td id="totalGiroText" class="py-2 px-3 text-right font-mono-num text-sm text-[#284c9a] dark:text-blue-400">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <p id="error-tabel-faktur-giro" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 p-2 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Minimal 1 faktur wajib ditambahkan ke daftar</span>
            </p>
        </div>
    </div>

    <!-- Checkbox Agreement & Action Buttons -->
    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-2">
        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-300">
            <input type="checkbox" id="agreementGiro" name="agreement"
                class="w-4 h-4 rounded-sm border-slate-300 text-[#284c9a] focus:ring-[#284c9a]">
            <span>Yakin data pembayaran giro ini sudah benar dan siap disimpan?</span>
        </label>

        <div id="saveButtonGroupGiro" class="hidden flex items-center justify-end gap-2 pt-1">
            <button type="button" id="btnCancelCreateGroupGiro"
                class="h-[34px] px-4 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpanGiroGroup"
                class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Simpan Giro</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formCreategroupgiro");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        // MaskMoney
        $form.find(".money-mask").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: false
        });

        // Select2 Salesman
        $form.find(".select2Kodesalesman").select2({
            placeholder: 'Pilih Salesman Penagih',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        // Select2 Faktur
        $form.find(".select2Nofaktur").select2({
            placeholder: 'Pilih Faktur',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        // Agreement toggle
        $form.find("#agreementGiro").on('change', function() {
            if (this.checked) {
                $form.find("#saveButtonGroupGiro").removeClass('hidden');
            } else {
                $form.find("#saveButtonGroupGiro").addClass('hidden');
            }
        });

        // Open lookup pelanggan
        $form.find("#btnCariPelangganGiro, #kode_pelanggan_giro_group").on('click', function(e) {
            e.preventDefault();
            if (typeof modalPelangganHelper !== 'undefined') {
                modalPelangganHelper.open();
            } else {
                $("#modalPelanggan").removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }
        });

        function formatRupiahNumber(val) {
            return new Intl.NumberFormat('id-ID').format(val || 0);
        }

        function parseMoney(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error, customText) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            if (customText) $error.find('span').text(customText);
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#tanggal_giro_group").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-giro"));
        });
        $form.find("#no_giro_group").on('input change', function() {
            clearError($(this), $form.find("#error-no-giro"));
        });
        $form.find("#bank_pengirim_giro").on('input change', function() {
            clearError($(this), $form.find("#error-bank-pengirim-giro"));
        });
        $form.find("#jatuh_tempo_giro").on('input change', function() {
            clearError($(this), $form.find("#error-jatuh-tempo-giro"));
        });
        $form.find("#kode_salesman_penagih_giro").on('change', function() {
            clearError($form.find("#kode_salesman_penagih_giro").closest('.select2-container-v2'), $form.find("#error-salesman-penagih-giro"));
        });
        $form.find("#no_faktur_giro_group").on('change', function() {
            clearError($form.find("#no_faktur_giro_group").closest('.select2-container-v2'), $form.find("#error-no-faktur-giro"));
            const no_faktur = $(this).val();
            if (no_faktur) {
                $.ajax({
                    type: 'GET',
                    url: `/penjualan/${no_faktur}/getpiutangfaktur`,
                    cache: false,
                    success: function(respond) {
                        if (respond && respond.data) {
                            const sisa = respond.data.sisa_piutang || 0;
                            $form.find("#sisa_piutang_giro").val(sisa);
                            $form.find("#jumlah_faktur_giro").val(formatRupiahNumber(sisa)).trigger('mask.maskMoney');
                            clearError($form.find("#jumlah_faktur_giro"), $form.find("#error-jumlah-faktur-giro"));
                        }
                    }
                });
            } else {
                $form.find("#sisa_piutang_giro").val('');
                $form.find("#jumlah_faktur_giro").val('');
            }
        });
        $form.find("#jumlah_faktur_giro").on('input change', function() {
            clearError($(this), $form.find("#error-jumlah-faktur-giro"));
        });

        function updateTotalGiro() {
            let grandTotal = 0;
            $form.find(".detail-jumlah-giro").each(function() {
                grandTotal += parseMoney($(this).val());
            });
            $form.find("#totalGiroText").text(formatRupiahNumber(grandTotal));
            if ($form.find("#loaddetailfakturgiro tr").length > 0) {
                $form.find("#footertabelgiro").removeClass('hidden');
                clearError($form.find("#tabelfakturgiro"), $form.find("#error-tabel-faktur-giro"));
            } else {
                $form.find("#footertabelgiro").addClass('hidden');
            }
        }

        // Add Faktur
        $form.find("#tambahfakturgiro").on('click', function(e) {
            e.preventDefault();
            const no_faktur = $form.find("#no_faktur_giro_group").val();
            const jumlahStr = $form.find("#jumlah_faktur_giro").val();
            const jumlah = parseMoney(jumlahStr);
            const sisa_piutang = parseMoney($form.find("#sisa_piutang_giro").val());

            if (!no_faktur) {
                showError($form.find("#no_faktur_giro_group").closest('.select2-container-v2'), $form.find("#error-no-faktur-giro"));
                return;
            }

            if (!jumlah || jumlah <= 0) {
                showError($form.find("#jumlah_faktur_giro"), $form.find("#error-jumlah-faktur-giro"), "Jumlah bayar harus lebih dari 0");
                return;
            }

            if (jumlah > sisa_piutang) {
                showError($form.find("#jumlah_faktur_giro"), $form.find("#error-jumlah-faktur-giro"), `Jumlah melebihi sisa piutang (Rp ${formatRupiahNumber(sisa_piutang)})`);
                return;
            }

            if ($form.find(`#index_${no_faktur}`).length > 0) {
                showError($form.find("#no_faktur_giro_group").closest('.select2-container-v2'), $form.find("#error-no-faktur-giro"), "Faktur ini sudah ada dalam daftar");
                return;
            }

            const row = `
                <tr id="index_${no_faktur}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                    <td class="py-1.5 px-3 font-semibold text-brand-600 dark:text-brand-400 font-mono-num">
                        <input type="hidden" name="no_faktur[]" value="${no_faktur}">
                        <span>${no_faktur}</span>
                    </td>
                    <td class="py-1.5 px-3 text-right font-bold font-mono-num text-slate-900 dark:text-white">
                        <input type="hidden" name="jml[]" class="detail-jumlah-giro" value="${jumlahStr}">
                        <span>${formatRupiahNumber(jumlah)}</span>
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="btn-hapus-faktur-giro w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 transition inline-flex items-center justify-center cursor-pointer" data-target="${no_faktur}">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            $form.find("#loaddetailfakturgiro").append(row);
            if (typeof lucide !== 'undefined') lucide.createIcons();

            $form.find("#no_faktur_giro_group").val('').trigger('change');
            $form.find("#jumlah_faktur_giro").val('');
            $form.find("#sisa_piutang_giro").val('');
            updateTotalGiro();
        });

        $(document).on('click', '.btn-hapus-faktur-giro', function(e) {
            e.preventDefault();
            const target = $(this).data('target');
            $form.find(`#index_${target}`).remove();
            updateTotalGiro();
        });

        $form.find("#btnCancelCreateGroupGiro").on('click', function() {
            if (typeof modalCreateGroupGiro !== 'undefined') {
                modalCreateGroupGiro.close();
            } else {
                $("#modalCreateGroupGiro").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_giro_group").val();
            const kode_pelanggan = $form.find("#kode_pelanggan_giro_group").val();
            const no_giro = $form.find("#no_giro_group").val();
            const kode_salesman = $form.find("#kode_salesman_penagih_giro").val();
            const bank_pengirim = $form.find("#bank_pengirim_giro").val();
            const jatuh_tempo = $form.find("#jatuh_tempo_giro").val();
            const totalFaktur = $form.find("#loaddetailfakturgiro tr").length;

            if (!tanggal) {
                showError($form.find("#tanggal_giro_group"), $form.find("#error-tanggal-giro"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_giro_group");
            }

            if (!kode_pelanggan) {
                showError($form.find("#kode_pelanggan_giro_group"), $form.find("#error-kode_pelanggan-giro"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_pelanggan_giro_group");
            }

            if (!no_giro) {
                showError($form.find("#no_giro_group"), $form.find("#error-no-giro"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#no_giro_group");
            }

            if (!kode_salesman) {
                showError($form.find("#kode_salesman_penagih_giro").closest('.select2-container-v2'), $form.find("#error-salesman-penagih-giro-giro"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_salesman_penagih_giro");
            }

            if (!bank_pengirim) {
                showError($form.find("#bank_pengirim_giro"), $form.find("#error-bank-pengirim-giro"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#bank_pengirim_giro");
            }

            if (!jatuh_tempo) {
                showError($form.find("#jatuh_tempo_giro"), $form.find("#error-jatuh-tempo-giro"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jatuh_tempo_giro");
            }

            if (totalFaktur === 0) {
                $form.find("#error-tabel-faktur-giro").removeClass('hidden').addClass('flex');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#no_faktur_giro_group");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanGiroGroup");
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
