<div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
    <!-- Header Strip -->
    <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
        <div class="flex items-center gap-1.5">
            <i data-lucide="receipt" class="w-3.5 h-3.5 text-blue-200"></i>
            <h6 class="text-xs font-bold text-white uppercase tracking-wider">Hasil Pencarian Transaksi Kas Kecil</h6>
        </div>
        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
            {{ count($kaskecil) }} Transaksi
        </span>
    </div>

    <!-- Scrollable Table Container -->
    <div class="overflow-x-auto max-h-[360px] overflow-y-auto">
        <table class="w-full text-left text-xs whitespace-nowrap" id="tableGetDataKlaim">
            <thead class="bg-[#1e3a78] text-white/90 sticky top-0 z-20">
                <tr>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-10">NO</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NO. BUKTI</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[180px]">KETERANGAN</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[160px]">AKUN</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">PENERIMAAN</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">PENGELUARAN</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">SALDO</th>
                    <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-14">
                        <div class="flex items-center justify-center gap-1">
                            <input type="checkbox" id="checkAllKaskecil" class="rounded border-slate-300 dark:border-slate-700 text-[#284c9a] focus:ring-0 focus:outline-hidden w-3.5 h-3.5 cursor-pointer" title="Pilih Semua">
                        </div>
                    </th>
                </tr>
                <tr class="bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-100 font-bold border-t border-slate-200 dark:border-slate-700">
                    <td colspan="7" class="py-1.5 px-3 uppercase text-[11px]">SALDO AWAL</td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                        {{ $saldoawal != null ? formatAngka($saldoawal->saldo_awal) : 0 }}
                    </td>
                    <td></td>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                @php
                    $saldo = $saldoawal != null ? $saldoawal->saldo_awal : 0;
                    $total_penerimaan = 0;
                    $total_pengeluaran = 0;
                @endphp
                @forelse ($kaskecil as $d)
                    @php
                        $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                        $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                        $saldo += $penerimaan - $pengeluaran;
                        $total_penerimaan += $penerimaan;
                        $total_pengeluaran += $pengeluaran;
                        $hasKlaim = !empty($d->kode_klaim);
                    @endphp
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition {{ $hasKlaim ? 'bg-emerald-50/20 dark:bg-emerald-950/20' : '' }}">
                        <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                            {{ formatIndo($d->tanggal) }}
                        </td>
                        <td class="py-1.5 px-3 text-center font-bold font-mono-num text-slate-800 dark:text-slate-200">
                            {{ $d->no_bukti }}
                        </td>
                        <td class="py-1.5 px-3 text-slate-800 dark:text-slate-200 max-w-[220px] truncate" title="{{ $d->keterangan }}">
                            {{ textCamelcase($d->keterangan) }}
                        </td>
                        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                            <span class="font-bold font-mono-num text-slate-700 dark:text-slate-300">{{ $d->kode_akun }}</span>
                            <span class="text-[11px]">- {{ $d->nama_akun }}</span>
                        </td>
                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                            {{ $penerimaan > 0 ? formatAngka($penerimaan) : '-' }}
                        </td>
                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                            {{ $pengeluaran > 0 ? formatAngka($pengeluaran) : '-' }}
                        </td>
                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                            {{ formatAngka($saldo) }}
                        </td>
                        <td class="py-1.5 px-3 text-center">
                            @if ($hasKlaim)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300" title="Sudah Masuk Klaim">
                                    {{ $d->kode_klaim }}
                                </span>
                            @else
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" name="id_kaskecil[]" value="{{ $d->id }}"
                                        class="item-checkbox rounded border-slate-300 dark:border-slate-700 text-[#284c9a] focus:ring-0 focus:outline-hidden w-3.5 h-3.5 cursor-pointer">
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="inbox" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-semibold">Tidak ada transaksi kas kecil pada periode dan cabang ini</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if (count($kaskecil) > 0)
                <tfoot class="bg-[#1e3a78] text-white font-bold sticky bottom-0 z-20">
                    <tr>
                        <td colspan="5" class="py-2 px-3 text-center uppercase text-[11px]">TOTAL</td>
                        <td class="py-2 px-3 text-right font-mono-num text-xs text-emerald-300">{{ formatAngka($total_penerimaan) }}</td>
                        <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-300">{{ formatAngka($total_pengeluaran) }}</td>
                        <td class="py-2 px-3 text-right font-mono-num text-xs text-white font-black">{{ formatAngka($saldo) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Check All / Uncheck All handler
        $("#checkAllKaskecil").on('change', function() {
            const isChecked = $(this).is(':checked');
            $("#tableGetDataKlaim .item-checkbox").prop('checked', isChecked);
            updateSelectedCounter();
        });

        $(document).on('change', '.item-checkbox', function() {
            const total = $("#tableGetDataKlaim .item-checkbox").length;
            const checked = $("#tableGetDataKlaim .item-checkbox:checked").length;
            $("#checkAllKaskecil").prop('checked', total > 0 && total === checked);
            updateSelectedCounter();
        });

        function updateSelectedCounter() {
            const checked = $("#tableGetDataKlaim .item-checkbox:checked").length;
            if (checked > 0) {
                $("#selectedCountBadge").text(`${checked} Dipilih`).removeClass('hidden');
            } else {
                $("#selectedCountBadge").addClass('hidden');
            }
        }
    });
</script>
