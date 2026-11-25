<form action="{{ route('setoranpenjualan.update', Crypt::encrypt($setoranpenjualan->kode_setoran)) }}" method="POST" id="formUpdatesetoran" class="space-y-3.5" autocomplete="off">
    @csrf
    @method('PUT')
    <input type="hidden" name="lhp_tunai" id="lhp_tunai_edit">
    <input type="hidden" name="lhp_tagihan" id="lhp_tagihan_edit">
    <input type="hidden" name="lhp_total" id="lhp_total_edit">
    <input type="hidden" name="setoran_giro" id="setoran_giro_edit">
    <input type="hidden" name="setoran_transfer" id="setoran_transfer_edit">
    <input type="hidden" name="giro_to_cash" id="giro_to_cash_edit">
    <input type="hidden" name="giro_to_transfer" id="giro_to_transfer_edit">

    <input type="hidden" id="tanggal_setoran_edit" value="{{ $setoranpenjualan->tanggal }}">
    <input type="hidden" id="kode_cabang_setoran_edit" value="{{ $setoranpenjualan->kode_cabang }}">
    <input type="hidden" id="kode_salesman_setoran_edit" value="{{ $setoranpenjualan->kode_salesman }}">

    <!-- Summary Box Transaksi Terpilih -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-200/80 dark:border-slate-800">
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Setoran Penjualan: {{ $setoranpenjualan->kode_setoran }}</span>
            <span class="text-xs font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ DateToIndo($setoranpenjualan->tanggal) }}</span>
        </div>
        <div class="grid grid-cols-2 gap-2 text-xs">
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Cabang</span>
                <span class="font-bold text-slate-800 dark:text-slate-200">{{ textUpperCase($setoranpenjualan->nama_cabang) }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Salesman</span>
                <span class="font-bold text-slate-800 dark:text-slate-200">{{ textUpperCase($setoranpenjualan->nama_salesman) }}</span>
            </div>
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
                <span id="lhp_tunai_edit_text" class="text-xs font-bold font-mono-num text-emerald-600 dark:text-emerald-400 mt-0.5 block">0</span>
            </div>
            <div class="p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Tagihan</span>
                <span id="lhp_tagihan_edit_text" class="text-xs font-bold font-mono-num text-emerald-600 dark:text-emerald-400 mt-0.5 block">0</span>
            </div>
            <div class="p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Total LHP</span>
                <span id="lhp_total_edit_text" class="text-xs font-black font-mono-num text-[#284c9a] dark:text-blue-400 mt-0.5 block">0</span>
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
                <label for="setoran_kertas_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Setoran Kertas (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="banknote" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="setoran_kertas_edit" name="setoran_kertas" value="{{ formatAngka($setoranpenjualan->setoran_kertas) }}"
                        class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>

            <div class="space-y-1">
                <label for="setoran_logam_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Setoran Logam (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="coins" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="setoran_logam_edit" name="setoran_logam" value="{{ formatAngka($setoranpenjualan->setoran_logam) }}"
                        class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>

            <div class="space-y-1">
                <label for="setoran_lainnya_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Setoran Lainnya (Rp)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="setoran_lainnya_edit" name="setoran_lainnya" value="{{ formatAngka($setoranpenjualan->setoran_lainnya) }}"
                        class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>
            </div>
        </div>

        <!-- Rekap Total Setoran & Selisih -->
        <div class="rounded-lg bg-slate-50 dark:bg-slate-800/60 p-3 space-y-1.5 text-xs font-semibold">
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Setoran Giro</span>
                <span id="setoran_giro_edit_text" class="font-mono-num text-slate-800 dark:text-slate-200">0</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Setoran Transfer</span>
                <span id="setoran_transfer_edit_text" class="font-mono-num text-slate-800 dark:text-slate-200">0</span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60 dark:border-slate-700 font-bold">
                <span class="text-slate-700 dark:text-slate-300">Total Setoran</span>
                <span id="setoran_total_edit_text" class="font-mono-num text-sm text-[#284c9a] dark:text-blue-400">0</span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60 dark:border-slate-700 font-bold">
                <span class="text-slate-700 dark:text-slate-300">Selisih (Setoran - LHP)</span>
                <span id="selisih_edit_text" class="font-mono-num text-sm text-slate-900 dark:text-white">0</span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-dashed border-slate-200/60 dark:border-slate-700 text-[11px] text-amber-600 dark:text-amber-400">
                <span>Ganti Giro ke Cash</span>
                <span id="giro_to_cash_edit_text" class="font-mono-num">0</span>
            </div>
            <div class="flex items-center justify-between text-[11px] text-amber-600 dark:text-amber-400">
                <span>Ganti Giro ke Transfer</span>
                <span id="giro_to_transfer_edit_text" class="font-mono-num">0</span>
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="space-y-1">
        <label for="keterangan_setoran_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan
        </label>
        <div class="relative">
            <textarea id="keterangan_setoran_edit" name="keterangan" rows="2" placeholder="Keterangan tambahan setoran (opsional)"
                class="w-full p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">{{ $setoranpenjualan->keterangan }}</textarea>
        </div>
    </div>

    <!-- Action Buttons (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelEditSetoran"
            class="h-[34px] px-4 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnUpdateSetoran"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Perbarui Setoran</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formUpdatesetoran");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".money-mask").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number || 0);
        }

        function parseMoney(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        function getlhp() {
            const tanggal = $form.find("#tanggal_setoran_edit").val();
            const kode_cabang = $form.find("#kode_cabang_setoran_edit").val();
            const kode_salesman = $form.find("#kode_salesman_setoran_edit").val();

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
                        $form.find("#lhp_tunai_edit_text").text(formatRupiah(response.data.lhp_tunai));
                        $form.find("#lhp_tunai_edit").val(response.data.lhp_tunai);
                        $form.find("#lhp_tagihan_edit_text").text(formatRupiah(response.data.lhp_tagihan));
                        $form.find("#lhp_tagihan_edit").val(response.data.lhp_tagihan);
                        $form.find("#lhp_total_edit_text").text(formatRupiah(total_lhp));
                        $form.find("#lhp_total_edit").val(total_lhp);
                        $form.find("#setoran_giro_edit_text").text(formatRupiah(response.data.setoran_giro));
                        $form.find("#setoran_giro_edit").val(response.data.setoran_giro);
                        $form.find("#setoran_transfer_edit_text").text(formatRupiah(response.data.setoran_transfer));
                        $form.find("#setoran_transfer_edit").val(response.data.setoran_transfer);
                        $form.find("#giro_to_cash_edit_text").text(formatRupiah(response.data.giro_to_cash));
                        $form.find("#giro_to_cash_edit").val(response.data.giro_to_cash);
                        $form.find("#giro_to_transfer_edit_text").text(formatRupiah(response.data.giro_to_transfer));
                        $form.find("#giro_to_transfer_edit").val(response.data.giro_to_transfer);
                        loadtotalsetoran();
                    }
                });
            }
        }

        function loadtotalsetoran() {
            const sk = parseMoney($form.find("#setoran_kertas_edit").val());
            const sg = parseMoney($form.find("#setoran_logam_edit").val());
            const sl = parseMoney($form.find("#setoran_lainnya_edit").val());
            const st = parseMoney($form.find("#setoran_transfer_edit").val());
            const sb = parseMoney($form.find("#setoran_giro_edit").val());
            const total_lhp = parseMoney($form.find("#lhp_total_edit").val());

            const total_setoran = sk + sg + sl + st + sb;
            const selisih = total_setoran - total_lhp;

            $form.find("#setoran_total_edit_text").text(formatRupiah(total_setoran));
            const $selisih = $form.find("#selisih_edit_text");
            $selisih.text(formatRupiah(selisih));
            if (selisih < 0) {
                $selisih.removeClass('text-emerald-600').addClass('text-rose-600');
            } else {
                $selisih.removeClass('text-rose-600').addClass('text-emerald-600');
            }
        }

        getlhp();

        $form.find("#setoran_kertas_edit, #setoran_logam_edit, #setoran_lainnya_edit").on('input keyup change', function() {
            loadtotalsetoran();
        });

        $form.find("#btnCancelEditSetoran").on('click', function() {
            if (typeof modalEditSetoran !== 'undefined') {
                modalEditSetoran.close();
            } else {
                $("#modalEditSetoran").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            const $btn = $form.find("#btnUpdateSetoran");
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
