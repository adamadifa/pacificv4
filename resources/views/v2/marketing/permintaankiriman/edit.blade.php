<form action="{{ route('permintaankiriman.update', Crypt::encrypt($pk->no_permintaan)) }}" method="POST" id="formeditPermintaankiriman" class="space-y-2.5" autocomplete="off">
    @csrf
    <input type="hidden" id="cektutuplaporan_edit" value="0">

    <!-- SECTION DOKUMEN & PARAMETER PERMINTAAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-2.5 sm:p-3 bg-slate-50/50 dark:bg-slate-900/40">
        
        <!-- Document Header Strip -->
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block leading-none">No. Permintaan</span>
                    <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-xs sm:text-sm">
                        {{ $pk->no_permintaan }}
                    </span>
                </div>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50">
                <i data-lucide="edit-3" class="w-3 h-3"></i>
                <span>Mode Edit</span>
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            
            <!-- Tanggal Permintaan (Col-span 6 - Full Width Bagi 2) -->
            <div class="sm:col-span-6 field-group">
                <label for="tanggal_edit" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5 whitespace-nowrap">
                    Tanggal Permintaan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal_edit" value="{{ $pk->tanggal }}" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
            </div>

            <!-- Cabang (Col-span 6 - Full Width Bagi 2) -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_cabang_edit" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Cabang Tujuan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_edit"
                        class="select2KodecabangEdit select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ $pk->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                                {{ $c->kode_cabang }} - {{ textUpperCase($c->nama_cabang) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Salesman (Col-span 12, Conditional TSM) -->
            <div class="sm:col-span-12 field-group {{ $pk->kode_cabang == 'TSM' ? '' : 'hidden' }}" id="salesmanGroupEdit">
                <label for="kode_salesman_edit" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Salesman (TSM)
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_salesman" id="kode_salesman_edit"
                        class="select2KodesalesmanEdit select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Salesman</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan (Col-span 12) -->
            <div class="sm:col-span-12 field-group">
                <label for="keterangan_edit" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Keterangan Permintaan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="keterangan" id="keterangan_edit" value="{{ $pk->keterangan }}" placeholder="Contoh: Permintaan stok reguler cabang Tasikmalaya"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

        </div>
    </div>

    <!-- SECTION INPUT DETAIL ITEM (TAMBAH PRODUK BARU KE TABEL) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Tambah Produk Baru ke Daftar</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            
            <!-- Pilih Produk (Col-span 7) -->
            <div class="sm:col-span-7 field-group">
                <label for="kode_produk_select_edit" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Pilih Produk
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="box" class="w-3.5 h-3.5"></i>
                    </div>
                    <select id="kode_produk_select_edit"
                        class="select2KodeprodukEdit select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Produk</option>
                        @foreach ($produk as $p)
                            <option value="{{ $p->kode_produk }}">{{ $p->kode_produk }} - {{ $p->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jumlah (Col-span 3) -->
            <div class="sm:col-span-3 field-group">
                <label for="jumlah_input_edit" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Jumlah
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="hash" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" id="jumlah_input_edit" placeholder="0"
                        class="money w-full h-[34px] pl-8 pr-2.5 text-xs text-right font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tombol Tambahkan Produk (Col-span 2) with Anti-Layout Shift Spacer -->
            <div class="sm:col-span-2">
                <label class="hidden sm:block text-[11px] font-bold text-transparent mb-0.5 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="btnTambahProdukEdit"
                    class="w-full h-[34px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah</span>
                </button>
            </div>

        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[260px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabledetailProdukEdit">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Strip Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Daftar Produk Permintaan</span>
                                </div>
                                <span id="itemCountBadgeEdit" class="text-[10.5px] text-white/70 font-mono-num">{{ count($detail) }} item</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right w-36 font-bold uppercase tracking-wider text-white/90 border-none">JUMLAH</th>
                        <th class="py-2 px-3 text-center w-14 font-bold uppercase tracking-wider text-white/90 border-none">#</th>
                    </tr>
                </thead>
                <tbody id="loaddetailEdit" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        <tr id="index_{{ $d->kode_produk }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-2 px-3">
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-2 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-2 px-3 text-right">
                                <input type="text" name="jml[]" value="{{ formatAngka($d->jumlah) }}"
                                    class="money w-28 h-[30px] px-2 text-right font-mono-num font-bold text-xs rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <button type="button" data-kode="{{ $d->kode_produk }}"
                                    class="btnDeleteRow w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                    title="Hapus Produk">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRowEdit">
                            <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Belum ada rincian produk</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ERROR CONTAINER SUMMARY JIKA DETAIL KOSONG -->
    <div id="errorDetailEmptyEdit" class="hidden rounded-lg p-2.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-700 dark:text-rose-400 text-xs font-semibold flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-500"></i>
        <span>Detail produk masih kosong! Harap cantumkan minimal 1 produk sebelum menyimpan perubahan.</span>
    </div>

    <!-- FOOTER ACTIONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3">
        <div class="field-group">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="agreement_check_edit"
                    class="w-4 h-4 rounded-sm border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 cursor-pointer">
                <label for="agreement_check_edit" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                    Konfirmasi perubahan data permintaan sudah benar
                </label>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpanEdit"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                <span id="btnSimpanEditText">Simpan Perubahan</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formeditPermintaankiriman');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Flatpickr Init
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Initialize Select2 (Floating Mount to document.body per GEMINI.md)
        $form.find('.select2KodecabangEdit').select2({
            placeholder: "Pilih Cabang",
            allowClear: true,
            width: '100%'
        });

        $form.find('.select2KodeprodukEdit').select2({
            placeholder: "Pilih Produk",
            allowClear: true,
            width: '100%'
        });

        $form.find('.select2KodesalesmanEdit').select2({
            placeholder: "Pilih Salesman",
            allowClear: true,
            width: '100%'
        });

        // Money Masking
        function initMaskMoney() {
            if (typeof $.fn.maskMoney !== 'undefined') {
                $form.find(".money").maskMoney({
                    thousands: '.',
                    decimal: ',',
                    precision: 0,
                    allowZero: true
                });
            }
        }
        initMaskMoney();

        // Helper Error Display
        function showError($field, message) {
            $field.addClass('!border-rose-500 !ring-1 !ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('!text-rose-500');
            $group.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearError($field) {
            $field.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('!text-rose-500');
            $group.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $group.find('.field-error-msg').remove();
        }

        // Live cleanup on input & select
        $form.on('input change', 'input, select', function() {
            clearError($(this));
            $('#errorDetailEmptyEdit').addClass('hidden');
        });

        $form.find('.select2KodecabangEdit, .select2KodeprodukEdit, .select2KodesalesmanEdit').on('select2:select select2:clear', function() {
            clearError($(this));
        });

        $form.find('#agreement_check_edit').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).removeClass('!ring-2 !ring-rose-500');
                $(this).closest('.field-group').find('.field-error-msg').remove();
            }
        });

        // Check CekTutupLaporan
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
                    $form.find("#cektutuplaporan_edit").val(respond);
                }
            });
        }

        // Badge Count
        function updateBadgeCount() {
            const count = $form.find('#loaddetailEdit tr[id^="index_"]').length;
            $form.find('#itemCountBadgeEdit').text(`${count} item`);
            if (count > 0) {
                $('#errorDetailEmptyEdit').addClass('hidden');
                $form.find('#emptyRowEdit').remove();
            } else {
                if ($form.find('#emptyRowEdit').length === 0) {
                    $form.find('#loaddetailEdit').html(`
                        <tr id="emptyRowEdit">
                            <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Belum ada rincian produk</span>
                                </div>
                            </td>
                        </tr>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            }
        }

        // Salesman TSM Dynamic Handler
        function getsalesmanbyCabang() {
            const kode_cabang = $form.find("#kode_cabang_edit").val();
            const kode_salesman = "{{ $pk->kode_salesman }}";
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_salesman_edit").html(respond).trigger('change');
                }
            });
        }

        function showhideSalesman() {
            const kode_cabang = $form.find("#kode_cabang_edit").val();
            if (kode_cabang === "TSM") {
                $form.find("#salesmanGroupEdit").removeClass('hidden');
            } else {
                $form.find("#salesmanGroupEdit").addClass('hidden');
                $form.find('#kode_salesman_edit').val('').trigger("change");
            }
        }

        // Initial setup
        showhideSalesman();
        if ("{{ $pk->kode_cabang }}" === "TSM") {
            getsalesmanbyCabang();
        }
        cektutuplaporan($form.find("#tanggal_edit").val(), "gudangjadi");

        $form.find("#tanggal_edit").on('change', function() {
            cektutuplaporan($(this).val(), "gudangjadi");
        });

        $form.find("#kode_cabang_edit").on('change select2:select', function() {
            const kode_cabang = $(this).val();
            showhideSalesman();
            if (kode_cabang === "TSM") {
                getsalesmanbyCabang();
            }
        });

        // Tambah Produk Baru ke Tabel Edit
        $form.find("#btnTambahProdukEdit").on('click', function(e) {
            e.preventDefault();
            const $kodeProduk = $form.find("#kode_produk_select_edit");
            const $jumlah = $form.find("#jumlah_input_edit");

            const kode = $kodeProduk.val();
            const nama = $kodeProduk.find('option:selected').text().split(' - ').slice(1).join(' - ') || $kodeProduk.find('option:selected').text();
            const jumlah = $jumlah.val();

            let hasAddError = false;
            let $firstAddError = null;

            if (!kode) {
                showError($kodeProduk, 'Pilih produk terlebih dahulu');
                hasAddError = true;
                $firstAddError = $kodeProduk;
            }

            if (!jumlah || jumlah === "0") {
                showError($jumlah, 'Jumlah wajib diisi');
                hasAddError = true;
                if (!$firstAddError) $firstAddError = $jumlah;
            }

            if ($form.find(`#tabledetailProdukEdit #index_${kode}`).length > 0) {
                showError($kodeProduk, 'Produk ini sudah ada dalam daftar');
                hasAddError = true;
                if (!$firstAddError) $firstAddError = $kodeProduk;
            }

            if (hasAddError) {
                if ($firstAddError) {
                    if ($firstAddError.hasClass('select2KodeprodukEdit')) {
                        $firstAddError.select2('open');
                    } else {
                        $firstAddError.focus();
                    }
                }
                return;
            }

            // Append row
            $form.find('#emptyRowEdit').remove();
            const newRow = `
                <tr id="index_${kode}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                    <td class="py-2 px-3">
                        <input type="hidden" name="kode_produk[]" value="${kode}">
                        <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">${kode}</span>
                    </td>
                    <td class="py-2 px-3 font-semibold text-slate-800 dark:text-slate-100">
                        ${nama}
                    </td>
                    <td class="py-2 px-3 text-right">
                        <input type="text" name="jml[]" value="${jumlah}"
                            class="money w-28 h-[30px] px-2 text-right font-mono-num font-bold text-xs rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition">
                    </td>
                    <td class="py-2 px-3 text-center">
                        <button type="button" data-kode="${kode}"
                            class="btnDeleteRow w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                            title="Hapus Produk">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            $form.find('#loaddetailEdit').prepend(newRow);
            initMaskMoney();
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Reset inputs
            $jumlah.val("");
            $kodeProduk.val('').trigger("change");
            clearError($kodeProduk);
            clearError($jumlah);
            updateBadgeCount();
        });

        // Delete Row in Edit
        $form.on('click', '.btnDeleteRow', function(e) {
            e.preventDefault();
            const kode = $(this).data('kode');
            $form.find(`#index_${kode}`).remove();
            updateBadgeCount();
        });

        // Submit Form Validation
        $form.on('submit', function(e) {
            const $tanggal = $form.find("#tanggal_edit");
            const $kodeCabang = $form.find("#kode_cabang_edit");
            const $keterangan = $form.find("#keterangan_edit");
            const cektutup = $form.find("#cektutuplaporan_edit").val();
            const detailCount = $form.find('#loaddetailEdit tr[id^="index_"]').length;
            const $agreement = $form.find('#agreement_check_edit');

            let hasError = false;
            let $firstError = null;

            if (!$tanggal.val().trim()) {
                showError($tanggal, 'Tanggal permintaan wajib diisi');
                hasError = true;
                if (!$firstError) $firstError = $tanggal;
            }

            if (!$kodeCabang.val()) {
                showError($kodeCabang, 'Pilih cabang tujuan');
                hasError = true;
                if (!$firstError) $firstError = $kodeCabang;
            }

            if (!$keterangan.val().trim()) {
                showError($keterangan, 'Keterangan permintaan wajib diisi');
                hasError = true;
                if (!$firstError) $firstError = $keterangan;
            }

            if (cektutup === '1') {
                Swal.fire({
                    title: "Peringatan",
                    text: "Laporan untuk periode tanggal ini sudah ditutup!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            if (detailCount === 0) {
                $('#errorDetailEmptyEdit').removeClass('hidden');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_produk_select_edit");
            } else {
                $('#errorDetailEmptyEdit').addClass('hidden');
            }

            if (!$agreement.is(':checked')) {
                $agreement.addClass('!ring-2 !ring-rose-500');
                const $agGroup = $agreement.closest('.field-group');
                $agGroup.find('.field-error-msg').remove();
                $agGroup.append(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Centang konfirmasi terlebih dahulu</span>
                    </p>
                `);
                hasError = true;
                if (!$firstError) $firstError = $agreement;
            } else {
                $agreement.removeClass('!ring-2 !ring-rose-500');
                $agreement.closest('.field-group').find('.field-error-msg').remove();
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) {
                    if ($firstError.hasClass('select2KodecabangEdit') || $firstError.hasClass('select2KodeprodukEdit')) {
                        $firstError.select2('open');
                    } else {
                        $firstError.focus();
                    }
                }
                return false;
            }

            // Anti-Double Submit
            const $btn = $form.find('#btnSimpanEdit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.find('i').remove();
            $form.find('#btnSimpanEditText').html(`
                <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `);
        });

    });
</script>
