@extends('layouts.app')
@section('titlepage', 'Detail Faktur')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('sfa.pelanggan') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Pelanggan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($penjualan->kode_pelanggan)) }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">{{ $penjualan->kode_pelanggan }}</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">{{ $penjualan->no_faktur }}</span>
    </div>
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('assets/vendor/js/signature/signature.css') }}">
<style>
    .kbw-signature {
        width: 100%;
        height: 180px;
        touch-action: none;
    }

    #sign canvas {
        width: 100% !important;
        height: 180px !important;
        touch-action: none;
    }
</style>

@php
    $total_netto =
        $penjualan->total_bruto -
        $penjualan->total_retur -
        $penjualan->potongan -
        $penjualan->potongan_istimewa -
        $penjualan->penyesuaian +
        $penjualan->ppn;
    $sisa_bayar = $total_netto - $penjualan->total_bayar;
@endphp

<div class="space-y-3.5 sm:space-y-4">
    <!-- TOP BAR -->
    <div class="flex items-center justify-between gap-2">
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($penjualan->kode_pelanggan)) }}"
            class="h-[36px] sm:h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1.5 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>

        <div class="flex items-center gap-1.5">
            <span class="px-2.5 py-1 rounded-md text-[10.5px] font-bold {{ $penjualan->jenis_transaksi == 'T' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' }}">
                {{ $penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}
            </span>
            @if ($penjualan->status_batal == '1')
                <span class="px-2.5 py-1 rounded-md text-[10.5px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-300">
                    BATAL
                </span>
            @else
                <span class="px-2.5 py-1 rounded-md text-[10.5px] font-bold {{ $penjualan->status == '1' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-300' }}">
                    {{ $penjualan->status == '1' ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
            @endif
        </div>
    </div>

    <!-- HERO FAKTUR CARD -->
    <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
        <div class="relative h-36 sm:h-44 w-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
            @if (Storage::disk('public')->exists('/penjualan/' . $penjualan->foto) && !empty($penjualan->foto))
                <img src="{{ getfotoPenjualan($penjualan->foto) }}" alt="Foto Faktur" id="foto" class="w-full h-full object-cover">
            @else
                <img src="{{ asset('assets/img/elements/1.jpg') }}" alt="Foto Faktur" id="foto" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/45 to-transparent"></div>
            <div class="absolute bottom-3 left-3.5 right-3.5 text-white">
                <div class="flex items-center justify-between gap-2">
                    <span class="px-2 py-0.5 rounded-md bg-white/20 backdrop-blur-xs text-[10.5px] font-bold font-mono-num">
                        {{ $penjualan->no_faktur }}
                    </span>
                    <span class="text-[11px] text-white/80 font-medium">{{ DateToIndo($penjualan->tanggal) }}</span>
                </div>
                <h4 class="text-sm sm:text-base font-black leading-tight mt-1 truncate">
                    {{ $penjualan->kode_pelanggan }} &bull; {{ textUpperCase($penjualan->nama_pelanggan) }}
                </h4>
            </div>
        </div>

        <!-- METRIC SUMMARY STRIP -->
        <div class="grid grid-cols-3 divide-x divide-slate-200/80 dark:divide-slate-800 bg-slate-50/70 dark:bg-slate-900/60 border-t border-slate-200/80 dark:border-slate-800">
            <div class="p-2.5 sm:p-3.5 min-w-0">
                <span class="block text-[9.5px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">Grand Total</span>
                <span class="block text-xs sm:text-sm font-black text-slate-800 dark:text-slate-100 font-mono-num mt-0.5 truncate">{{ formatAngka($total_netto) }}</span>
            </div>
            <div class="p-2.5 sm:p-3.5 min-w-0">
                <span class="block text-[9.5px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">Sudah Bayar</span>
                <span class="block text-xs sm:text-sm font-black text-emerald-600 dark:text-emerald-400 font-mono-num mt-0.5 truncate">{{ formatAngka($penjualan->total_bayar) }}</span>
            </div>
            <div class="p-2.5 sm:p-3.5 min-w-0">
                <span class="block text-[9.5px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">Sisa Bayar</span>
                <span class="block text-xs sm:text-sm font-black text-rose-600 dark:text-rose-400 font-mono-num mt-0.5 truncate">{{ formatAngka($sisa_bayar) }}</span>
            </div>
        </div>
    </div>

    @if ($penjualan->status_batal == '1')
        <div class="rounded-xl border border-amber-200 dark:border-amber-900 bg-amber-50 dark:bg-amber-950/40 p-4 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-amber-800 dark:text-amber-200 uppercase tracking-wider">Faktur Batal</h4>
                <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">Faktur ini sudah dibatalkan.</p>
            </div>
        </div>
    @else
        <!-- CETAK & BATAL ACTIONS -->
        <div class="flex items-center gap-2">
            <button type="button" onclick="ajax_print('/sfa/penjualan/{{ Crypt::encrypt($penjualan->no_faktur) }}/cetak', this)"
                class="flex-1 h-[40px] sm:h-[38px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Faktur</span>
            </button>

            @if (date('Y-m-d', strtotime($penjualan->created_at)) == date('Y-m-d'))
                @if ($penjualan->status_batal == '0')
                    <button type="button"
                        class="ubahfakturbatal h-[40px] sm:h-[38px] px-3.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 shrink-0 cursor-pointer"
                        title="Ubah Faktur Batal">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Batal</span>
                    </button>
                @elseif ($penjualan->status_batal == '2')
                    <a href="{{ route('sfa.batalkanubahfakturbatal', Crypt::encrypt($penjualan->no_faktur)) }}"
                        class="batalkanfakturbatal h-[40px] sm:h-[38px] px-3.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 shrink-0"
                        title="Batalkan Ajuan Batal">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Batalkan</span>
                    </a>
                @endif
            @endif
        </div>

        <!-- TANDA TANGAN CARD -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="pen-tool" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Tanda Tangan Pelanggan</span>
                </div>
            </div>

            <div class="p-3.5 space-y-2.5">
                @php
                    $path = Storage::url('signature/' . $penjualan->signature);
                @endphp
                @if (!empty($penjualan->signature))
                    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-950 p-2 flex items-center justify-center">
                        <img src="{{ url($path) }}" alt="Tanda Tangan" class="max-h-36 w-auto object-contain">
                    </div>

                    <div class="flex items-center gap-2">
                        <form method="POST" class="deleteform inline-flex m-0 p-0 shrink-0"
                            action="/sfa/{{ Crypt::encrypt($penjualan->no_faktur) }}/deletesignature">
                            @csrf
                            @method('DELETE')
                            <button type="button" tanggal="{{ $penjualan->tanggal }}"
                                class="delete-confirm h-[38px] px-3.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-bold inline-flex items-center justify-center gap-1.5 transition cursor-pointer">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Hapus</span>
                            </button>
                        </form>

                        <button type="button" onclick="return sendUrlToPrint('{{ url($path) }}');"
                            class="flex-1 h-[38px] px-4 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            <span>Cetak Tanda Tangan</span>
                        </button>
                    </div>
                @else
                    <form action="/sfa/uploadsignature" id="frmSignature" method="POST" class="space-y-2.5 no-auto-loading">
                        @csrf
                        <input type="hidden" value="{{ $penjualan->no_faktur }}" name="no_faktur">
                        <div class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-950 overflow-hidden">
                            <div id="sign"></div>
                        </div>
                        <textarea style="display: none" name="signed" id="signature" cols="30" rows="10"></textarea>

                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" id="clear"
                                class="h-[38px] rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold inline-flex items-center justify-center gap-1.5 transition cursor-pointer">
                                <i data-lucide="eraser" class="w-3.5 h-3.5"></i>
                                <span>Clear</span>
                            </button>
                            <button type="submit" id="save"
                                class="h-[38px] rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs inline-flex items-center justify-center gap-1.5 transition cursor-pointer">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                <span>Simpan TTD</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- DETAIL, BAYAR, TRANSFER, GIRO SWIPE SLIDER CARD -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="p-2.5 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-lg">
                    <button type="button" data-index="0" data-target="#pane-detail"
                        class="sfa-faktur-tab-btn active flex-1 h-[32px] px-2 rounded-md text-[11px] sm:text-xs font-bold bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 shadow-xs transition cursor-pointer text-center">
                        Detail
                    </button>
                    <button type="button" data-index="1" data-target="#pane-bayar"
                        class="sfa-faktur-tab-btn flex-1 h-[32px] px-2 rounded-md text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition cursor-pointer text-center">
                        Bayar
                    </button>
                    <button type="button" data-index="2" data-target="#pane-transfer"
                        class="sfa-faktur-tab-btn flex-1 h-[32px] px-2 rounded-md text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition cursor-pointer text-center">
                        Transfer
                    </button>
                    <button type="button" data-index="3" data-target="#pane-giro"
                        class="sfa-faktur-tab-btn flex-1 h-[32px] px-2 rounded-md text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition cursor-pointer text-center">
                        Giro
                    </button>
                </div>
            </div>

            <div id="sfaTabViewport" class="overflow-hidden">
                <div id="sfaTabTrack" class="flex transition-transform duration-300 ease-out" style="transform: translateX(0%);">
                <!-- PANE 1: DETAIL PENJUALAN -->
                <div id="pane-detail" class="sfa-faktur-tab-pane w-full shrink-0 p-3.5 space-y-3">
                    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 overflow-hidden divide-y divide-slate-100 dark:divide-slate-800/80">
                        @php $subtotal = 0; @endphp
                        @foreach ($detail as $d)
                            @php
                                $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                                $jumlah_dus = $jumlah[0];
                                $jumlah_pack = $jumlah[1];
                                $jumlah_pcs = $jumlah[2];
                                $subtotal += $d->subtotal;
                                $subtotal_dus = $jumlah_dus * $d->harga_dus;
                                $subtotal_pack = $jumlah_pack * $d->harga_pack;
                                $subtotal_pcs = $jumlah_pcs * $d->harga_pcs;
                                $isPromo = $d->status_promosi == '1';
                            @endphp
                            <div class="p-2.5 {{ $isPromo ? 'bg-amber-50/70 dark:bg-amber-950/30' : 'bg-white dark:bg-slate-900' }}">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">
                                        {{ $d->kode_produk }} - {{ $d->nama_produk }}
                                    </span>
                                    @if ($isPromo)
                                        <span class="px-1.5 py-0.5 rounded bg-amber-200 text-amber-900 text-[10px] font-bold shrink-0">PROMO</span>
                                    @endif
                                </div>
                                <div class="space-y-0.5 text-[11px] text-slate-500 dark:text-slate-400 font-mono-num">
                                    @if (!empty($jumlah_dus))
                                        <div class="flex items-center justify-between">
                                            <span>{{ formatAngka($jumlah_dus) }} Dus &times; {{ formatAngka($d->harga_dus) }}</span>
                                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ formatAngka($subtotal_dus) }}</span>
                                        </div>
                                    @endif
                                    @if (!empty($jumlah_pack))
                                        <div class="flex items-center justify-between">
                                            <span>{{ formatAngka($jumlah_pack) }} Pack &times; {{ formatAngka($d->harga_pack) }}</span>
                                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ formatAngka($subtotal_pack) }}</span>
                                        </div>
                                    @endif
                                    @if (!empty($jumlah_pcs))
                                        <div class="flex items-center justify-between">
                                            <span>{{ formatAngka($jumlah_pcs) }} Pcs &times; {{ formatAngka($d->harga_pcs) }}</span>
                                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ formatAngka($subtotal_pcs) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- RINGKASAN NOMINAL -->
                    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/50 p-3 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Subtotal</span>
                            <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">{{ formatAngka($subtotal) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Potongan</span>
                            <span class="font-bold font-mono-num text-rose-600 dark:text-rose-400">- {{ formatAngka($penjualan->potongan) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Pot. Istimewa</span>
                            <span class="font-bold font-mono-num text-rose-600 dark:text-rose-400">- {{ formatAngka($penjualan->potongan_istimewa) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Penyesuaian</span>
                            <span class="font-bold font-mono-num text-rose-600 dark:text-rose-400">- {{ formatAngka($penjualan->penyesuaian) }}</span>
                        </div>
                        @if (!empty($penjualan->ppn))
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 dark:text-slate-400 font-semibold">PPN</span>
                                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">{{ formatAngka($penjualan->ppn) }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Retur</span>
                            <span class="font-bold font-mono-num text-rose-600 dark:text-rose-400">- {{ formatAngka($penjualan->total_retur) }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                            <span class="font-black text-slate-800 dark:text-white uppercase">Grand Total</span>
                            <span class="text-sm font-black font-mono-num text-[#284c9a] dark:text-blue-400">{{ formatAngka($total_netto) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Jumlah Bayar</span>
                            <span class="font-bold font-mono-num text-emerald-600 dark:text-emerald-400">{{ formatAngka($penjualan->total_bayar) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-semibold">Sisa Bayar</span>
                            <span id="sisabayar" class="font-black font-mono-num text-rose-600 dark:text-rose-400">{{ formatAngka($sisa_bayar) }}</span>
                        </div>
                    </div>
                </div>

                <!-- PANE 2: PEMBAYARAN -->
                <div id="pane-bayar" class="sfa-faktur-tab-pane w-full shrink-0 p-3.5 space-y-3">
                    <button type="button" id="btnCreateBayar"
                        class="w-full h-[40px] sm:h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Input Pembayaran</span>
                    </button>

                    @if ($historibayar->isEmpty())
                        <div class="py-8 text-center text-xs text-slate-400">Belum ada histori pembayaran.</div>
                    @else
                        <div class="space-y-2">
                            @foreach ($historibayar as $d)
                                <div class="p-3 rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase font-mono-num block">
                                            {{ $d->no_bukti }} &bull; {{ $jenis_bayar[$d->jenis_bayar] ?? $d->jenis_bayar }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 block mt-0.5">{{ DateToIndo($d->tanggal) }}</span>
                                        <span class="text-sm font-black text-slate-800 dark:text-slate-100 font-mono-num block mt-1">
                                            Rp {{ formatAngka($d->jumlah) }}
                                        </span>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ $d->nama_salesman }}</span>
                                    </div>

                                    <div class="flex items-center gap-1 shrink-0 aksihistoribayar">
                                        @if ($d->tanggal >= date('Y-m-d') && $d->print_tagihan == 0)
                                            @if (($d->jenis_bayar == 'TP' && $penjualan->jenis_bayar != 'TN') || ($d->jenis_bayar == 'TN' && $penjualan->jenis_bayar != 'TN'))
                                                <button type="button" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}"
                                                    class="btnEditBayar w-7 h-7 rounded-md bg-blue-50 hover:bg-blue-100 text-[#284c9a] inline-flex items-center justify-center transition cursor-pointer">
                                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                                </button>
                                                <form method="POST" name="deleteform" class="deleteform inline-flex m-0 p-0"
                                                    action="{{ route('pembayaranpenjualan.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="delete-confirm w-7 h-7 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 inline-flex items-center justify-center transition cursor-pointer">
                                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- PANE 3: TRANSFER -->
                <div id="pane-transfer" class="sfa-faktur-tab-pane w-full shrink-0 p-3.5 space-y-3">
                    <button type="button" id="btnCreatetransfer"
                        class="w-full h-[40px] sm:h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Input Transfer</span>
                    </button>

                    @if ($transfer->isEmpty())
                        <div class="py-8 text-center text-xs text-slate-400">Belum ada data pembayaran transfer.</div>
                    @else
                        <div class="space-y-2">
                            @foreach ($transfer as $d)
                                @php
                                    $status_transfer = $d->status == '0' ? 'Pending' : ($d->status == '1' ? 'Diterima' : 'Ditolak');
                                    $badge_transfer = $d->status == '0' ? 'bg-amber-100 text-amber-700' : ($d->status == '1' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700');
                                @endphp
                                <div class="p-3 rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[11px] font-bold text-[#284c9a] dark:text-blue-400 font-mono-num">{{ $d->kode_transfer }}</span>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $badge_transfer }}">{{ $status_transfer }}</span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 block mt-0.5">{{ DateToIndo($d->tanggal) }}</span>
                                        <span class="text-sm font-black text-slate-800 dark:text-slate-100 font-mono-num block mt-1">
                                            Rp {{ formatAngka($d->jumlah) }}
                                        </span>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ $d->bank_pengirim }}</span>
                                    </div>

                                    <div class="flex items-center gap-1 shrink-0">
                                        @if ($d->status == '0')
                                            <button type="button" kode_transfer="{{ Crypt::encrypt($d->kode_transfer) }}"
                                                class="btnEdittransfer w-7 h-7 rounded-md bg-blue-50 hover:bg-blue-100 text-[#284c9a] inline-flex items-center justify-center transition cursor-pointer">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form method="POST" name="deleteform" class="deleteform inline-flex m-0 p-0"
                                                action="{{ route('pembayarantransfer.delete', [Crypt::encrypt($d->no_faktur), Crypt::encrypt($d->kode_transfer)]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="delete-confirm w-7 h-7 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 inline-flex items-center justify-center transition cursor-pointer">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">Keuangan</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- PANE 4: GIRO -->
                <div id="pane-giro" class="sfa-faktur-tab-pane w-full shrink-0 p-3.5 space-y-3">
                    <button type="button" id="btnCreategiro"
                        class="w-full h-[40px] sm:h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Input Giro</span>
                    </button>

                    @if ($giro->isEmpty())
                        <div class="py-8 text-center text-xs text-slate-400">Belum ada data pembayaran giro.</div>
                    @else
                        <div class="space-y-2">
                            @foreach ($giro as $d)
                                @php
                                    $status_giro = $d->status == '0' ? 'Pending' : ($d->status == '1' ? 'Diterima' : 'Ditolak');
                                    $badge_giro = $d->status == '0' ? 'bg-amber-100 text-amber-700' : ($d->status == '1' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700');
                                @endphp
                                <div class="p-3 rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[11px] font-bold text-[#284c9a] dark:text-blue-400 font-mono-num">{{ $d->no_giro }}</span>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $badge_giro }}">{{ $status_giro }}</span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 block mt-0.5">{{ DateToIndo($d->tanggal) }}</span>
                                        <span class="text-sm font-black text-slate-800 dark:text-slate-100 font-mono-num block mt-1">
                                            Rp {{ formatAngka($d->jumlah) }}
                                        </span>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ $d->bank_pengirim }}</span>
                                        @if (!empty($d->foto) && Storage::disk('public')->exists('/giro/' . $d->foto))
                                            <a href="{{ getfotoGiro($d->foto) }}" target="_blank"
                                                class="mt-1.5 inline-flex items-center gap-1 px-2 py-0.5 rounded bg-sky-50 text-sky-700 text-[10.5px] font-bold">
                                                <i data-lucide="image" class="w-3 h-3"></i>
                                                <span>Lihat Dokumen</span>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1 shrink-0">
                                        @if ($d->status == '0')
                                            <button type="button" kode_giro="{{ Crypt::encrypt($d->kode_giro) }}"
                                                class="btnEditgiro w-7 h-7 rounded-md bg-blue-50 hover:bg-blue-100 text-[#284c9a] inline-flex items-center justify-center transition cursor-pointer">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form method="POST" name="deleteform" class="deleteform inline-flex m-0 p-0"
                                                action="{{ route('pembayarangiro.delete', [Crypt::encrypt($d->no_faktur), Crypt::encrypt($d->kode_giro)]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="delete-confirm w-7 h-7 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 inline-flex items-center justify-center transition cursor-pointer">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">Keuangan</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- MODAL V2 (BOTTOM SHEET ON MOBILE, STATIC BACKDROP, CLEAN WHITE HEADER) -->
<div id="modal" class="fixed inset-0 z-50 hidden items-end sm:items-center justify-center bg-slate-950/60 backdrop-blur-[2px] p-0 sm:p-4">
    <div class="w-full max-w-xl rounded-t-2xl sm:rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xl overflow-hidden">
        <div class="px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h5 class="modal-title text-sm font-bold text-slate-800 dark:text-slate-100">Form</h5>
            </div>
            <button type="button" class="btn-close-modal-faktur w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div id="loadmodal" class="p-4 max-h-[82vh] overflow-y-auto"></div>
    </div>
</div>
@endsection

@push('myscript')
<script src="{{ asset('assets/vendor/libs/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/jqueryui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('assets/vendor/js/signature/signature.js') }}"></script>
<script>
    let jmlprint = "{{ $penjualan->print }}";
    let jmlprinttagihan = "{{ $print_tagihan }}";
    let jmlprintgiro = "{{ $print_giro }}";
    let jmlprinttransfer = "{{ $print_transfer }}";
    let lock_print = "{{ $penjualan->lock_print }}";

    function ajax_print(url, btn) {
        if (jmlprint >= 1 && jmlprinttagihan <= 0 && jmlprintgiro <= 0 && jmlprinttransfer <= 0 && lock_print == 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Faktur Sudah Tidak Bisa Di Cetak, Hubungi OM !'
            });
            return false;
        }

        const $b = $(btn);
        const origHtml = $b.html();
        $b.prop('disabled', true).text('Memproses...');
        $.get(url, function(data) {
            window.location.href = data;
            document.querySelectorAll('.aksihistoribayar').forEach(function(element) {
                element.style.display = 'none';
            });
            jmlprint++;
            jmlprinttagihan--;
            jmlprintgiro--;
            jmlprinttransfer--;
            lock_print = 0;
        }).fail(function() {
            alert('ajax error');
        }).always(function() {
            $b.prop('disabled', false).html(origHtml);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    }

    function sendUrlToPrint(url) {
        const beforeUrl = 'intent:';
        const afterUrl = '#Intent;component=ru.a402d.rawbtprinter.activity.PrintDownloadActivity;package=ru.a402d.rawbtprinter;end;';
        document.location = beforeUrl + encodeURI(url) + afterUrl;
        return false;
    }

    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Swipeable Tab Slider
        let activeTabIndex = 0;
        const totalTabs = $('.sfa-faktur-tab-btn').length;

        function goToTab(index) {
            if (index < 0) index = 0;
            if (index >= totalTabs) index = totalTabs - 1;
            activeTabIndex = index;

            const $btn = $(`.sfa-faktur-tab-btn[data-index="${index}"]`);
            $('.sfa-faktur-tab-btn')
                .removeClass('active bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 shadow-xs')
                .addClass('text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200');
            $btn
                .addClass('active bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 shadow-xs')
                .removeClass('text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200');

            $('#sfaTabTrack').css('transform', `translateX(-${index * 100}%)`);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $('.sfa-faktur-tab-btn').on('click', function() {
            goToTab(parseInt($(this).data('index')));
        });

        let touchStartX = 0;
        let touchStartY = 0;
        const viewportEl = document.getElementById('sfaTabViewport');
        if (viewportEl) {
            viewportEl.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                touchStartY = e.changedTouches[0].screenY;
            }, { passive: true });

            viewportEl.addEventListener('touchend', function(e) {
                const dx = e.changedTouches[0].screenX - touchStartX;
                const dy = e.changedTouches[0].screenY - touchStartY;
                if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) {
                    if (dx < 0 && activeTabIndex < totalTabs - 1) {
                        goToTab(activeTabIndex + 1);
                    } else if (dx > 0 && activeTabIndex > 0) {
                        goToTab(activeTabIndex - 1);
                    }
                }
            }, { passive: true });
        }

        // Signature Pad
        if ($('#sign').length && typeof $.fn.signature !== 'undefined') {
            const sign = $('#sign').signature({
                syncField: '#signature',
                syncFormat: 'PNG'
            });
            $('#clear').on('click', function(e) {
                e.preventDefault();
                sign.signature('clear');
                $('#signature').val('');
            });
        }

        $('#frmSignature').on('submit', function() {
            if ($('#signature').val() == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Tanda Tangan Tidak Boleh Kosong !'
                });
                return false;
            }
        });

        // Modal Helpers
        function openModalFaktur(title, url) {
            $('#modal .modal-title').text(title);
            $('#loadmodal').html('<div class="py-8 text-center text-xs font-semibold text-slate-400">Memuat formulir...</div>');
            $('#modal').removeClass('hidden').addClass('flex');
            $('#loadmodal').load(url, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        }

        $(document).on('click', '.btn-close-modal-faktur', function() {
            $('#modal').addClass('hidden').removeClass('flex');
        });

        $('#btnCreateBayar').on('click', function(e) {
            e.preventDefault();
            openModalFaktur('Input Pembayaran', `/pembayaranpenjualan/{{ Crypt::encrypt($penjualan->no_faktur) }}/create`);
        });

        $('.btnEditBayar').on('click', function(e) {
            e.preventDefault();
            openModalFaktur('Edit Pembayaran', `/pembayaranpenjualan/${$(this).attr('no_bukti')}/edit`);
        });

        $('#btnCreatetransfer').on('click', function(e) {
            e.preventDefault();
            openModalFaktur('Input Transfer', `/pembayarantransfer/{{ Crypt::encrypt($penjualan->no_faktur) }}/create`);
        });

        $('.btnEdittransfer').on('click', function(e) {
            e.preventDefault();
            openModalFaktur('Edit Transfer', `/pembayarantransfer/{{ Crypt::encrypt($penjualan->no_faktur) }}/${$(this).attr('kode_transfer')}/edit`);
        });

        $('#btnCreategiro').on('click', function(e) {
            e.preventDefault();
            openModalFaktur('Input Giro', `/pembayarangiro/{{ Crypt::encrypt($penjualan->no_faktur) }}/create`);
        });

        $('.btnEditgiro').on('click', function(e) {
            e.preventDefault();
            openModalFaktur('Edit Giro', `/pembayarangiro/{{ Crypt::encrypt($penjualan->no_faktur) }}/${$(this).attr('kode_giro')}/edit`);
        });

        $('.ubahfakturbatal').on('click', function(e) {
            e.preventDefault();
            openModalFaktur('Ubah Faktur Batal', `/sfa/penjualan/{{ Crypt::encrypt($penjualan->no_faktur) }}/ubahfakturbatal`);
        });
    });
</script>
@endpush
