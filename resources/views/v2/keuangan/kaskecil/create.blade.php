<form action="{{ route('kaskecil.store') }}" method="POST" id="formKaskecil" class="space-y-3.5" autocomplete="off">
    @csrf
    <input type="hidden" id="cektutuplaporan" value="0">

    <!-- HEADER FIELDS: CABANG, NO BUKTI, TANGGAL -->
    <div class="grid grid-cols-1 sm:grid-cols-2 @hasanyrole($roles_show_cabang) lg:grid-cols-3 @endhasanyrole gap-2.5 items-start">
        @hasanyrole($roles_show_cabang)
            <div class="space-y-1">
                <label for="kode_cabang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Cabang <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_create" class="select2Kodecabang w-full">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textuppercase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
                <p id="error-cabang-kaskecil" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Cabang wajib dipilih</span>
                </p>
            </div>
        @endhasanyrole

        <!-- No Bukti -->
        <div class="space-y-1">
            <label for="no_bukti_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                No. Bukti <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" id="no_bukti_create" name="no_bukti" placeholder="Contoh: KK24..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-nobukti-kaskecil" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>No. Bukti wajib diisi</span>
            </p>
        </div>

        <!-- Tanggal -->
        <div class="space-y-1">
            <label for="tanggal_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_create" name="tanggal" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-kaskecil" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal transaksi wajib diisi</span>
            </p>
        </div>
    </div>

    <!-- CARD INPUT RINCIAN ITEM -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 space-y-3">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-700 dark:text-slate-200">Tambah Rincian Transaksi Kas Kecil</h6>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-start">
            <!-- Keterangan -->
            <div class="space-y-1 sm:col-span-2 lg:col-span-4">
                <label for="keterangan_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Keterangan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="keterangan_item" placeholder="Keterangan pengeluaran/penerimaan"
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
                <p id="error-keterangan-item" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Keterangan wajib diisi</span>
                </p>
            </div>

            <!-- Kode Akun (COA) -->
            <div class="space-y-1 sm:col-span-2 lg:col-span-3">
                <label for="kode_akun_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Akun Rekening <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                    </div>
                    <select id="kode_akun_item" class="select2Kodeakun w-full">
                        <option value="">Pilih Akun</option>
                        @foreach ($coa as $d)
                            <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}</option>
                        @endforeach
                    </select>
                </div>
                <p id="error-akun-item" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Akun wajib dipilih</span>
                </p>
            </div>

            <!-- Debet / Kredit -->
            <div class="space-y-1 lg:col-span-2">
                <label for="debet_kredit_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Jenis <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                    </div>
                    <select id="debet_kredit_item"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                        <option value="D" selected>DEBET (Keluar)</option>
                        <option value="K">KREDIT (Masuk)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="space-y-1 lg:col-span-2">
                <label for="jumlah_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Jumlah (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <span class="text-xs font-bold text-slate-400">Rp</span>
                    </div>
                    <input type="text" id="jumlah_item" placeholder="0"
                        class="money w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 text-right font-mono-num placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
                <p id="error-jumlah-item" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Jumlah > 0</span>
                </p>
            </div>

            <!-- Tombol Tambah Item -->
            <div class="space-y-1 lg:col-span-1">
                <label class="hidden lg:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="btnTambahItemKaskecil"
                    class="w-full h-[36px] px-2.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span class="lg:hidden">Tambah Item</span>
                </button>
            </div>
        </div>

        @if (auth()->user()->kode_cabang == 'PST')
            <!-- Pilihan Peruntukan Khusus Pusat -->
            <div class="pt-2 border-t border-slate-200/60 dark:border-slate-800 flex items-center gap-4 text-xs font-bold text-slate-700 dark:text-slate-200">
                <span class="text-slate-400 font-medium">Peruntukan:</span>
                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="kode_peruntukan_radio" value="PC" checked class="w-4 h-4 text-[#284c9a] focus:ring-0 focus:outline-hidden cursor-pointer">
                    <span>Pacific (PC)</span>
                </label>
                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="kode_peruntukan_radio" value="MP" class="w-4 h-4 text-[#284c9a] focus:ring-0 focus:outline-hidden cursor-pointer">
                    <span>Makmur Permata (MP)</span>
                </label>
            </div>
        @endif
    </div>

    <!-- TABEL DAFTAR ITEM TRANSAKSI KAS KECIL -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-1.5">
                <i data-lucide="list-checks" class="w-3.5 h-3.5 text-blue-200"></i>
                <h6 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Rincian Kas Kecil</h6>
            </div>
            <span id="counterItemKaskecil" class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                0 Item
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">KETERANGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">AKUN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">PENERIMAAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">PENGELUARAN</th>
                        @if (auth()->user()->kode_cabang == 'PST')
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">PERUNTUKAN</th>
                        @endif
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-12">AKSI</th>
                    </tr>
                </thead>
                <tbody id="loaditem" class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    <tr id="emptyItemRow">
                        <td colspan="{{ auth()->user()->kode_cabang == 'PST' ? 6 : 5 }}" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="receipt" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-semibold">Belum ada item transaksi yang ditambahkan</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- KLAUSUL PERSETUJUAN & TOMBOL SUBMIT -->
    <div class="space-y-3 pt-2">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" id="agreementKaskecil" class="rounded border-slate-300 dark:border-slate-700 text-[#284c9a] focus:ring-0 focus:outline-hidden w-4 h-4 cursor-pointer">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Yakin data operasional kas kecil ini sudah benar dan siap disimpan?</span>
        </label>

        <div id="saveButtonGroup" class="hidden flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
            <button type="button" id="btnCancelCreateKaskecil"
                class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpanKaskecil"
                class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                <span>Simpan Kas Kecil</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formKaskecil");
        let baris = 0;

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $form.find(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });

        const $select2Cabang = $form.find('.select2Kodecabang');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        const $select2Akun = $form.find('.select2Kodeakun');
        if ($select2Akun.length) {
            $select2Akun.select2({
                placeholder: 'Pilih Akun',
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

        // Live cleanup listeners
        $form.find("#kode_cabang_create").on('change select2:select', function() {
            clearError($(this), $form.find("#error-cabang-kaskecil"));
        });
        $form.find("#no_bukti_create").on('input change', function() {
            clearError($(this), $form.find("#error-nobukti-kaskecil"));
        });
        $form.find("#tanggal_create").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-kaskecil"));
        });
        $form.find("#keterangan_item").on('input change', function() {
            clearError($(this), $form.find("#error-keterangan-item"));
        });
        $form.find("#kode_akun_item").on('change select2:select', function() {
            clearError($(this), $form.find("#error-akun-item"));
        });
        $form.find("#jumlah_item").on('input change', function() {
            clearError($(this), $form.find("#error-jumlah-item"));
        });

        // Toggle Agreement
        $form.find("#agreementKaskecil").on('change', function() {
            if (this.checked) {
                $form.find("#saveButtonGroup").removeClass('hidden');
            } else {
                $form.find("#saveButtonGroup").addClass('hidden');
            }
        });

        // Cancel modal
        $form.find("#btnCancelCreateKaskecil").on('click', function() {
            if (typeof modalKaskecilHelper !== 'undefined') {
                modalKaskecilHelper.close();
            } else {
                $("#modalKaskecil").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        function parseMoney(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        function updateItemCounter() {
            const count = $form.find('#loaditem tr:not(#emptyItemRow)').length;
            $form.find("#counterItemKaskecil").text(`${count} Item`);
            if (count === 0) {
                $form.find("#emptyItemRow").show();
            } else {
                $form.find("#emptyItemRow").hide();
            }
        }

        function resetItemInputs() {
            $form.find("#keterangan_item").val('');
            $form.find("#kode_akun_item").val('').trigger('change');
            $form.find("#jumlah_item").val('');
            $form.find("#debet_kredit_item").val('D');
        }

        // Action: Tambah Item
        $form.find("#btnTambahItemKaskecil").on('click', function(e) {
            e.preventDefault();
            const keterangan = $form.find("#keterangan_item").val().trim();
            const $akunOpt = $form.find("#kode_akun_item option:selected");
            const kode_akun = $akunOpt.val();
            const nama_akun = $akunOpt.text();
            const debet_kredit = $form.find("#debet_kredit_item").val();
            const jumlahStr = $form.find("#jumlah_item").val();
            const jumlah = parseMoney(jumlahStr);
            const kode_peruntukan = $form.find("input[name='kode_peruntukan_radio']:checked").val() || '';

            let hasError = false;
            let $firstError = null;

            if (!keterangan) {
                showError($form.find("#keterangan_item"), $form.find("#error-keterangan-item"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#keterangan_item");
            }

            if (!kode_akun) {
                showError($form.find("#kode_akun_item"), $form.find("#error-akun-item"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_akun_item");
            }

            if (jumlah <= 0) {
                showError($form.find("#jumlah_item"), $form.find("#error-jumlah-item"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jumlah_item");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            baris++;
            const penerimaan = debet_kredit === 'K' ? jumlahStr : '-';
            const pengeluaran = debet_kredit === 'D' ? jumlahStr : '-';

            const peruntukanTd = @json(auth()->user()->kode_cabang == 'PST')
                ? `<td class="py-1.5 px-3 text-center font-bold text-slate-700 dark:text-slate-300">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold ${kode_peruntukan === 'MP' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300'}">${kode_peruntukan}</span>
                   </td>`
                : '';

            const rowHtml = `
                <tr id="index_${baris}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                    <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                        <input type="hidden" name="keterangan_item[]" value="${keterangan}" />
                        <input type="hidden" name="jumlah_item[]" value="${jumlahStr}" />
                        <input type="hidden" name="debet_kredit_item[]" value="${debet_kredit}" />
                        <input type="hidden" name="kode_peruntukan_item[]" value="${kode_peruntukan}" />
                        <span>${keterangan}</span>
                    </td>
                    <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                        <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" />
                        <span>${nama_akun}</span>
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                        ${penerimaan}
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                        ${pengeluaran}
                    </td>
                    ${peruntukanTd}
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="btnDeleteRow w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer m-auto" title="Hapus Item">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            $form.find("#loaditem").append(rowHtml);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            resetItemInputs();
            updateItemCounter();
        });

        // Delete item row
        $form.on('click', '.btnDeleteRow', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            updateItemCounter();
        });

        // Submit form validation
        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const $cabang = $form.find("#kode_cabang_create");
            if ($cabang.length && !$cabang.val()) {
                showError($cabang, $form.find("#error-cabang-kaskecil"));
                hasError = true;
                if (!$firstError) $firstError = $cabang;
            }

            const no_bukti = $form.find("#no_bukti_create").val().trim();
            if (!no_bukti) {
                showError($form.find("#no_bukti_create"), $form.find("#error-nobukti-kaskecil"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#no_bukti_create");
            }

            const tanggal = $form.find("#tanggal_create").val().trim();
            if (!tanggal) {
                showError($form.find("#tanggal_create"), $form.find("#error-tanggal-kaskecil"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_create");
            }

            const itemCount = $form.find('#loaditem tr:not(#emptyItemRow)').length;
            if (itemCount === 0) {
                Swal.fire({
                    title: "Rincian Transaksi Kosong!",
                    text: "Silakan tambahkan minimal 1 rincian transaksi kas kecil ke dalam daftar tabel sebelum menyimpan.",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                });
                return false;
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanKaskecil");
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
