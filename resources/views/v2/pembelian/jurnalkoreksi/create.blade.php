<form action="{{ route('jurnalkoreksi.store') }}" id="formJurnalkoreksi" method="POST" class="space-y-2.5">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        
        <!-- Tanggal -->
        <div>
            <label for="tanggal_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Koreksi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_jk" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>

        <!-- Supplier -->
        <div>
            <label for="kode_supplier_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Supplier <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_supplier" id="kode_supplier_jk" class="select2KodesupplierJK w-full">
                    <option value="">Pilih Supplier</option>
                    @foreach ($supplier as $d)
                        <option value="{{ $d->kode_supplier }}">{{ textUpperCase($d->nama_supplier) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        
        <!-- No. Bukti Pembelian -->
        <div>
            <label for="no_bukti_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Bukti Pembelian <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                </div>
                <select name="no_bukti" id="no_bukti_jk" class="select2NobuktiJK w-full">
                    <option value="">Pilih No. Bukti Pembelian</option>
                </select>
            </div>
        </div>

        <!-- Kode Barang -->
        <div>
            <label for="kode_barang_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Barang Pembelian <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="package" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_barang" id="kode_barang_jk" class="select2KodebarangJK w-full">
                    <option value="">Pilih Barang</option>
                </select>
            </div>
        </div>

    </div>

    <!-- Keterangan -->
    <div>
        <label for="keterangan_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan Koreksi <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="keterangan" id="keterangan_jk" placeholder="Masukkan alasan atau rincian koreksi"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>
    </div>

    <!-- Qty, Harga, Total -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-start">
        
        <!-- Qty -->
        <div>
            <label for="jumlah_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Qty <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="jumlah" id="jumlah_jk" placeholder="0"
                    class="number-separator text-right w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

        <!-- Harga -->
        <div>
            <label for="harga_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Harga Satuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="harga" id="harga_jk" placeholder="0"
                    class="number-separator text-right w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

        <!-- Total -->
        <div>
            <label for="total_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Total Penyesuaian
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calculator" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="total" id="total_jk" placeholder="0" readonly
                    class="text-right w-full h-[36px] pl-8 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-[#284c9a] dark:text-blue-400 placeholder:text-slate-400 focus:outline-hidden">
            </div>
        </div>

    </div>

    <!-- Akun Debet & Akun Kredit -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start pt-1">
        
        <!-- Akun Debet -->
        <div>
            <label for="kode_akun_debet_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Akun Debet <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_akun_debet" id="kode_akun_debet_jk" class="select2KodeakundebetJK w-full">
                    <option value="">Pilih Akun Debet</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Akun Kredit -->
        <div>
            <label for="kode_akun_kredit_jk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Akun Kredit <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_akun_kredit" id="kode_akun_kredit_jk" class="select2KodeakunkreditJK w-full">
                    <option value="">Pilih Akun Kredit</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <!-- Modal Footer Actions -->
    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanJK"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Jurnal Koreksi</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const form = $("#formJurnalkoreksi");

        if (typeof flatpickr !== 'undefined') {
            form.find(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: new Date()
            });
        }

        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ',',
            });
        }

        // Initialize Select2 dropdowns without modal dropdownParent for smooth floating overlay
        form.find('.select2KodesupplierJK').select2({ placeholder: 'Pilih Supplier', allowClear: true, width: '100%' });
        form.find('.select2NobuktiJK').select2({ placeholder: 'Pilih No. Bukti Pembelian', allowClear: true, width: '100%' });
        form.find('.select2KodebarangJK').select2({ placeholder: 'Pilih Barang', allowClear: true, width: '100%' });
        form.find('.select2KodeakundebetJK').select2({ placeholder: 'Pilih Akun Debet', allowClear: true, width: '100%' });
        form.find('.select2KodeakunkreditJK').select2({ placeholder: 'Pilih Akun Kredit', allowClear: true, width: '100%' });

        form.find("#kode_supplier_jk").change(function() {
            clearInlineError($(this));
            const kode_supplier = $(this).val();
            form.find("#no_bukti_jk").html("<option value=''>Memuat...</option>").trigger("change");
            form.find("#kode_barang_jk").html("<option value=''>Pilih Barang</option>").trigger("change");
            
            if (kode_supplier) {
                $.get(`/pembelian/${kode_supplier}/getpembelianbysupplier`, function(data) {
                    form.find("#no_bukti_jk").html(data).trigger("change");
                });
            } else {
                form.find("#no_bukti_jk").html("<option value=''>Pilih No. Bukti Pembelian</option>").trigger("change");
            }
        });

        form.find("#no_bukti_jk").change(function() {
            clearInlineError($(this));
            const no_bukti = $(this).val();
            if (no_bukti) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('pembelian.getbarangpembelian') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_bukti: no_bukti
                    },
                    cache: false,
                    success: function(respond) {
                        form.find("#kode_barang_jk").html(respond).trigger("change");
                    }
                });
            } else {
                form.find("#kode_barang_jk").html("<option value=''>Pilih Barang</option>").trigger("change");
            }
        });

        function convertNumber(number) {
            if (!number) return 0;
            let formatted = number.toString().replace(/\./g, '');
            formatted = formatted.replace(/,/g, '.');
            return parseFloat(formatted) || 0;
        }

        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function calculateTotal() {
            let qty = convertNumber(form.find("#jumlah_jk").val());
            let harga = convertNumber(form.find("#harga_jk").val());
            let total = qty * harga;
            form.find("#total_jk").val(numberFormat(total, '2', ',', '.'));
        }

        form.find("#jumlah_jk, #harga_jk").on('input change keyup', function() {
            calculateTotal();
            clearInlineError($(this));
        });

        function showInlineError($el, msg) {
            clearInlineError($el);
            $el.addClass("border-rose-500 ring-1 ring-rose-500");
            
            const $rel = $el.closest(".relative");
            if ($rel.length) {
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
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find("i").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
            }
            $el.siblings(".field-error-msg").remove();
            $el.parent().find("> .field-error-msg").remove();
        }

        function buttonDisable() {
            form.find("#btnSimpanJK").prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            form.find("#btnSimpanJK").html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        }

        form.submit(function(e) {
            const tanggal = form.find("#tanggal_jk").val().trim();
            const kode_supplier = form.find("#kode_supplier_jk").val();
            const no_bukti = form.find("#no_bukti_jk").val();
            const kode_barang = form.find("#kode_barang_jk").val();
            const keterangan = form.find("#keterangan_jk").val().trim();
            const jumlah = form.find("#jumlah_jk").val();
            const harga = form.find("#harga_jk").val();
            const kode_akun_debet = form.find("#kode_akun_debet_jk").val();
            const kode_akun_kredit = form.find("#kode_akun_kredit_jk").val();

            let hasError = false;

            if (!tanggal) {
                showInlineError(form.find("#tanggal_jk"), "Tanggal koreksi wajib diisi!");
                hasError = true;
            }
            if (!kode_supplier) {
                showInlineError(form.find("#kode_supplier_jk"), "Supplier wajib dipilih!");
                hasError = true;
            }
            if (!no_bukti) {
                showInlineError(form.find("#no_bukti_jk"), "No. Bukti pembelian wajib dipilih!");
                hasError = true;
            }
            if (!kode_barang) {
                showInlineError(form.find("#kode_barang_jk"), "Barang wajib dipilih!");
                hasError = true;
            }
            if (!keterangan) {
                showInlineError(form.find("#keterangan_jk"), "Keterangan wajib diisi!");
                hasError = true;
            }
            if (!jumlah || convertNumber(jumlah) === 0) {
                showInlineError(form.find("#jumlah_jk"), "Qty koreksi wajib diisi!");
                hasError = true;
            }
            if (!harga || convertNumber(harga) === 0) {
                showInlineError(form.find("#harga_jk"), "Harga satuan wajib diisi!");
                hasError = true;
            }
            if (!kode_akun_debet) {
                showInlineError(form.find("#kode_akun_debet_jk"), "Akun debet wajib dipilih!");
                hasError = true;
            }
            if (!kode_akun_kredit) {
                showInlineError(form.find("#kode_akun_kredit_jk"), "Akun kredit wajib dipilih!");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            buttonDisable();
        });

        // Live cleanup on input & select
        form.on("input change", "input, select", function() {
            clearInlineError($(this));
        });

        form.find("select").on("select2:select select2:clear", function(e) {
            clearInlineError($(e.target));
        });
    });
</script>
