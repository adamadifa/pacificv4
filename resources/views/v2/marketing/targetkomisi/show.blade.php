@php
    $namaBulan = !empty($namabulan[$targetkomisi->bulan]) ? $namabulan[$targetkomisi->bulan] : $targetkomisi->bulan;
@endphp

<div class="space-y-4">
    <!-- SECTION 1: EXECUTIVE SUMMARY STRIP (STANDAR V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3 sm:p-3.5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <!-- 1. Kode Target -->
            <div>
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Kode Target</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm block">
                    {{ $targetkomisi->kode_target }}
                </span>
            </div>

            <!-- 2. Cabang -->
            <div class="sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Cabang</span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-bold text-xs border border-blue-200/60 dark:border-blue-900/50">
                    <i data-lucide="building-2" class="w-3 h-3"></i>
                    <span>{{ textUpperCase($targetkomisi->nama_cabang) }}</span>
                </span>
            </div>

            <!-- 3. Periode Bulan & Tahun -->
            <div class="sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Periode</span>
                <span class="font-bold text-slate-800 dark:text-slate-100 text-xs block">
                    {{ $namaBulan }} {{ $targetkomisi->tahun }}
                </span>
            </div>

            <!-- 4. Status Dokumen -->
            <div class="sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Status Target</span>
                <div>
                    @if ($targetkomisi->status == '1')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Disetujui
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/80 dark:border-amber-900/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Pending Approval
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: TABEL RINCIAN MATRIKS TARGET (STICKY SALESMAN COLUMNS) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[460px] scrollbar-thin">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead class="sticky top-0 z-20">
                    <!-- Baris 1: Header Top (Frozen Kode & Salesman) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th rowspan="4" class="sticky left-0 z-40 w-[80px] min-w-[80px] max-w-[80px] py-2 px-3 text-center font-bold uppercase tracking-wider text-white bg-[#284c9a] border-r border-white/10 align-middle">KODE</th>
                        <th rowspan="4" class="sticky left-[80px] z-40 w-[180px] min-w-[180px] max-w-[180px] py-2 px-3 font-bold uppercase tracking-wider text-white bg-[#284c9a] border-r-2 border-[#193267] shadow-[3px_0_6px_-2px_rgba(0,0,0,0.35)] align-middle">SALESMAN</th>
                        <th rowspan="4" class="py-2 px-3 text-center w-24 min-w-[90px] font-bold uppercase tracking-wider text-white border-r border-white/10 align-middle bg-[#284c9a]">NIK</th>
                        <th rowspan="4" class="py-2 px-3 text-center w-28 min-w-[100px] font-bold uppercase tracking-wider text-white border-r border-white/10 align-middle bg-[#284c9a]">MASA KERJA</th>
                        <th colspan="{{ count($produk) * 10 }}" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white">
                            RINCIAN TARGET PRODUK
                        </th>
                    </tr>

                    <!-- Baris 2: Nama Produk Header -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        @foreach ($produk as $p)
                            <th colspan="10" class="py-1.5 px-2 text-center font-bold uppercase tracking-wider text-amber-300 border-x border-white/15">
                                {{ $p->kode_produk }} - {{ $p->nama_produk }}
                            </th>
                        @endforeach
                    </tr>

                    <!-- Baris 3: Kategori Metrik -->
                    <tr class="bg-[#193267] text-white text-[10.5px]">
                        @foreach ($produk as $p)
                            <th rowspan="2" class="py-1 px-2 text-center font-bold uppercase tracking-wider bg-emerald-950/70 text-emerald-300 border-l border-white/10">AVG</th>
                            <th colspan="3" class="py-1 px-2 text-center font-bold uppercase tracking-wider bg-sky-950/70 text-sky-300 border-x border-white/10">Realisasi Penjualan</th>
                            <th rowspan="2" class="py-1 px-2 text-center font-bold uppercase tracking-wider bg-indigo-950/70 text-indigo-300 border-r border-white/10">Last</th>
                            <th colspan="5" class="py-1 px-2 text-center font-bold uppercase tracking-wider bg-[#132752] text-amber-200 border-r border-white/10">Target Penyesuaian</th>
                        @endforeach
                    </tr>

                    <!-- Baris 4: Kolom Riwayat Realisasi & Tingkatan Target -->
                    <tr class="bg-[#152a56] text-white text-[10px]">
                        @foreach ($produk as $p)
                            <th class="py-1 px-2 text-center font-semibold bg-sky-950/60 text-sky-200">{{ getMonthName2($lasttigabulan) }}</th>
                            <th class="py-1 px-2 text-center font-semibold bg-sky-950/60 text-sky-200">{{ getMonthName2($lastduabulan) }}</th>
                            <th class="py-1 px-2 text-center font-semibold bg-sky-950/60 text-sky-200">{{ getMonthName2($lastbulan) }}</th>
                            <th class="py-1 px-2 text-center font-bold text-white/80">AWAL</th>
                            <th class="py-1 px-2 text-center font-bold text-white/80">RSM</th>
                            <th class="py-1 px-2 text-center font-bold text-white/80">GM</th>
                            <th class="py-1 px-2 text-center font-bold text-white/80">DIRUT</th>
                            <th class="py-1 px-2 text-center font-black bg-amber-950/70 text-amber-300 border-r border-white/10">AKHIR</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php
                        foreach ($produk as $p) {
                            ${"total_rata_rata_penjualan_$p->kode_produk"} = 0;
                            ${"total_penjualan_tigabulan_$p->kode_produk"} = 0;
                            ${"total_penjualan_duabulan_$p->kode_produk"} = 0;
                            ${"total_penjualan_lastbulan_$p->kode_produk"} = 0;
                            ${"total_last_target_$p->kode_produk"} = 0;
                            ${"total_target_awal_$p->kode_produk"} = 0;
                            ${"total_target_rsm_$p->kode_produk"} = 0;
                            ${"total_target_gm_$p->kode_produk"} = 0;
                            ${"total_target_dirut_$p->kode_produk"} = 0;
                            ${"total_target_$p->kode_produk"} = 0;
                        }
                    @endphp

                    @forelse ($detail as $d)
                        <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <!-- 1. Kode (Sticky/Frozen Left-0) -->
                            <td class="sticky left-0 z-10 w-[80px] min-w-[80px] max-w-[80px] bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800 py-1.5 px-3 text-center border-b border-r border-slate-100 dark:border-slate-800">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono-num font-bold text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    {{ $d->kode_salesman }}
                                </span>
                            </td>

                            <!-- 2. Salesman (Sticky/Frozen Left-80px with Elevation Shadow) -->
                            <td class="sticky left-[80px] z-10 w-[180px] min-w-[180px] max-w-[180px] bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800 py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 border-r-2 border-slate-200 dark:border-slate-700 shadow-[3px_0_6px_-2px_rgba(0,0,0,0.12)] truncate" title="{{ $d->nama_salesman }}">
                                {{ $d->nama_salesman }}
                            </td>

                            <!-- 3. NIK -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500 text-[11px] border-b border-r border-slate-100 dark:border-slate-800 w-24 min-w-[90px]">
                                {{ $d->nik ?? '-' }}
                            </td>

                            <!-- 4. Masa Kerja -->
                            <td class="py-1.5 px-3 text-center text-[10.5px] text-slate-500 font-mono-num border-b border-r border-slate-100 dark:border-slate-800 w-28 min-w-[100px]">
                                @php
                                    $end_date = $targetkomisi->tahun . '-' . $targetkomisi->bulan . '-01';
                                    $masakerja = hitungMasakerja($d->tanggal_masuk, $end_date);
                                @endphp
                                @if (!empty($d->tanggal_masuk))
                                    {{ $masakerja['tahun'] }} Thn {{ $masakerja['bulan'] }} Bln
                                @else
                                    -
                                @endif
                            </td>

                            <!-- Per Produk Data -->
                            @foreach ($produk as $p)
                                @php
                                    $isiDus = $p->isi_pcs_dus > 0 ? $p->isi_pcs_dus : 1;
                                    $rata_rata_penjualan = $d->{"penjualan_$p->kode_produk"} / $isiDus / 3;
                                    $jml_penjualan_tigabulan = $d->{"penjualan_tiga_bulan_$p->kode_produk"} / $isiDus;
                                    $jml_penjualan_duabulan = $d->{"penjualan_dua_bulan_$p->kode_produk"} / $isiDus;
                                    $jml_penjualan_lastbulan = $d->{"penjualan_last_bulan_$p->kode_produk"} / $isiDus;
                                    $jml_last_target = $d->{"target_last_$p->kode_produk"};

                                    ${"total_rata_rata_penjualan_$p->kode_produk"} += $rata_rata_penjualan;
                                    ${"total_penjualan_tigabulan_$p->kode_produk"} += $jml_penjualan_tigabulan;
                                    ${"total_penjualan_duabulan_$p->kode_produk"} += $jml_penjualan_duabulan;
                                    ${"total_penjualan_lastbulan_$p->kode_produk"} += $jml_penjualan_lastbulan;
                                    ${"total_last_target_$p->kode_produk"} += $jml_last_target;
                                    ${"total_target_awal_$p->kode_produk"} += (float)($d->{"target_awal_$p->kode_produk"} ?? 0);
                                    ${"total_target_rsm_$p->kode_produk"} += (float)($d->{"target_rsm_$p->kode_produk"} ?? 0);
                                    ${"total_target_gm_$p->kode_produk"} += (float)($d->{"target_gm_$p->kode_produk"} ?? 0);
                                    ${"total_target_dirut_$p->kode_produk"} += (float)($d->{"target_dirut_$p->kode_produk"} ?? 0);
                                    ${"total_target_$p->kode_produk"} += (float)($d->{"target_$p->kode_produk"} ?? 0);
                                @endphp

                                <td class="py-1.5 px-2 text-right font-mono-num font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20">
                                    {{ formatAngka($rata_rata_penjualan) }}
                                </td>
                                <td class="py-1.5 px-2 text-right font-mono-num text-sky-700 dark:text-sky-400 bg-sky-50/40 dark:bg-sky-950/20">
                                    {{ formatAngka($jml_penjualan_tigabulan) }}
                                </td>
                                <td class="py-1.5 px-2 text-right font-mono-num text-sky-700 dark:text-sky-400 bg-sky-50/40 dark:bg-sky-950/20">
                                    {{ formatAngka($jml_penjualan_duabulan) }}
                                </td>
                                <td class="py-1.5 px-2 text-right font-mono-num text-sky-700 dark:text-sky-400 bg-sky-50/40 dark:bg-sky-950/20">
                                    {{ formatAngka($jml_penjualan_lastbulan) }}
                                </td>
                                <td class="py-1.5 px-2 text-right font-mono-num font-semibold text-indigo-700 dark:text-indigo-400 bg-indigo-50/40 dark:bg-indigo-950/20">
                                    {{ formatAngka($jml_last_target) }}
                                </td>
                                <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngka($d->{"target_awal_$p->kode_produk"}) }}
                                </td>
                                <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngka($d->{"target_rsm_$p->kode_produk"}) }}
                                </td>
                                <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngka($d->{"target_gm_$p->kode_produk"}) }}
                                </td>
                                <td class="py-1.5 px-2 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngka($d->{"target_dirut_$p->kode_produk"}) }}
                                </td>
                                <td class="py-1.5 px-2 text-right font-mono-num font-black text-amber-600 dark:text-amber-400 bg-amber-50/60 dark:bg-amber-950/20">
                                    {{ formatAngka($d->{"target_$p->kode_produk"}) }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (count($produk) * 10) + 4 }}" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-7 h-7 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Data detail target komisi tidak ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if (count($detail) > 0)
                    <!-- FOOTER GRAND TOTAL -->
                    <tfoot class="bg-slate-50 dark:bg-slate-800/90 font-bold border-t-2 border-slate-200 dark:border-slate-700 text-[11px] sticky bottom-0 z-10 backdrop-blur-xs">
                        <tr>
                            <td class="sticky left-0 z-20 bg-slate-100 dark:bg-slate-800 border-t-2 border-r border-slate-200 dark:border-slate-700"></td>
                            <td class="sticky left-[80px] z-20 bg-slate-100 dark:bg-slate-800 py-2 px-3 font-bold uppercase tracking-wider text-slate-800 dark:text-slate-100 border-t-2 border-r-2 border-slate-300 dark:border-slate-600 shadow-[3px_0_6px_-2px_rgba(0,0,0,0.12)] truncate">
                                TOTAL TARGET
                            </td>
                            <td class="bg-slate-50 dark:bg-slate-800/90 border-t-2 border-slate-200 dark:border-slate-700"></td>
                            <td class="bg-slate-50 dark:bg-slate-800/90 border-t-2 border-slate-200 dark:border-slate-700"></td>
                            @foreach ($produk as $p)
                                <td class="py-2 px-2 text-right font-mono-num text-emerald-800 dark:text-emerald-300 bg-emerald-50/80 dark:bg-emerald-950/30">
                                    {{ formatAngka(${"total_rata_rata_penjualan_$p->kode_produk"}) }}
                                </td>
                                <td class="py-2 px-2 text-right font-mono-num text-sky-800 dark:text-sky-300 bg-sky-50/70 dark:bg-sky-950/30">
                                    {{ formatAngka(${"total_penjualan_tigabulan_$p->kode_produk"}) }}
                                </td>
                                <td class="py-2 px-2 text-right font-mono-num text-sky-800 dark:text-sky-300 bg-sky-50/70 dark:bg-sky-950/30">
                                    {{ formatAngka(${"total_penjualan_duabulan_$p->kode_produk"}) }}
                                </td>
                                <td class="py-2 px-2 text-right font-mono-num text-sky-800 dark:text-sky-300 bg-sky-50/70 dark:bg-sky-950/30">
                                    {{ formatAngka(${"total_penjualan_lastbulan_$p->kode_produk"}) }}
                                </td>
                                <td class="py-2 px-2 text-right font-mono-num text-indigo-800 dark:text-indigo-300 bg-indigo-50/70 dark:bg-indigo-950/30">
                                    {{ formatAngka(${"total_last_target_$p->kode_produk"}) }}
                                </td>
                                <td class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-200">
                                    {{ formatAngka(${"total_target_awal_$p->kode_produk"}) }}
                                </td>
                                <td class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-200">
                                    {{ formatAngka(${"total_target_rsm_$p->kode_produk"}) }}
                                </td>
                                <td class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-200">
                                    {{ formatAngka(${"total_target_gm_$p->kode_produk"}) }}
                                </td>
                                <td class="py-2 px-2 text-right font-mono-num text-slate-800 dark:text-slate-200">
                                    {{ formatAngka(${"total_target_dirut_$p->kode_produk"}) }}
                                </td>
                                <td class="py-2 px-2 text-right font-mono-num font-black text-amber-600 dark:text-amber-400 bg-amber-50/80 dark:bg-amber-950/30">
                                    {{ formatAngka(${"total_target_$p->kode_produk"}) }}
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- SECTION 3: KETERANGAN WARNA METRIK -->
    <div class="flex items-center gap-4 flex-wrap text-[11px] text-slate-500 dark:text-slate-400 px-1">
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-xs bg-emerald-100 dark:bg-emerald-950/80 border border-emerald-300 dark:border-emerald-700"></span>
            <span>Rata-Rata Penjualan 3 Bulan (AVG)</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-xs bg-sky-100 dark:bg-sky-950/80 border border-sky-300 dark:border-sky-700"></span>
            <span>Realisasi Penjualan Historis</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-xs bg-indigo-100 dark:bg-indigo-950/80 border border-indigo-300 dark:border-indigo-700"></span>
            <span>Target Terakhir (Last Target)</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-xs bg-amber-100 dark:bg-amber-950/80 border border-amber-300 dark:border-amber-700"></span>
            <span>Target Akhir Disetujui</span>
        </div>
    </div>

    <!-- ACTION FOOTER -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Tutup
        </button>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
