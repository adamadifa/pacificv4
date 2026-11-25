@php
    $total = 0;
    foreach ($detail as $d) {
        $total += $d->jumlah;
    }
@endphp

<form action="{{ route('kontrabonpmb.storeproses', Crypt::encrypt($kontrabon->no_kontrabon)) }}" method="POST" id="formProseskontrabon" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- 1. DOKUMEN RINGKASAN TERPADU (EXECUTIVE SUMMARY STRIP V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex items-center justify-between pb-2.5 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <i data-lucide="receipt" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Dokumen Kontra Bon</h5>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold font-mono-num bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/80 dark:border-blue-900/60">
                {{ $kontrabon->no_kontrabon }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Tanggal Dokumen</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block font-mono-num">{{ DateToIndo($kontrabon->tanggal) }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Terima Dari (Supplier)</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate" title="{{ textUpperCase($kontrabon->nama_supplier) }}">
                    {{ textUpperCase($kontrabon->nama_supplier) }}
                </span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Jenis Pengajuan</span>
                <div class="mt-0.5 flex items-center gap-1.5">
                    @if ($kontrabon->kategori == 'KB')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                            Kontra Bon (KB)
                        </span>
                    @elseif ($kontrabon->kategori == 'IM')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">
                            Internal Memo (IM)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            Tunai (TN)
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Total Tagihan</span>
                <span class="font-bold font-mono-num text-xs text-[#284c9a] dark:text-blue-400 mt-0.5 block">
                    Rp {{ formatAngkaDesimal($total) }}
                </span>
            </div>
        </div>
    </div>

    <!-- 2. TABEL FAKTUR PEMBELIAN TERKAIT -->
    <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
        <div class="overflow-x-auto max-h-[160px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#1e3a78] text-white">
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center border-r border-[#284c9a]/40" style="width: 8%">NO</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40" style="width: 25%">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40">NO. BUKTI</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right" style="width: 28%">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($detail as $d)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 text-center font-bold text-slate-500 border-r border-slate-100 dark:border-slate-800">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 font-mono-num text-slate-700 dark:text-slate-300">
                                {{ DateToIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_bukti }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ formatAngkaDesimal($d->jumlah) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-700 sticky bottom-0">
                    <tr>
                        <td colspan="3" class="py-2 px-3 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-300">TOTAL PEMBAYARAN</td>
                        <td class="py-2 px-3 text-right text-xs font-mono-num font-black text-[#284c9a] dark:text-blue-400">
                            {{ formatAngkaDesimal($total) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- FORM INPUT PROSES PEMBAYARAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 p-3.5 sm:p-4 space-y-3">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="credit-card" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Rincian Pembayaran Kas / Bank</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
            
            <!-- Tanggal Bayar -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tanggal Bayar <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal_proses" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] cursor-pointer">
                </div>
            </div>

            <!-- Bank (Select2) -->
            <div class="relative select2-container-v2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Bank / Kas <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_bank" id="kode_bank_proses" class="select2KodebankProses w-full">
                        <option value="">Pilih Bank / Kas</option>
                        @foreach ($bank as $d)
                            <option value="{{ $d->kode_bank }}">
                                {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Akun (Select2) -->
            <div class="relative select2-container-v2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Akun Pembukuan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_akun" id="kode_akun_proses" class="select2KodeakunProses w-full">
                        <option value="">Pilih Akun</option>
                        <option value="2-1300">2-1300 - Hutang Lainnya</option>
                        <option value="2-1200">2-1200 - Hutang Dagang</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan Pembayaran (Mengisi Kolom Kanan agar Sempurna Penuh) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Keterangan Pembayaran <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="keterangan" id="keterangan_proses" placeholder="Keterangan pembayaran kontra bon"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                </div>
            </div>

            <!-- No. BKK (Khusus Kas Kecil BK071 - Full Width saat Aktif) -->
            <div id="kaskecilContainer" class="hidden sm:col-span-2">
                <div class="p-3 rounded-lg border border-amber-200/80 dark:border-amber-900/50 bg-amber-50/50 dark:bg-amber-950/20">
                    <label class="block text-xs font-bold text-amber-900 dark:text-amber-200 mb-1">
                        No. BKK (Kas Kecil BK071) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-amber-600 dark:text-amber-400 z-10">
                            <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="no_bkk" id="no_bkk_proses" placeholder="Masukkan Nomor BKK"
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-amber-300 dark:border-amber-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                    </div>
                </div>
            </div>

            <!-- Checkbox Dibayar Oleh Cabang -->
            <div class="sm:col-span-2 pt-1 border-t border-slate-200/80 dark:border-slate-800">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="dibayarcabang" id="dibayarcabang_proses" value="1"
                        class="w-4 h-4 rounded-sm border-slate-300 text-[#284c9a] focus:ring-[#284c9a] cursor-pointer">
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">Dibayar Oleh Cabang ?</span>
                </label>
            </div>

            <!-- Pilihan Cabang (Muncul jika checkbox aktif) -->
            <div id="cabangContainer" class="sm:col-span-2 hidden relative select2-container-v2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Cabang Pembayar <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_proses" class="select2KodecabangProses w-full">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>

    <!-- TOMBOL AKSI MODAL (RIGHT-ALIGNED) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelProsesModal"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitProses"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Proses Pembayaran</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formProseskontrabon");

        // Flatpickr
        form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Select2
        if ($.fn.select2) {
            form.find('.select2KodebankProses').select2({
                placeholder: 'Pilih Bank / Kas',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });

            form.find('.select2KodeakunProses').select2({
                placeholder: 'Pilih Akun',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });

            form.find('.select2KodecabangProses').select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        // Toggle Kas Kecil (BK071)
        function checkKasKecil() {
            const kode_bank = form.find("#kode_bank_proses").val();
            if (kode_bank === "BK071") {
                $("#kaskecilContainer").removeClass("hidden");
            } else {
                $("#kaskecilContainer").addClass("hidden");
                form.find("#no_bkk_proses").val("");
            }
        }

        form.find("#kode_bank_proses").on("change", checkKasKecil);
        checkKasKecil();

        // Toggle Dibayar Cabang
        form.find("#dibayarcabang_proses").on("change", function() {
            if (this.checked) {
                $("#cabangContainer").removeClass("hidden");
            } else {
                $("#cabangContainer").addClass("hidden");
                form.find("#kode_cabang_proses").val("").trigger("change");
            }
        });

        // Inline Validation Helpers
        function showInlineError($el, msg) {
            $el.addClass("border-rose-500 ring-1 ring-rose-500");
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $rel = $el.closest(".relative");
            if ($rel.length && !($rel[0].className && $rel[0].className.indexOf('col-span') !== -1)) {
                $rel.find("i").addClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            } else {
                $el.siblings(".field-error-msg").remove();
                $el.parent().find("> .field-error-msg").remove();
                $el.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $el.focus();
        }

        function clearInlineError($el) {
            $el.removeClass("border-rose-500 ring-1 ring-rose-500");
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find("i").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.parent().find(".field-error-msg").remove();
            }
            $el.siblings(".field-error-msg").remove();
            $el.parent().find("> .field-error-msg").remove();
            $el.parent().find(".field-error-msg").remove();
        }

        // Tombol Batal
        form.find("#btnCancelProsesModal").on("click", function() {
            $("#modalProses").addClass("hidden");
            $("body").removeClass("overflow-hidden");
        });

        // Submit Validation & Anti-Double Submit
        form.on("submit", function(e) {
            const tanggal = form.find("#tanggal_proses").val().trim();
            const kode_bank = form.find("#kode_bank_proses").val();
            const kode_akun = form.find("#kode_akun_proses").val();
            const no_bkk = form.find("#no_bkk_proses").val().trim();
            const keterangan = form.find("#keterangan_proses").val().trim();
            const isCabang = form.find("#dibayarcabang_proses").is(":checked");
            const kode_cabang = form.find("#kode_cabang_proses").val();

            let hasError = false;

            if (!tanggal) {
                showInlineError(form.find("#tanggal_proses"), "Tanggal bayar wajib diisi!");
                hasError = true;
            }

            if (!kode_bank) {
                showInlineError(form.find("#kode_bank_proses"), "Bank / Kas wajib dipilih!");
                hasError = true;
            }

            if (kode_bank === "BK071" && !no_bkk) {
                showInlineError(form.find("#no_bkk_proses"), "No. BKK wajib diisi untuk Kas Kecil!");
                hasError = true;
            }

            if (!kode_akun) {
                showInlineError(form.find("#kode_akun_proses"), "Akun pembukuan wajib dipilih!");
                hasError = true;
            }

            if (!keterangan) {
                showInlineError(form.find("#keterangan_proses"), "Keterangan pembayaran wajib diisi!");
                hasError = true;
            }

            if (isCabang && !kode_cabang) {
                showInlineError(form.find("#kode_cabang_proses"), "Cabang pembayar wajib dipilih!");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            // Anti double submit
            const $btn = $("#btnSubmitProses");
            $btn.prop("disabled", true).addClass("opacity-75 cursor-not-allowed pointer-events-none").html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `);
            return true;
        });

        // Live cleanup
        form.on("input change", "input, select", function() {
            clearInlineError($(this));
        });
        form.find("select").on("select2:select select2:clear", function(e) {
            clearInlineError($(this));
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
