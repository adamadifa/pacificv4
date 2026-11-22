<form action="{{ route('repackgudangjadi.update', Crypt::encrypt($repack->no_mutasi)) }}" method="POST" id="formeditRepack" autocomplete="off" class="space-y-4">
    @csrf
    <input type="hidden" id="cektutuplaporan">

    <!-- BARIS 1: NO MUTASI & TANGGAL -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <div class="field-group">
            <label for="no_mutasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Mutasi Repack
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_mutasi" id="no_mutasi" readonly value="{{ $repack->no_mutasi }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#284c9a] dark:text-blue-400 cursor-not-allowed">
            </div>
        </div>

        <div class="field-group">
            <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Repack <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" value="{{ $repack->tanggal }}" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- SEPARATOR BAR -->
    <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pilih & Tambah Produk</span>
    </div>

    <!-- INPUT PRODUK & JUMLAH (ANTI-LAYOUT SHIFT: ITEMS-START + SPACER LABEL) -->
    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/30 p-3 sm:p-3.5">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
            
            <div class="sm:col-span-6 field-group">
                <label for="kode_produk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Pilih Produk <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="box" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_produk" id="kode_produk" class="select2Kodeproduk w-full">
                        <option value="">Pilih Produk</option>
                        @foreach ($produk as $d)
                            <option value="{{ $d->kode_produk }}">{{ $d->kode_produk }} - {{ $d->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="sm:col-span-4 field-group">
                <label for="jumlah" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Jumlah <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="jumlah" id="jumlah" placeholder="0"
                        class="money w-full h-[36px] pl-9 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="tambahproduk"
                    class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah</span>
                </button>
            </div>

        </div>
    </div>

    <!-- DETAIL PRODUK TABLE (Dua Baris Thead V2 Standard) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabledetailProduk">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="list" class="w-3.5 h-3.5 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Repack</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-36">JUMLAH</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-16">AKSI</th>
                    </tr>
                </thead>
                <tbody id="loaddetail" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        <tr id="{{ 'index_' . $d->kode_produk }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                {{ $d->kode_produk }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">{{ $d->nama_produk }}</td>
                            <td class="py-1 px-3 text-right">
                                <input type="text" name="jml[]" value="{{ formatAngka($d->jumlah) }}"
                                    class="money w-28 h-[30px] px-2 text-right font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500" />
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <button type="button" class="btnDeleteRow w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 transition inline-flex items-center justify-center cursor-pointer">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="4" class="py-6 text-center text-slate-400">
                                Belum ada produk yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- AGREEMENT & SUBMIT BUTTON -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="aggrement" id="defaultCheck3" value="aggrement"
                class="agreement w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Yakin perubahan data sudah benar?</span>
        </label>

        <button type="submit" id="btnSimpan"
            class="h-[36px] inline-flex items-center justify-center gap-2 px-6 rounded-lg bg-[#284c9a] hover:bg-blue-800 active:bg-blue-900 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const formEdit = $("#formeditRepack");

        formEdit.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        if (typeof $ !== 'undefined' && $.fn.maskMoney) {
            formEdit.find(".money").maskMoney();
        }

        // Initialize Select2 V2
        formEdit.find('.select2Kodeproduk').select2({
            placeholder: 'Pilih Produk',
            width: '100%'
        });

        function setFieldError($field, message) {
            clearFieldError($field);
            const $group = $field.closest('.field-group');
            $group.addClass('has-error');
            if ($field.hasClass('select2-hidden-accessible')) {
                const $s2Container = $field.next('.select2');
                $s2Container.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $field.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid');
            }
            $group.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($field) {
            const $group = $field.closest('.field-group');
            $group.removeClass('has-error');
            if ($field.hasClass('select2-hidden-accessible')) {
                const $s2Container = $field.next('.select2');
                $s2Container.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $field.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid');
            }
            $group.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $group.find('.field-error-msg').remove();
        }

        formEdit.find('#tanggal').on('change', function() {
            clearFieldError($(this));
            if (typeof cektutuplaporan === 'function') {
                cektutuplaporan($(this).val(), "gudangjadi");
            }
        });

        formEdit.find('#kode_produk').on('change select2:select', function() {
            clearFieldError($(this));
        });

        formEdit.find('#jumlah').on('input change', function() {
            clearFieldError($(this));
        });

        // Add Product to Table
        formEdit.find("#tambahproduk").on('click', function(e) {
            e.preventDefault();
            const $kodeField = formEdit.find("#kode_produk");
            const $jumlahField = formEdit.find("#jumlah");
            const kode_produk = $kodeField.val();
            const nama_produk = $kodeField.find("option:selected").text();
            const jumlah = $jumlahField.val();

            let hasErr = false;
            if (!kode_produk) {
                setFieldError($kodeField, 'Silakan pilih produk terlebih dahulu');
                hasErr = true;
            }
            if (!jumlah || jumlah === "0" || jumlah === "0,00") {
                setFieldError($jumlahField, 'Jumlah tidak boleh kosong atau 0');
                hasErr = true;
            }

            if (hasErr) return;

            if (formEdit.find('#tabledetailProduk').find('#index_' + kode_produk).length > 0) {
                setFieldError($kodeField, 'Produk ini sudah ada dalam daftar tabel!');
                return;
            }

            formEdit.find("#emptyRow").remove();

            const row = `
                <tr id="index_${kode_produk}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                    <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                        <input type="hidden" name="kode_produk[]" value="${kode_produk}"/>
                        ${kode_produk}
                    </td>
                    <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">${nama_produk}</td>
                    <td class="py-1 px-3 text-right">
                        <input type="text" name="jml[]" value="${jumlah}"
                            class="money w-28 h-[30px] px-2 text-right font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500" />
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="btnDeleteRow w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 transition inline-flex items-center justify-center cursor-pointer">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            formEdit.find('#loaddetail').append(row);
            if (typeof $ !== 'undefined' && $.fn.maskMoney) {
                formEdit.find(".money").maskMoney();
            }
            $kodeField.val('').trigger('change');
            $jumlahField.val('');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Delete Row
        $(document).on('click', '.btnDeleteRow', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            if (formEdit.find('#loaddetail tr').length === 0) {
                formEdit.find('#loaddetail').html(`
                    <tr id="emptyRow">
                        <td colspan="4" class="py-6 text-center text-slate-400">
                            Belum ada produk yang ditambahkan.
                        </td>
                    </tr>
                `);
            }
        });

        // Form Submit Validation
        formEdit.on('submit', function(e) {
            const $tgl = formEdit.find("#tanggal");
            const $btnSimpan = formEdit.find("#btnSimpan");
            let hasError = false;

            if (!$tgl.val()) {
                setFieldError($tgl, 'Tanggal repack harus diisi');
                $tgl.focus();
                hasError = true;
            }

            if (formEdit.find("#loaddetail input[name='kode_produk[]']").length === 0) {
                Swal.fire({
                    title: "Perhatian",
                    text: "Silakan tambahkan minimal satu produk ke dalam tabel!",
                    icon: "warning",
                    confirmButtonColor: "#284c9a"
                });
                hasError = true;
            }

            if (!formEdit.find("#defaultCheck3").is(":checked")) {
                Swal.fire({
                    title: "Perhatian",
                    text: "Silakan centang persetujuan terlebih dahulu!",
                    icon: "warning",
                    confirmButtonColor: "#284c9a"
                });
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            // Anti double submit
            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
