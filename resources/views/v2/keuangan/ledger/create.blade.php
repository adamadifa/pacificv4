<form action="{{ route('ledger.store') }}" method="POST" id="formLedger" class="space-y-3.5" autocomplete="off">
    @csrf
    <input type="hidden" id="cektutuplaporan" value="0">

    <!-- MASTER BANK SELECTION -->
    <div class="space-y-1">
        <label for="kode_bank_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Bank Rekening <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="landmark" class="w-4 h-4"></i>
            </div>
            <select name="kode_bank" id="kode_bank_create" class="select2Kodebank w-full">
                <option value="">Pilih Bank</option>
                @foreach ($bank as $d)
                    <option value="{{ $d->kode_bank }}">
                        {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <p id="error-bank-ledger" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Bank rekening wajib dipilih</span>
        </p>
    </div>

    <!-- CARD INPUT RINCIAN ITEM -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 space-y-3">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-700 dark:text-slate-200">Tambah Rincian Transaksi Ledger</h6>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-start">
            <!-- Tanggal -->
            <div class="space-y-1 lg:col-span-3">
                <label for="tanggal_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Tanggal <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="tanggal_item" readonly placeholder="Pilih Tanggal"
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
                <p id="error-tanggal-item" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Tanggal wajib diisi</span>
                </p>
            </div>

            <!-- Pelanggan -->
            <div class="space-y-1 lg:col-span-4">
                <label for="pelanggan_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Pelanggan (Opsional)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="pelanggan_item" placeholder="Nama pelanggan / pihak ketiga"
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>

            <!-- Kode Akun (COA) -->
            <div class="space-y-1 lg:col-span-5">
                <label for="kode_akun_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Kode Akun <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                    </div>
                    <select id="kode_akun_item" class="select2Kodeakun w-full">
                        <option value="">Pilih Kode Akun</option>
                        @foreach ($coa as $d)
                            <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}</option>
                        @endforeach
                    </select>
                </div>
                <p id="error-akun-item" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Kode akun wajib dipilih</span>
                </p>
            </div>
        </div>

        <!-- Baris Kedua: Keterangan, Jumlah, Jenis, Peruntukan, Cabang, Tombol -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-start">
            <!-- Keterangan -->
            <div class="space-y-1 lg:col-span-4">
                <label for="keterangan_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Keterangan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="keterangan_item" placeholder="Keterangan transaksi ledger"
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
                <p id="error-keterangan-item" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Keterangan wajib diisi</span>
                </p>
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

            <!-- Debet / Kredit -->
            <div class="space-y-1 lg:col-span-2">
                <label for="debet_kredit_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Debet / Kredit <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                    </div>
                    <select id="debet_kredit_item"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                        <option value="">Pilih</option>
                        <option value="D">Debet (Keluar)</option>
                        <option value="K">Kredit (Masuk)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
                <p id="error-dk-item" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Pilih Debet/Kredit</span>
                </p>
            </div>

            <!-- Peruntukan -->
            <div class="space-y-1 lg:col-span-2">
                <label for="kode_peruntukan_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Peruntukan
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="tag" class="w-4 h-4"></i>
                    </div>
                    <select id="kode_peruntukan_item"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                        <option value="">Pilih</option>
                        <option value="MP">MP</option>
                        <option value="PC">PACIFIC</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Cabang (Keterangan Peruntukan Jika PC) -->
            <div id="containerCabangItem" class="hidden space-y-1 lg:col-span-2">
                <label for="kode_cabang_item" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Cabang <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <select id="kode_cabang_item" class="select2Kodecabang w-full">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
                <p id="error-cabang-item" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Cabang wajib dipilih</span>
                </p>
            </div>

            <!-- Tombol Tambah Item -->
            <div class="space-y-1 lg:col-span-1">
                <label class="hidden lg:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="btnTambahItemLedger"
                    class="w-full h-[36px] px-2.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span class="lg:hidden">Tambah Item</span>
                </button>
            </div>
        </div>
    </div>

    <!-- TABEL DAFTAR ITEM TRANSAKSI LEDGER -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-1.5">
                <i data-lucide="list-checks" class="w-3.5 h-3.5 text-blue-200"></i>
                <h6 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Rincian Ledger</h6>
            </div>
            <span id="counterItemLedger" class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                0 Item
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">PELANGGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">KETERANGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">KODE AKUN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">DEBET</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">KREDIT</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-12">AKSI</th>
                    </tr>
                </thead>
                <tbody id="loaditem" class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    <tr id="emptyItemRow">
                        <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="book-open" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-semibold">Belum ada item transaksi yang ditambahkan ke daftar ledger</span>
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
            <input type="checkbox" id="agreementLedger" class="rounded border-slate-300 dark:border-slate-700 text-[#284c9a] focus:ring-0 focus:outline-hidden w-4 h-4 cursor-pointer">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Yakin data transaksi ledger ini sudah benar dan siap disimpan?</span>
        </label>

        <div id="saveButtonGroup" class="hidden flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
            <button type="button" id="btnCancelCreateLedger"
                class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpanLedger"
                class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                <span>Simpan Ledger</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formLedger");
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

        const $select2Bank = $form.find('.select2Kodebank');
        if ($select2Bank.length) {
            $select2Bank.select2({
                placeholder: 'Pilih Bank',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        const $select2Akun = $form.find('.select2Kodeakun');
        if ($select2Akun.length) {
            $select2Akun.select2({
                placeholder: 'Pilih Kode Akun',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        const $select2Cabang = $form.find('.select2Kodecabang');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Pilih Cabang',
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
        $form.find("#kode_bank_create").on('change select2:select', function() {
            clearError($(this), $form.find("#error-bank-ledger"));
        });
        $form.find("#tanggal_item").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-item"));
            checkTutupLaporan();
        });
        $form.find("#kode_akun_item").on('change select2:select', function() {
            clearError($(this), $form.find("#error-akun-item"));
        });
        $form.find("#keterangan_item").on('input change', function() {
            clearError($(this), $form.find("#error-keterangan-item"));
        });
        $form.find("#jumlah_item").on('input change', function() {
            clearError($(this), $form.find("#error-jumlah-item"));
        });
        $form.find("#debet_kredit_item").on('change', function() {
            clearError($(this), $form.find("#error-dk-item"));
        });
        $form.find("#kode_cabang_item").on('change select2:select', function() {
            clearError($(this), $form.find("#error-cabang-item"));
        });

        // Toggle peruntukan cabang
        $form.find("#kode_peruntukan_item").on('change', function() {
            if ($(this).val() === 'PC') {
                $form.find("#containerCabangItem").removeClass('hidden');
            } else {
                $form.find("#containerCabangItem").addClass('hidden');
                $form.find("#kode_cabang_item").val('').trigger('change');
            }
        });

        // Agreement toggle
        $form.find("#agreementLedger").on('change', function() {
            if (this.checked) {
                $form.find("#saveButtonGroup").removeClass('hidden');
            } else {
                $form.find("#saveButtonGroup").addClass('hidden');
            }
        });

        // Cancel modal
        $form.find("#btnCancelCreateLedger").on('click', function() {
            if (typeof modalLedgerHelper !== 'undefined') {
                modalLedgerHelper.close();
            } else {
                $("#modalLedger").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        function checkTutupLaporan() {
            const tanggal = $form.find("#tanggal_item").val();
            if (!tanggal) return;
            $.ajax({
                type: 'POST',
                url: '/tutuplaporan/cektutuplaporan',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: "ledger"
                },
                cache: false,
                success: function(response) {
                    $form.find("#cektutuplaporan").val(response);
                    if (parseInt(response) > 0) {
                        showError($form.find("#tanggal_item"), $form.find("#error-tanggal-item"), "Laporan periode ini sudah ditutup!");
                    }
                }
            });
        }

        // Warning reset when changing bank if rows exist
        $form.find("#kode_bank_create").on('select2:selecting', function(e) {
            const count = $form.find('#loaditem tr:not(#emptyItemRow)').length;
            if (count > 0) {
                const self = this;
                const nextVal = e.params.args.data.id;
                e.preventDefault();
                Swal.fire({
                    title: 'Ganti Pilihan Bank?',
                    text: 'Jika Anda mengganti bank, seluruh daftar rincian item transaksi yang sudah diinput akan di-reset.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#284c9a',
                    confirmButtonText: 'Ya, Ganti & Reset!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $form.find('#loaditem').html(`
                            <tr id="emptyItemRow">
                                <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <i data-lucide="book-open" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                        <span class="text-xs font-semibold">Belum ada item transaksi yang ditambahkan ke daftar ledger</span>
                                    </div>
                                </td>
                            </tr>
                        `);
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                        updateItemCounter();
                        $select2Bank.val(nextVal).trigger('change.select2');
                    }
                });
            }
        });

        function parseMoney(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        function updateItemCounter() {
            const count = $form.find('#loaditem tr:not(#emptyItemRow)').length;
            $form.find("#counterItemLedger").text(`${count} Item`);
            if (count === 0) {
                $form.find("#emptyItemRow").show();
            } else {
                $form.find("#emptyItemRow").hide();
            }
        }

        function resetItemInputs() {
            $form.find("#tanggal_item").val('');
            $form.find("#pelanggan_item").val('');
            $form.find("#keterangan_item").val('');
            $form.find("#kode_akun_item").val('').trigger('change');
            $form.find("#jumlah_item").val('');
            $form.find("#debet_kredit_item").val('');
            $form.find("#kode_peruntukan_item").val('').trigger('change');
            $form.find("#kode_cabang_item").val('').trigger('change');
        }

        // Action: Tambah Item ke Tabel
        $form.find("#btnTambahItemLedger").on('click', function(e) {
            e.preventDefault();
            const kode_bank = $form.find("#kode_bank_create").val();
            const tanggal = $form.find("#tanggal_item").val().trim();
            const pelanggan = $form.find("#pelanggan_item").val().trim();
            const keterangan = $form.find("#keterangan_item").val().trim();
            const $akunOpt = $form.find("#kode_akun_item option:selected");
            const kode_akun = $akunOpt.val();
            const nama_akun = $akunOpt.text();
            const jumlahStr = $form.find("#jumlah_item").val();
            const jumlah = parseMoney(jumlahStr);
            const debet_kredit = $form.find("#debet_kredit_item").val();
            const kode_peruntukan = $form.find("#kode_peruntukan_item").val();
            const kode_cabang = $form.find("#kode_cabang_item").val();
            const cektutup = parseInt($form.find("#cektutuplaporan").val()) || 0;

            let hasError = false;
            let $firstError = null;

            if (!kode_bank) {
                showError($form.find("#kode_bank_create"), $form.find("#error-bank-ledger"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_bank_create");
            }

            if (cektutup > 0) {
                showError($form.find("#tanggal_item"), $form.find("#error-tanggal-item"), "Periode laporan sudah ditutup!");
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_item");
            } else if (!tanggal) {
                showError($form.find("#tanggal_item"), $form.find("#error-tanggal-item"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_item");
            }

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

            if (!debet_kredit) {
                showError($form.find("#debet_kredit_item"), $form.find("#error-dk-item"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#debet_kredit_item");
            }

            if (kode_peruntukan === 'PC' && !kode_cabang) {
                showError($form.find("#kode_cabang_item"), $form.find("#error-cabang-item"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_cabang_item");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            baris++;
            const debet = debet_kredit === 'D' ? jumlahStr : '-';
            const kredit = debet_kredit === 'K' ? jumlahStr : '-';

            // Pisahkan format tanggal d-m-y
            let parts = tanggal.split("-");
            let displayTgl = `${parseInt(parts[2])}-${parseInt(parts[1])}-${parts[0].substr(-2)}`;

            let badgePeruntukan = '';
            if (kode_peruntukan === 'MP') {
                badgePeruntukan = '<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">MP</span>';
            } else if (kode_peruntukan === 'PC') {
                badgePeruntukan = `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300">PC - ${kode_cabang}</span>`;
            }

            const rowHtml = `
                <tr id="index_${baris}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                    <td class="py-1.5 px-3 text-center font-mono-num font-semibold text-slate-700 dark:text-slate-300">
                        <input type="hidden" name="tanggal_item[]" value="${tanggal}" />
                        <input type="hidden" name="pelanggan_item[]" value="${pelanggan}" />
                        <input type="hidden" name="keterangan_item[]" value="${keterangan}" />
                        <input type="hidden" name="kode_akun_item[]" value="${kode_akun}" />
                        <input type="hidden" name="jumlah_item[]" value="${jumlahStr}" />
                        <input type="hidden" name="debet_kredit_item[]" value="${debet_kredit}" />
                        <input type="hidden" name="kode_peruntukan_item[]" value="${kode_peruntukan}" />
                        <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}" />
                        <span>${displayTgl}</span>
                    </td>
                    <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                        <span>${pelanggan || '-'}</span>
                    </td>
                    <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                        <div class="flex items-center gap-1.5">
                            <span>${keterangan}</span>
                            ${badgePeruntukan}
                        </div>
                    </td>
                    <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                        <span>${nama_akun}</span>
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                        ${debet}
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                        ${kredit}
                    </td>
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

        // Delete row item
        $form.on('click', '.btnDeleteRow', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            updateItemCounter();
        });

        // Submit listener
        $form.on('submit', function(e) {
            e.preventDefault();
            const bankVal = $form.find("#kode_bank_create").val();
            if (!bankVal) {
                showError($form.find("#kode_bank_create"), $form.find("#error-bank-ledger"));
                $form.find("#kode_bank_create").focus();
                return false;
            }

            const itemCount = $form.find('#loaditem tr:not(#emptyItemRow)').length;
            if (itemCount === 0) {
                Swal.fire({
                    title: "Rincian Transaksi Kosong!",
                    text: "Silakan tambahkan minimal 1 transaksi ke dalam daftar rincian ledger sebelum menyimpan.",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                });
                return false;
            }

            const $btn = $form.find("#btnSimpanLedger");
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
