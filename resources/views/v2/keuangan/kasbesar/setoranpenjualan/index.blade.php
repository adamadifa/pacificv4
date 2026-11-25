@extends('v2.layouts.app')
@section('titlepage', 'Setoran Penjualan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Kas Besar</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Setoran Penjualan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Setoran Penjualan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen setoran hasil penjualan per salesman.</p>
        </div>
    </div>

    <!-- NAVIGATION TABS (V2 PILLS) -->
    @include('v2.layouts.navigation_kasbesar')

    <!-- FILTER FORM (NO-LABEL COMPACT V2 - 2 BARIS SEIMBANG) -->
    <form action="{{ route('setoranpenjualan.index') }}" method="GET" class="w-full">
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-12 gap-2.5 items-center">
                <!-- Dari Tanggal -->
                <div class="relative col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Cabang (Jika Ada Hak Akses) -->
                @hasanyrole($roles_show_cabang)
                    <div class="relative select2-container-v2 col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-2">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_search_sp" class="select2Kodecabangsearch w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ textUpperCase($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endhasanyrole

                <!-- Salesman -->
                <div class="relative select2-container-v2 col-span-1 sm:col-span-1 md:col-span-2 {{ auth()->user()->hasRole($roles_show_cabang) ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_salesman_search" id="kode_salesman_search_sp" class="select2Kodesalesmansearch w-full">
                        <option value="">Semua Salesman</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="relative col-span-1 sm:col-span-1 md:col-span-2 {{ auth()->user()->hasRole($roles_show_cabang) ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </div>
                    <select name="status" id="status_sp"
                        class="w-full h-[36px] pl-9 pr-7 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                        <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <!-- Tombol Cari & Reset -->
                <div class="flex items-center gap-1.5 col-span-1 sm:col-span-1 md:col-span-2 lg:col-span-2">
                    <button type="submit"
                        class="h-[36px] flex-1 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span>Cari</span>
                    </button>
                    @if (request()->hasAny(['dari', 'sampai', 'kode_cabang_search', 'kode_salesman_search', 'status']))
                        <a href="{{ route('setoranpenjualan.index') }}"
                            class="h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 text-xs font-bold transition flex items-center justify-center cursor-pointer shrink-0" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <!-- FORM CETAK (TERSEMBUNYI UNTUK SUBMIT FORM HEADER) -->
    @can('setoranpenjualan.show')
        <form action="/setoranpenjualan/cetak" method="GET" id="formCetak" target="_blank" class="hidden">
            <input type="hidden" name="dari" id="dari_cetak" value="{{ Request('dari') }}" />
            <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
            <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
            <input type="hidden" name="kode_salesman_search" id="kode_salesman_cetak" value="{{ Request('kode_salesman_search') }}" />
        </form>
    @endcan

    <!-- DATA TABLE CARD (V2 PACIFIC NAVY 2-TIER ~36PX - 100% PARITAS V1) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="table" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Setoran Penjualan</h5>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num ml-1">
                    {{ count($setoran_penjualan) }} Data
                </span>
            </div>
            <div class="flex items-center gap-2">
                @can('setoranpenjualan.show')
                    <button form="formCetak" type="submit"
                        class="h-[28px] px-2.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                        <span>Cetak</span>
                    </button>
                    <button form="formCetak" type="submit" name="exportButton"
                        class="h-[28px] px-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        <span>Export</span>
                    </button>
                @endcan
                @can('setoranpenjualan.create')
                    <button type="button" id="btnCreateSetoran"
                        class="h-[28px] px-3 rounded-lg bg-white hover:bg-blue-50 text-[#284c9a] text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer shrink-0">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Input Setoran</span>
                    </button>
                @endcan
            </div>
        </div>

        <!-- TABLE CONTENT -->
        <div class="overflow-x-auto min-h-[280px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <!-- HEADER KOLOM 2 BARIS SESUAI V1 -->
                <thead>
                    <tr>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white text-center align-middle bg-[#284c9a] border-r border-white/10">TANGGAL</th>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white text-center align-middle bg-[#284c9a] border-r border-white/10">SALESMAN</th>
                        <th colspan="2" class="py-1 px-3 text-[11px] font-bold uppercase tracking-wider text-white text-center bg-emerald-700 border-r border-white/10">PENJUALAN</th>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white text-center align-middle bg-emerald-800 border-r border-white/10">TOTAL LHP</th>
                        <th colspan="5" class="py-1 px-3 text-[11px] font-bold uppercase tracking-wider text-white text-center bg-rose-700 border-r border-white/10">SETORAN</th>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white text-center align-middle bg-rose-800 border-r border-white/10">TOTAL SETORAN</th>
                        <th rowspan="2" class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white text-center align-middle bg-[#284c9a] w-20">#</th>
                    </tr>
                    <tr>
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-white text-center bg-emerald-800 border-r border-white/10">TUNAI</th>
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-white text-center bg-emerald-800 border-r border-white/10">TAGIHAN</th>

                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-white text-center bg-rose-800 border-r border-white/10">KERTAS</th>
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-white text-center bg-rose-800 border-r border-white/10">LOGAM</th>
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-white text-center bg-rose-800 border-r border-white/10">GIRO</th>
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-white text-center bg-rose-800 border-r border-white/10">TRANSFER</th>
                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-white text-center bg-rose-800 border-r border-white/10">LAINNYA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @php
                        $subtotal_lhp_tunai = 0;
                        $subtotal_lhp_tagihan = 0;
                        $subtotal_total_lhp = 0;

                        $subtotal_setoran_kertas = 0;
                        $subtotal_setoran_logam = 0;
                        $subtotal_setoran_lainnya = 0;
                        $subtotal_setoran_transfer = 0;
                        $subtotal_setoran_giro = 0;
                        $subtotal_total_setoran = 0;
                    @endphp
                    @forelse ($setoran_penjualan as $key => $d)
                        @php
                            $next_tanggal = @$setoran_penjualan[$key + 1]->tanggal;
                            $total_lhp = $d->lhp_tunai + $d->lhp_tagihan;
                            $uk = $d->kurangsetorkertas - $d->lebihsetorkertas;
                            $ul = $d->kurangsetorlogam - $d->lebihsetorlogam;
                            $setoran_kertas = $d->setoran_kertas + $uk;
                            $setoran_logam = $d->setoran_logam + $ul;
                            $total_setoran =
                                $setoran_kertas + $setoran_logam + $d->setoran_giro + $d->setoran_transfer + $d->setoran_lainnya;

                            $subtotal_lhp_tunai += $d->lhp_tunai;
                            $subtotal_lhp_tagihan += $d->lhp_tagihan;
                            $subtotal_total_lhp += $total_lhp;

                            $subtotal_setoran_kertas += $setoran_kertas;
                            $subtotal_setoran_logam += $setoran_logam;
                            $subtotal_setoran_lainnya += $d->setoran_lainnya;
                            $subtotal_setoran_transfer += $d->setoran_transfer;
                            $subtotal_setoran_giro += $d->setoran_giro;
                            $subtotal_total_setoran += $total_setoran;

                            $cek_tagihan = $d->cek_lhp_tagihan + $d->cek_lhp_giro + $d->cek_lhp_transfer;
                            $color_setoran_tunai = $d->lhp_tunai == $d->cek_lhp_tunai ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400 font-bold';
                            $color_setoran_tagihan = $d->lhp_tagihan == $cek_tagihan ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400 font-bold';
                            $cek_giro_to_cash_transfer = $d->cek_giro_to_cash_transfer;
                            $giro_to_cash_transfer = $d->giro_to_cash + $d->giro_to_transfer;

                            if (
                                $d->lhp_tunai == $d->cek_lhp_tunai &&
                                $d->lhp_tagihan == $cek_tagihan &&
                                $giro_to_cash_transfer == $cek_giro_to_cash_transfer
                            ) {
                                $color_total_lhp = 'text-emerald-600 dark:text-emerald-400';
                            } else {
                                $color_total_lhp = 'text-rose-600 dark:text-rose-400 font-bold';
                            }

                            $opkertas = '+';
                            $oplogam = '+';

                            $selisih = $total_setoran - $total_lhp;

                            $kontenkertas = formatRupiah($d->setoran_kertas) . $opkertas . formatRupiah($uk);
                            $kontenlogam = formatRupiah($d->setoran_logam) . $oplogam . formatRupiah($ul);

                            $nama_salesman = explode(' ', $d->nama_salesman);
                            $nama_depan = $d->nama_salesman != 'NON SALES' ? $nama_salesman[0] : $d->nama_salesman;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 text-center font-mono-num">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                            <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ textUpperCase($nama_depan) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num {{ $color_setoran_tunai }}">
                                {{ formatAngka($d->lhp_tunai) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num {{ $color_setoran_tagihan }}">
                                {{ formatAngka($d->lhp_tagihan) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num {{ $color_total_lhp }} cursor-pointer showlhp hover:underline"
                                tanggal="{{ $d->tanggal }}" kode_salesman="{{ $d->kode_salesman }}" title="Klik untuk melihat Detail LHP">
                                {{ formatAngka($total_lhp) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num" title="Rincian: {{ $kontenkertas }}">
                                {{ formatAngka($setoran_kertas) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num" title="Rincian: {{ $kontenlogam }}">
                                {{ formatAngka($setoran_logam) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num">{{ formatAngka($d->setoran_giro) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num">{{ formatAngka($d->setoran_transfer) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num">{{ formatAngka($d->setoran_lainnya) }}</td>
                            <td class="py-1.5 px-3 text-right font-bold font-mono-num text-slate-900 dark:text-white">
                                {{ formatAngka($total_setoran) }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('setoranpenjualan.show')
                                        @if (!empty($d->keterangan))
                                            <span class="w-6 h-6 rounded-md bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 flex items-center justify-center cursor-help"
                                                title="{{ $d->keterangan }}">
                                                <i data-lucide="info" class="w-3 h-3"></i>
                                            </span>
                                        @endif
                                    @endcan
                                    @can('setoranpenjualan.edit')
                                        <button type="button" class="btnEditSetoran w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 transition flex items-center justify-center cursor-pointer"
                                            kode_setoran="{{ Crypt::encrypt($d->kode_setoran) }}" title="Edit Setoran">
                                            <i data-lucide="pencil" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                    @can('setoranpenjualan.delete')
                                        <form action="{{ route('setoranpenjualan.delete', Crypt::encrypt($d->kode_setoran)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btnDeleteSetoran w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus Setoran">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>

                        @if ($d->tanggal != $next_tanggal)
                            <tr class="bg-slate-100 dark:bg-slate-800/80 font-bold border-y border-slate-200/80 dark:border-slate-700">
                                <td colspan="2" class="py-2 px-3 text-center text-xs uppercase tracking-wider text-slate-700 dark:text-slate-200">TOTAL HARIAN</td>
                                <td class="py-2 px-3 text-right font-mono-num text-xs text-brand-600 dark:text-brand-400">{{ formatAngka($subtotal_lhp_tunai) }}</td>
                                <td class="py-2 px-3 text-right font-mono-num text-xs text-brand-600 dark:text-brand-400">{{ formatAngka($subtotal_lhp_tagihan) }}</td>
                                <td class="py-2 px-3 text-right font-mono-num text-xs text-brand-600 dark:text-brand-400">{{ formatAngka($subtotal_total_lhp) }}</td>

                                <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-600 dark:text-rose-400">{{ formatAngka($subtotal_setoran_kertas) }}</td>
                                <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-600 dark:text-rose-400">{{ formatAngka($subtotal_setoran_logam) }}</td>
                                <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-600 dark:text-rose-400">{{ formatAngka($subtotal_setoran_giro) }}</td>
                                <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-600 dark:text-rose-400">{{ formatAngka($subtotal_setoran_transfer) }}</td>
                                <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-600 dark:text-rose-400">{{ formatAngka($subtotal_setoran_lainnya) }}</td>
                                <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-600 dark:text-rose-400">{{ formatAngka($subtotal_total_setoran) }}</td>
                                <td></td>
                            </tr>
                            @php
                                $subtotal_lhp_tunai = 0;
                                $subtotal_lhp_tagihan = 0;
                                $subtotal_total_lhp = 0;

                                $subtotal_setoran_kertas = 0;
                                $subtotal_setoran_logam = 0;
                                $subtotal_setoran_lainnya = 0;
                                $subtotal_setoran_transfer = 0;
                                $subtotal_setoran_giro = 0;
                                $subtotal_total_setoran = 0;
                            @endphp
                        @endif
                    @empty
                        <tr>
                            <td colspan="12" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data setoran penjualan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODALS V2 (STATIC BACKDROP) ================= -->

<!-- 1. MODAL CREATE SETORAN -->
<div id="modalCreateSetoran" class="fixed inset-0 z-50 hidden">
    <div id="modalCreateSetoranBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalCreateSetoranDialog" class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Pembayaran Setoran</h5>
                </div>
                <button type="button" id="btnCloseModalCreateSetoran"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalCreateSetoranContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- 2. MODAL EDIT SETORAN -->
<div id="modalEditSetoran" class="fixed inset-0 z-50 hidden">
    <div id="modalEditSetoranBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalEditSetoranDialog" class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="pencil" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Pembayaran Setoran</h5>
                </div>
                <button type="button" id="btnCloseModalEditSetoran"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalEditSetoranContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- 3. MODAL DETAIL LHP -->
<div id="modalShowLHP" class="fixed inset-0 z-50 hidden">
    <div id="modalShowLHPBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalShowLHPDialog" class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Laporan Hasil Penjualan (LHP)</h5>
                </div>
                <button type="button" id="btnCloseModalShowLHP"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="modalShowLHPContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $('.select2Kodecabangsearch').select2({
            placeholder: 'Semua Cabang',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        $('.select2Kodesalesmansearch').select2({
            placeholder: 'Semua Salesman',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search_sp").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
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
                    $("#kode_salesman_search_sp").html(respond);
                }
            });
        }

        $("#kode_cabang_search_sp").on('change', function() {
            getsalesmanbyCabang();
        });

        getsalesmanbyCabang();

        // Modal Helpers (Rule 9: Static Backdrop & Explicit Close)
        function initModalHelper(modalId, dialogId, backdropId, closeBtnId) {
            const $modal = $(modalId);
            const $dialog = $(dialogId);
            const $backdrop = $(backdropId);
            const $closeBtn = $(closeBtnId);

            function open() {
                $modal.removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function close() {
                $modal.addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }

            $closeBtn.on('click', close);

            $backdrop.on('click', function(e) {
                e.stopPropagation();
                $dialog.addClass('scale-[1.015] shadow-2xl');
                setTimeout(() => $dialog.removeClass('scale-[1.015] shadow-2xl'), 150);
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                    $dialog.addClass('scale-[1.015] shadow-2xl');
                    setTimeout(() => $dialog.removeClass('scale-[1.015] shadow-2xl'), 150);
                }
            });

            return { open, close };
        }

        window.modalCreateSetoran = initModalHelper('#modalCreateSetoran', '#modalCreateSetoranDialog', '#modalCreateSetoranBackdrop', '#btnCloseModalCreateSetoran');
        window.modalEditSetoran = initModalHelper('#modalEditSetoran', '#modalEditSetoranDialog', '#modalEditSetoranBackdrop', '#btnCloseModalEditSetoran');
        window.modalShowLHP = initModalHelper('#modalShowLHP', '#modalShowLHPDialog', '#modalShowLHPBackdrop', '#btnCloseModalShowLHP');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat data...</span>
            </div>
        `;

        // Action: Create
        $(document).on('click', '#btnCreateSetoran', function(e) {
            e.preventDefault();
            $("#modalCreateSetoranContent").html(loadingSpinner);
            modalCreateSetoran.open();
            $("#modalCreateSetoranContent").load('/setoranpenjualan/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Edit
        $(document).on('click', '.btnEditSetoran', function(e) {
            e.preventDefault();
            const kode_setoran = $(this).attr("kode_setoran");
            $("#modalEditSetoranContent").html(loadingSpinner);
            modalEditSetoran.open();
            $("#modalEditSetoranContent").load(`/setoranpenjualan/${kode_setoran}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Action: Detail LHP (POST Request ke /setoranpenjualan/showlhp)
        $(document).on('click', '.showlhp', function(e) {
            e.preventDefault();
            const tanggal = $(this).attr("tanggal");
            const kode_salesman = $(this).attr("kode_salesman");
            $("#modalShowLHPContent").html(loadingSpinner);
            modalShowLHP.open();
            $.ajax({
                type: 'POST',
                url: '/setoranpenjualan/showlhp',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $("#modalShowLHPContent").html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                },
                error: function() {
                    $("#modalShowLHPContent").html(`
                        <div class="py-8 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-semibold">Gagal memuat rincian LHP.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        });

        // Action: Cetak / Export Validation
        $("#formCetak").on('submit', function(e) {
            var dari = $("#dari_cetak").val();
            var sampai = $("#sampai_cetak").val();

            if (!dari && !sampai) {
                e.preventDefault();
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Lakukan Pencarian Data Terlebih Dahulu !",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                });
                return false;
            }
        });

        // Action: Delete Confirm
        $(document).on('click', '.btnDeleteSetoran', function(e) {
            e.preventDefault();
            const form = $(this).closest("form");
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data setoran penjualan ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
