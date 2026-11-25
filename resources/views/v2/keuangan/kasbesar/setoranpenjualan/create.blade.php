<form action="{{ route('setoranpenjualan.store') }}" method="POST" id="formCreatesetoran" class="space-y-3.5" autocomplete="off">
    @csrf
    <input type="hidden" name="lhp_tunai" id="lhp_tunai">
    <input type="hidden" name="lhp_tagihan" id="lhp_tagihan">
    <input type="hidden" name="lhp_total" id="lhp_total">
    <input type="hidden" name="setoran_giro" id="setoran_giro">
    <input type="hidden" name="setoran_transfer" id="setoran_transfer">
    <input type="hidden" name="giro_to_cash" id="giro_to_cash">
    <input type="hidden" name="giro_to_transfer" id="giro_to_transfer">

    <!-- Row 1: Tanggal LHP -->
    <div class="space-y-1">
        <label for="tanggal_setoran" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal LHP <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_setoran" name="tanggal" readonly placeholder="Pilih Tanggal LHP"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-tanggal-setoran" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Tanggal LHP wajib diisi</span>
        </p>
    </div>

    <!-- Row 2: Cabang (Jika multi-cabang) & Salesman -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        @hasanyrole($roles_show_cabang)
            <div class="space-y-1">
                <label for="kode_cabang_setoran" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Cabang <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_setoran" class="select2Kodecabang w-full">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
                <p id="error-cabang-setoran" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Cabang wajib dipilih</span>
                </p>
            </div>
        @endhasanyrole

        <div class="space-y-1">
            <label for="kode_salesman_setoran" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Salesman <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <select name="kode_salesman" id="kode_salesman_setoran" class="select2Kodesalesman w-full">
                    <option value="">Pilih Salesman</option>
                </select>
            </div>
            <p id="error-salesman-setoran" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Salesman wajib dipilih</span>
            </p>
        </div>
    </div>

    <!-- Ringkasan LHP Terkalkulasi -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-200">Ringkasan Nilai LHP Salesman</h6>
        </div>

        <div class="grid grid-cols-3 gap-2 text-xs">
            <div class="p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Tunai</span>
                <span id="lhp_tunai_text" class="text-xs font-bold font-mono-num text-emerald-600 dark:text-emerald-400 mt-0.5 block">0</span>
            </div>
            <div class="p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Tagihan</span>
                <span id="lhp_tagihan_text" class="text-xs font-bold font-mono-num text-emerald-600 dark:text-emerald-400 mt-0.5 block">0</span>
            </div>
            <div class="p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Total LHP</span>
                <span id="lhp_total_text" class="text-xs font-black font-mono-num text-[#284c9a] dark:text-blue-400 mt-0.5 block">0</span>
            </div>
        </div>
    </div>

    <!-- Rincian Setoran Masuk -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-3.5 space-y-3">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
            <i data-lucide="banknote" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-200">Rincian Fisik Setoran</h6>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
            <div class="space-y-1">
                <label for="setoran_kertas" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Setoran Kertas (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="banknote" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="setoran_kertas" name="setoran_kertas" placeholder="0"
                        class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>

            <div class="space-y-1">
                <label for="setoran_logam" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Setoran Logam (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="coins" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="setoran_logam" name="setoran_logam" placeholder="0"
                        class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>

            <div class="space-y-1">
                <label for="setoran_lainnya" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Setoran Lainnya (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="setoran_lainnya" name="setoran_lainnya" placeholder="0"
                        class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>
        </div>

        <!-- Rekap Total Setoran & Selisih -->
        <div class="rounded-lg bg-slate-50 dark:bg-slate-800/60 p-3 space-y-1.5 text-xs font-semibold">
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Setoran Giro</span>
                <span id="setoran_giro_text" class="font-mono-num text-slate-800 dark:text-slate-200">0</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Setoran Transfer</span>
                <span id="setoran_transfer_text" class="font-mono-num text-slate-800 dark:text-slate-200">0</span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60 dark:border-slate-700 font-bold">
                <span class="text-slate-700 dark:text-slate-300">Total Setoran</span>
                <span id="setoran_total_text" class="font-mono-num text-sm text-[#284c9a] dark:text-blue-400">0</span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60 dark:border-slate-700 font-bold">
                <span class="text-slate-700 dark:text-slate-300">Selisih (Setoran - LHP)</span>
                <span id="selisih_text" class="font-mono-num text-sm text-slate-900 dark:text-white">0</span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-dashed border-slate-200/60 dark:border-slate-700 text-[11px] text-amber-600 dark:text-amber-400">
                <span>Ganti Giro ke Cash</span>
                <span id="giro_to_cash_text" class="font-mono-num">0</span>
            </div>
            <div class="flex items-center justify-between text-[11px] text-amber-600 dark:text-amber-400">
                <span>Ganti Giro ke Transfer</span>
                <span id="giro_to_transfer_text" class="font-mono-num">0</span>
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="space-y-1">
        <label for="keterangan_setoran" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan
        </label>
        <div class="relative">
            <textarea id="keterangan_setoran" name="keterangan" rows="2" placeholder="Keterangan tambahan setoran (opsional)"
                class="w-full p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition"></textarea>
        </div>
    </div>

    <!-- Action Buttons (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelSetoran"
            class="h-[34px] px-4 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanSetoran"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Setoran</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formCreatesetoran");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $form.find(".money-mask").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });

        $form.find('.select2Kodecabang').select2({
            placeholder: 'Pilih Cabang',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        $form.find('.select2Kodesalesman').select2({
            placeholder: 'Pilih Salesman',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number || 0);
        }

        function parseMoney(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        function getsalesmanbyCabang() {
            var kode_cabang = $form.find("#kode_cabang_setoran").val();
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_salesman_setoran").html(respond);
                }
            });
        }

        $form.find("#kode_cabang_setoran").on('change', function() {
            getsalesmanbyCabang();
        });

        getsalesmanbyCabang();

        function getlhp() {
            const tanggal = $form.find("#tanggal_setoran").val();
            const kode_cabang = $form.find("#kode_cabang_setoran").val();
            const kode_salesman = $form.find("#kode_salesman_setoran").val();

            if (tanggal && kode_salesman) {
                $.ajax({
                    type: 'POST',
                    url: '/setoranpenjualan/getlhp',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal,
                        kode_cabang: kode_cabang,
                        kode_salesman: kode_salesman
                    },
                    cache: false,
                    success: function(response) {
                        let total_lhp = parseInt(response.data.lhp_tunai) + parseInt(response.data.lhp_tagihan);
                        $form.find("#lhp_tunai_text").text(formatRupiah(response.data.lhp_tunai));
                        $form.find("#lhp_tunai").val(response.data.lhp_tunai);
                        $form.find("#lhp_tagihan_text").text(formatRupiah(response.data.lhp_tagihan));
                        $form.find("#lhp_tagihan").val(response.data.lhp_tagihan);
                        $form.find("#lhp_total_text").text(formatRupiah(total_lhp));
                        $form.find("#lhp_total").val(total_lhp);
                        $form.find("#setoran_giro_text").text(formatRupiah(response.data.setoran_giro));
                        $form.find("#setoran_giro").val(response.data.setoran_giro);
                        $form.find("#setoran_transfer_text").text(formatRupiah(response.data.setoran_transfer));
                        $form.find("#setoran_transfer").val(response.data.setoran_transfer);
                        $form.find("#giro_to_cash_text").text(formatRupiah(response.data.giro_to_cash));
                        $form.find("#giro_to_cash").val(response.data.giro_to_cash);
                        $form.find("#giro_to_transfer_text").text(formatRupiah(response.data.giro_to_transfer));
                        $form.find("#giro_to_transfer").val(response.data.giro_to_transfer);
                        loadtotalsetoran();
                    }
                });
            }
        }

        function loadtotalsetoran() {
            const sk = parseMoney($form.find("#setoran_kertas").val());
            const sg = parseMoney($form.find("#setoran_logam").val());
            const sl = parseMoney($form.find("#setoran_lainnya").val());
            const st = parseMoney($form.find("#setoran_transfer").val());
            const sb = parseMoney($form.find("#setoran_giro").val());
            const total_lhp = parseMoney($form.find("#lhp_total").val());

            const total_setoran = sk + sg + sl + st + sb;
            const selisih = total_setoran - total_lhp;

            $form.find("#setoran_total_text").text(formatRupiah(total_setoran));
            const $selisih = $form.find("#selisih_text");
            $selisih.text(formatRupiah(selisih));
            if (selisih < 0) {
                $selisih.removeClass('text-emerald-600').addClass('text-rose-600');
            } else {
                $selisih.removeClass('text-rose-600').addClass('text-emerald-600');
            }
        }

        $form.find("#tanggal_setoran, #kode_cabang_setoran, #kode_salesman_setoran").on('change', function() {
            getlhp();
        });

        $form.find("#setoran_kertas, #setoran_logam, #setoran_lainnya").on('input keyup change', function() {
            loadtotalsetoran();
        });

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#tanggal_setoran").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-setoran"));
        });
        $form.find("#kode_cabang_setoran").on('change', function() {
            clearError($form.find('.select2-container-v2'), $form.find("#error-cabang-setoran"));
        });
        $form.find("#kode_salesman_setoran").on('change', function() {
            clearError($form.find("#kode_salesman_setoran").closest('.select2-container-v2'), $form.find("#error-salesman-setoran"));
        });

        $form.find("#btnCancelSetoran").on('click', function() {
            if (typeof modalCreateSetoran !== 'undefined') {
                modalCreateSetoran.close();
            } else {
                $("#modalCreateSetoran").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_setoran").val();
            const kode_salesman = $form.find("#kode_salesman_setoran").val();

            if (!tanggal) {
                showError($form.find("#tanggal_setoran"), $form.find("#error-tanggal-setoran"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_setoran");
            }

            if (!kode_salesman) {
                showError($form.find("#kode_salesman_setoran").closest('.select2-container-v2'), $form.find("#error-salesman-setoran"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_salesman_setoran");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanSetoran");
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
