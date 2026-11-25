<div class="space-y-4">
    <!-- Modern Tabs Navigation -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <ul class="flex space-x-2 -mb-px nav-tabs" role="tablist">
            <li role="presentation">
                <button type="button" class="nav-link active inline-flex items-center gap-2 py-2.5 px-4 font-bold text-xs border-b-2 border-[#284c9a] text-[#284c9a] dark:text-blue-400 bg-transparent transition-colors cursor-pointer" data-bs-toggle="tab" data-bs-target="#omset" aria-selected="true">
                    <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                    <span>Berdasarkan Omset</span>
                </button>
            </li>
            <li role="presentation">
                <button type="button" class="nav-link inline-flex items-center gap-2 py-2.5 px-4 font-bold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 bg-transparent transition-colors cursor-pointer" data-bs-toggle="tab" data-bs-target="#sellingout" aria-selected="false">
                    <i data-lucide="package-check" class="w-3.5 h-3.5"></i>
                    <span>Selling Out</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content w-full">
        <!-- 1. TAB: BERDASARKAN OMSET -->
        <div class="tab-pane active" id="omset" role="tabpanel">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xs bg-white dark:bg-slate-900">
                <div class="overflow-x-auto max-h-[540px] scrollbar-thin">
                    <table class="w-full text-left border-collapse text-xs font-sans whitespace-nowrap">
                        <thead>
                            <tr class="bg-[#284c9a] text-white">
                                <th rowspan="2" class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] border-b border-r border-white/15">Kode</th>
                                <th rowspan="2" class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] border-b border-r border-white/15">Nama Produk</th>
                                <th colspan="5" class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center border-b border-r border-white/15">Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                                <th colspan="5" class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center border-b border-white/15">Sampai Dengan Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                            </tr>
                            <tr class="bg-[#1e3a78] text-white/90">
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Real {{ $lastyear }}</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Target</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Real {{ $tahun }}</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-center border-b border-r border-white/10">Ach%</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-center border-b border-r border-white/15">Grw%</th>

                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Real {{ $lastyear }}</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Target</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Real {{ $tahun }}</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-center border-b border-r border-white/10">Ach%</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-center border-b border-white/10">Grw%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                            @foreach ($dppp as $d)
                                @php
                                    $target = $d->target;
                                    $realisasi_penjualan_lastyear = $d->realisasi_penjualan_lastyear / $d->isi_pcs_dus;
                                    $realisasi_penjualan = $d->realisasi_penjualan / $d->isi_pcs_dus;
                                    $grw = !empty($realisasi_penjualan_lastyear)
                                        ? (($realisasi_penjualan - $realisasi_penjualan_lastyear) / $realisasi_penjualan_lastyear) * 100
                                        : 0;
                                    $ach = !empty($target) ? ($realisasi_penjualan / $target) * 100 : 0;

                                    $target_sampaibulanini = $d->target_sampaibulanini;
                                    $realisasi_penjualan_lastyear_sampaibulanini = $d->realisasi_penjualan_lastyear_sampaibulanini / $d->isi_pcs_dus;
                                    $realisasi_penjualan_sampaibulanini = $d->realisasi_penjualan_sampaibulanini / $d->isi_pcs_dus;
                                    $grw_sampaibulanini = !empty($realisasi_penjualan_lastyear_sampaibulanini)
                                        ? (($realisasi_penjualan_sampaibulanini - $realisasi_penjualan_lastyear_sampaibulanini) /
                                                $realisasi_penjualan_lastyear_sampaibulanini) * 100
                                        : 0;
                                    $ach_sampaibulanini = !empty($target_sampaibulanini)
                                        ? ($realisasi_penjualan_sampaibulanini / $target_sampaibulanini) * 100
                                        : 0;

                                    $colorgrw = $grw < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400';
                                    $colorgrw_sampaibulanini = $grw_sampaibulanini < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400';
                                    $colorach = $ach < 100 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400';
                                    $colorach_sampaibulanini = $ach_sampaibulanini < 100 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400';
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-1.5 px-3 font-mono-num font-bold text-slate-700 dark:text-slate-200">{{ $d->kode_produk }}</td>
                                    <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">{{ $d->nama_produk }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-300">{{ formatAngkaDesimal($realisasi_penjualan_lastyear) }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-300">{{ formatAngkaDesimal($target) }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ formatAngkaDesimal($realisasi_penjualan) }}</td>
                                    <td class="py-1.5 px-2.5 text-center font-mono-num font-bold {{ $colorach }}">{{ formatAngkaDesimal($ach) }}</td>
                                    <td class="py-1.5 px-2.5 text-center font-mono-num font-bold {{ $colorgrw }}">
                                        <span class="inline-flex items-center gap-0.5">
                                            @if (!empty($grw))
                                                <i data-lucide="{{ $grw < 0 ? 'trending-down' : 'trending-up' }}" class="w-3 h-3"></i>
                                            @endif
                                            {{ formatAngkaDesimal($grw) }}
                                        </span>
                                    </td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-300">{{ formatAngkaDesimal($realisasi_penjualan_lastyear_sampaibulanini) }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-300">{{ formatAngkaDesimal($target_sampaibulanini) }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ formatAngkaDesimal($realisasi_penjualan_sampaibulanini) }}</td>
                                    <td class="py-1.5 px-2.5 text-center font-mono-num font-bold {{ $colorach_sampaibulanini }}">{{ formatAngkaDesimal($ach_sampaibulanini) }}</td>
                                    <td class="py-1.5 px-2.5 text-center font-mono-num font-bold {{ $colorgrw_sampaibulanini }}">
                                        <span class="inline-flex items-center gap-0.5">
                                            @if (!empty($grw_sampaibulanini))
                                                <i data-lucide="{{ $grw_sampaibulanini < 0 ? 'trending-down' : 'trending-up' }}" class="w-3 h-3"></i>
                                            @endif
                                            {{ formatAngkaDesimal($grw_sampaibulanini) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 2. TAB: SELLING OUT -->
        <div class="tab-pane hidden" id="sellingout" role="tabpanel">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xs bg-white dark:bg-slate-900">
                <div class="overflow-x-auto max-h-[540px] scrollbar-thin">
                    <table class="w-full text-left border-collapse text-xs font-sans whitespace-nowrap">
                        <thead>
                            <tr class="bg-[#284c9a] text-white">
                                <th rowspan="2" class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] border-b border-r border-white/15">Kode</th>
                                <th rowspan="2" class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] border-b border-r border-white/15">Nama Produk</th>
                                <th colspan="5" class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center border-b border-r border-white/15">Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                                <th colspan="5" class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center border-b border-white/15">Sampai Dengan Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                            </tr>
                            <tr class="bg-[#1e3a78] text-white/90">
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Real {{ $lastyear }}</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Target</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Real {{ $tahun }}</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-center border-b border-r border-white/10">Ach%</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-center border-b border-r border-white/15">Grw%</th>

                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Real {{ $lastyear }}</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Target</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-right border-b border-r border-white/10">Real {{ $tahun }}</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-center border-b border-r border-white/10">Ach%</th>
                                <th class="py-2 px-2.5 font-bold uppercase tracking-wider text-[11px] text-center border-b border-white/10">Grw%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/70">
                            @foreach ($selling_out as $d)
                                @php
                                    $selling_out_lastyear = $d->selling_out_lastyear / $d->isi_pcs_dus;
                                    $target = $d->target;
                                    $so = $d->selling_out / $d->isi_pcs_dus;
                                    $ach = !empty($target) ? ($so / $target) * 100 : 0;
                                    $grw = !empty($selling_out_lastyear) ? (($so - $selling_out_lastyear) / $selling_out_lastyear) * 100 : 0;
                                    $colorach = $ach < 100 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400';
                                    $colorgrw = $grw < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400';

                                    $selling_out_lastyear_sampaibulanini = $d->selling_out_lastyear_sampaibulanini / $d->isi_pcs_dus;
                                    $target_sampaibulanini = $d->target_sampaibulanini;
                                    $selling_out_sampaibulanini = $d->selling_out_sampaibulanini / $d->isi_pcs_dus;
                                    $ach_sampaibulanini = !empty($target_sampaibulanini)
                                        ? ($selling_out_sampaibulanini / $target_sampaibulanini) * 100
                                        : 0;
                                    $grw_sampaibulanini = !empty($selling_out_lastyear_sampaibulanini)
                                        ? (($selling_out_sampaibulanini - $selling_out_lastyear_sampaibulanini) / $selling_out_lastyear_sampaibulanini) * 100
                                        : 0;
                                    $colorach_sampaibulanini = $ach_sampaibulanini < 100 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400';
                                    $colorgrw_sampaibulanini = $grw_sampaibulanini < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400';
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-1.5 px-3 font-mono-num font-bold text-slate-700 dark:text-slate-200">{{ $d->kode_produk }}</td>
                                    <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">{{ $d->nama_produk }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-300">{{ formatAngkaDesimal($selling_out_lastyear) }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-300">{{ formatAngkaDesimal($target) }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ formatAngkaDesimal($so) }}</td>
                                    <td class="py-1.5 px-2.5 text-center font-mono-num font-bold {{ $colorach }}">{{ formatAngkaDesimal($ach) }}</td>
                                    <td class="py-1.5 px-2.5 text-center font-mono-num font-bold {{ $colorgrw }}">
                                        <span class="inline-flex items-center gap-0.5">
                                            @if (!empty($grw))
                                                <i data-lucide="{{ $grw < 0 ? 'trending-down' : 'trending-up' }}" class="w-3 h-3"></i>
                                            @endif
                                            {{ formatAngkaDesimal($grw) }}
                                        </span>
                                    </td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-300">{{ formatAngkaDesimal($selling_out_lastyear_sampaibulanini) }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num text-slate-600 dark:text-slate-300">{{ formatAngkaDesimal($target_sampaibulanini) }}</td>
                                    <td class="py-1.5 px-2.5 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ formatAngkaDesimal($selling_out_sampaibulanini) }}</td>
                                    <td class="py-1.5 px-2.5 text-center font-mono-num font-bold {{ $colorach_sampaibulanini }}">{{ formatAngkaDesimal($ach_sampaibulanini) }}</td>
                                    <td class="py-1.5 px-2.5 text-center font-mono-num font-bold {{ $colorgrw_sampaibulanini }}">
                                        <span class="inline-flex items-center gap-0.5">
                                            @if (!empty($grw_sampaibulanini))
                                                <i data-lucide="{{ $grw_sampaibulanini < 0 ? 'trending-down' : 'trending-up' }}" class="w-3 h-3"></i>
                                            @endif
                                            {{ formatAngkaDesimal($grw_sampaibulanini) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.nav-tabs button[data-bs-toggle="tab"]').on('click', function(e) {
            e.preventDefault();
            const $this = $(this);
            const target = $this.attr('data-bs-target');
            const $container = $this.closest('.space-y-4');

            $container.find('.nav-tabs .nav-link')
                .removeClass('active border-[#284c9a] text-[#284c9a] dark:text-blue-400')
                .addClass('border-transparent text-slate-500');

            $this.addClass('active border-[#284c9a] text-[#284c9a] dark:text-blue-400')
                .removeClass('border-transparent text-slate-500');

            $container.find('.tab-pane').removeClass('active').addClass('hidden');
            $container.find(target).removeClass('hidden').addClass('active');
        });
    });
</script>
