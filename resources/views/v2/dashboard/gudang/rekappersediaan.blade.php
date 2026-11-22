<style>
    .inventory-v2-container {
        border-collapse: separate;
        border-spacing: 0;
    }
    .inventory-v2-container thead,
    .inventory-v2-container thead tr {
        background-color: #284c9a !important;
    }
    .inventory-v2-container thead th {
        position: sticky;
        top: 0;
        z-index: 20;
        background-color: #284c9a !important;
        box-shadow: none !important;
    }
    .inventory-v2-container thead tr:nth-child(2) th {
        top: 35px; /* Overlap with row 1 */
        background-color: #284c9a !important;
    }
    .inventory-v2-container th.freeze-col,
    .inventory-v2-container td.freeze-col {
        position: sticky;
        left: 0;
        z-index: 10;
    }
    .inventory-v2-container thead th.freeze-col {
        z-index: 30;
        top: 0;
        background-color: #284c9a !important;
    }
    .branch-v2-row.active .chevron-icon {
        transform: rotate(90deg);
    }
</style>

<div class="rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
    <div class="overflow-x-auto overflow-y-auto max-h-[580px] scrollbar-thin">
        <table class="w-full text-left border-collapse inventory-v2-container font-sans text-xs">
            <thead class="bg-[#284c9a]">
                <!-- Top Header Row -->
                <tr class="bg-[#284c9a]">
                    <th rowspan="2" class="freeze-col bg-[#284c9a] text-white font-bold text-[11px] uppercase tracking-wider px-3.5 py-2.5 min-w-[240px] max-w-[280px] border-r border-b border-white/20 shadow-[4px_0_8px_-2px_rgba(0,0,0,0.15)] align-middle">
                        <div class="flex items-center gap-2">
                            <i data-lucide="building-2" class="w-4 h-4 text-blue-200"></i>
                            <span>Unit / Cabang</span>
                        </div>
                    </th>
                    <th colspan="{{ count($products) }}" class="h-[34px] bg-[#284c9a] text-white font-bold text-[11px] uppercase tracking-wider px-3 text-center border-b border-white/20">
                        <div class="flex items-center justify-center gap-1.5">
                            <i data-lucide="package" class="w-3.5 h-3.5 text-blue-200"></i>
                            <span>Stok Utama Produk (Satuan Dus)</span>
                        </div>
                    </th>
                </tr>

                <!-- Product Codes Sub-Header -->
                <tr class="bg-[#284c9a]">
                    @foreach ($products as $product)
                        <th class="bg-[#284c9a] text-white/90 font-bold text-[11px] py-1.5 px-2.5 text-center min-w-[70px] border-r border-white/10 border-b border-white/20 tracking-tight">
                            {{ $product->kode_produk }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                
                {{-- PUSAT ROW --}}
                <tr class="bg-emerald-600 text-white font-bold">
                    <td class="freeze-col bg-emerald-600 text-white font-bold px-3.5 py-2.5 border-r border-emerald-700 shadow-[4px_0_8px_-2px_rgba(0,0,0,0.15)]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="warehouse" class="w-4 h-4 text-emerald-100"></i>
                            <span class="tracking-wide">PUSAT</span>
                        </div>
                    </td>
                    @foreach ($products as $product)
                        <td class="text-center font-mono-num font-bold text-xs px-2 py-2 border-r border-emerald-500/40">
                            {{ formatAngka($rekapgudang->{"saldoakhir_$product->kode_produk"}) }}
                        </td>
                    @endforeach
                </tr>

                {{-- BRANCH ROWS --}}
                @foreach ($rekappersediaancabang as $data)
                    <tr class="group branch-v2-row hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-colors duration-150" data-branch="{{ $data->kode_cabang }}">
                        <td class="freeze-col bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/50 px-3.5 py-2 border-r border-slate-200 dark:border-slate-800 shadow-[4px_0_8px_-2px_rgba(0,0,0,0.06)] transition-colors duration-150">
                            <div class="flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 group-hover:text-brand-600 chevron-icon transition-transform duration-200 shrink-0"></i>
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-xs tracking-tight uppercase">{{ textUpperCase($data->nama_cabang) }}</span>
                            </div>
                        </td>
                        @foreach ($products as $product)
                            @php
                                $saldo_akhir =
                                    ($data->{"saldo_$product->kode_produk"} +
                                        $data->{"mutasi_$product->kode_produk"} -
                                        $data->{"ambil_$product->kode_produk"} +
                                        $data->{"kembali_$product->kode_produk"}) /
                                    $product->isi_pcs_dus;

                                $saldo_akhir = $saldo_akhir < 0 ? 0 : $saldo_akhir;
                                $buffer = $data->{"buffer_$product->kode_produk"};
                                $max = $data->{"max_$product->kode_produk"};

                                // Status highlight classes
                                if ($saldo_akhir <= $buffer) {
                                    $cellClass = 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 font-black';
                                } elseif ($saldo_akhir >= $max) {
                                    $cellClass = 'bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-400 font-bold';
                                } else {
                                    $cellClass = 'text-slate-700 dark:text-slate-200 font-semibold';
                                }
                            @endphp
                            <td class="text-center font-mono-num text-xs px-2 py-2 border-r border-slate-100 dark:border-slate-800/60 {{ $cellClass }}">
                                {{ formatAngka(floor($saldo_akhir)) }}
                            </td>
                        @endforeach
                    </tr>

                    {{-- EXPANDABLE SUB-ROWS --}}
                    <!-- 1. Buffer Stock -->
                    <tr class="detail-v2-row detail-{{ $data->kode_cabang }} hidden bg-slate-50/70 dark:bg-slate-950/50 border-t border-slate-100 dark:border-slate-800/80">
                        <td class="freeze-col bg-slate-50 dark:bg-slate-950 pl-8 pr-3 py-1.5 border-r border-slate-200 dark:border-slate-800 shadow-[4px_0_8px_-2px_rgba(0,0,0,0.06)]">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Buffer Stock</span>
                        </td>
                        @foreach ($products as $product)
                            <td class="text-center font-mono-num text-xs text-slate-500 dark:text-slate-400 py-1.5 px-2 border-r border-slate-100 dark:border-slate-800/40">
                                {{ formatAngka($data->{"buffer_$product->kode_produk"}) }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- 2. Max Stock -->
                    <tr class="detail-v2-row detail-{{ $data->kode_cabang }} hidden bg-slate-50/70 dark:bg-slate-950/50">
                        <td class="freeze-col bg-slate-50 dark:bg-slate-950 pl-8 pr-3 py-1.5 border-r border-slate-200 dark:border-slate-800 shadow-[4px_0_8px_-2px_rgba(0,0,0,0.06)]">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Max. Stock</span>
                        </td>
                        @foreach ($products as $product)
                            <td class="text-center font-mono-num text-xs text-slate-500 dark:text-slate-400 py-1.5 px-2 border-r border-slate-100 dark:border-slate-800/40">
                                {{ formatAngka($data->{"max_$product->kode_produk"}) }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- 3. Sell Out (Bulan Ini) -->
                    <tr class="detail-v2-row detail-{{ $data->kode_cabang }} hidden bg-blue-50/30 dark:bg-blue-950/20 border-b border-slate-200/60 dark:border-slate-800">
                        <td class="freeze-col bg-blue-50/50 dark:bg-blue-950/40 pl-8 pr-3 py-1.5 border-r border-slate-200 dark:border-slate-800 shadow-[4px_0_8px_-2px_rgba(0,0,0,0.06)]">
                            <span class="text-[10px] font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider">Sell Out (Bln Ini)</span>
                        </td>
                        @foreach ($products as $product)
                            <td class="text-center font-mono-num text-xs font-bold text-brand-700 dark:text-brand-300 py-1.5 px-2 border-r border-slate-100 dark:border-slate-800/40">
                                {{ formatAngka(floor($data->{"penjualan_$product->kode_produk"} / $product->isi_pcs_dus)) }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    (function() {
        if (window.lucide) {
            lucide.createIcons();
        }

        $(".branch-v2-row").off('click').on('click', function() {
            const branchId = $(this).data('branch');
            const details = $(`.detail-${branchId}`);
            
            $(this).toggleClass('active');
            details.toggleClass('hidden');
        });
    })();
</script>
