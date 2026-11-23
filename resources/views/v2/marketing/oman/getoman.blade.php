@if ($detail->isEmpty())
    <tr>
        <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500">
            <div class="flex flex-col items-center justify-center gap-1.5">
                <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                <span class="text-xs font-medium">Data rincian produk OMAN tidak ditemukan</span>
            </div>
        </td>
    </tr>
@else
    @foreach ($detail as $d)
        @php
            $subtotal = (float)$d->total_oman - (float)$d->saldo_akhir_gudang;
        @endphp
        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition border-b border-slate-100 dark:border-slate-800/80">
            <!-- 1. Kode Produk -->
            <td class="py-1.5 px-3 text-center">
                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400 text-xs">{{ $d->kode_produk }}</span>
                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
            </td>

            <!-- 2. Nama Produk -->
            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100 text-xs">
                {{ $d->nama_produk }}
            </td>

            <!-- 3. Total OMAN -->
            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-700 dark:text-slate-200 text-xs">
                <input type="hidden" id="oman_marketing" class="oman_marketing" name="oman_marketing[]" value="{{ $d->total_oman }}">
                {{ formatAngka($d->total_oman) }}
            </td>

            <!-- 4. Stok Gudang -->
            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-700 dark:text-slate-200 text-xs">
                <input type="hidden" id="stok_gudang" class="stok_gudang" name="stok_gudang[]" value="{{ $d->saldo_akhir_gudang }}">
                {{ formatAngka($d->saldo_akhir_gudang) }}
            </td>

            <!-- 5. Buffer Stok (Input) -->
            <td class="py-1.5 px-3 text-right w-36">
                <input type="text" id="buffer_stok" name="buffer_stok[]" placeholder="0"
                    class="form-table buffer_stok money w-full h-[30px] px-2 text-right font-mono-num font-bold text-xs rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition">
            </td>

            <!-- 6. Subtotal (Calculated) -->
            <td class="py-1.5 px-3 text-right w-36">
                <input type="text" id="subtotal" name="subtotal[]" placeholder="0" value="{{ formatAngka($subtotal) }}"
                    class="form-table subtotal w-full h-[30px] px-2 text-right font-mono-num font-bold text-xs rounded-md border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 focus:outline-hidden" readonly>
            </td>
        </tr>
    @endforeach
@endif

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        function formatNumberDot(number) {
            if (isNaN(number) || number === null) return "0";
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function parseCleanInt(val) {
            if (!val) return 0;
            var clean = val.toString().replace(/\./g, '').replace(/,/g, '');
            var parsed = parseInt(clean, 10);
            return isNaN(parsed) ? 0 : parsed;
        }

        if (typeof $.fn.maskMoney !== 'undefined') {
            $(".buffer_stok.money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        $("#mytable tbody tr, #tableOman tbody tr").each(function() {
            var $row = $(this);
            $row.find('.buffer_stok, .oman_marketing, .stok_gudang').on('input change keyup', function() {
                var omVal = $row.find('.oman_marketing').val();
                var sgVal = $row.find('.stok_gudang').val();
                var bsVal = $row.find('.buffer_stok').val();

                var om = parseCleanInt(omVal);
                var sg = parseCleanInt(sgVal);
                var bs = parseCleanInt(bsVal);

                var subTotal = om - sg + bs;
                $row.find('.subtotal').val(formatNumberDot(subTotal));
            });
        });
    });
</script>
