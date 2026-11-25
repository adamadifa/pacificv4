@extends('layouts.app')
@section('titlepage', 'Detail Retur')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('sfa.pelanggan') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Pelanggan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($retur->kode_pelanggan)) }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">{{ $retur->kode_pelanggan }}</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">{{ $retur->no_retur }}</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 sm:space-y-4">
    <!-- TOP BAR -->
    <div class="flex items-center justify-between gap-2">
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($retur->kode_pelanggan)) }}"
            class="h-[36px] sm:h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1.5 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>

        <span class="px-2.5 py-1 rounded-md text-[10.5px] font-bold {{ $retur->jenis_retur == 'PF' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300' }}">
            {{ $retur->jenis_retur == 'PF' ? 'POTONG FAKTUR' : 'GANTI BARANG' }}
        </span>
    </div>

    <!-- HERO RETUR CARD -->
    <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
        <div class="relative h-36 sm:h-44 w-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
            @if (Storage::disk('public')->exists('/penjualan/' . $retur->foto) && !empty($retur->foto))
                <img src="{{ getfotoPenjualan($retur->foto) }}" alt="Foto Retur" id="foto" class="w-full h-full object-cover">
            @else
                <img src="{{ asset('assets/img/elements/1.jpg') }}" alt="Foto Retur" id="foto" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/45 to-transparent"></div>
            <div class="absolute bottom-3 left-3.5 right-3.5 text-white">
                <div class="flex items-center justify-between gap-2">
                    <span class="px-2 py-0.5 rounded-md bg-white/20 backdrop-blur-xs text-[10.5px] font-bold font-mono-num">
                        {{ $retur->no_retur }}
                    </span>
                    <span class="text-[11px] text-white/80 font-medium">{{ DateToIndo($retur->tanggal) }}</span>
                </div>
                <h4 class="text-sm sm:text-base font-black leading-tight mt-1 truncate">
                    {{ $retur->kode_pelanggan }} &bull; {{ textUpperCase($retur->nama_pelanggan) }}
                </h4>
            </div>
        </div>

        <!-- METRIC STRIP -->
        <div class="grid grid-cols-2 divide-x divide-slate-200/80 dark:divide-slate-800 bg-slate-50/70 dark:bg-slate-900/60 border-t border-slate-200/80 dark:border-slate-800">
            <div class="p-3 min-w-0">
                <span class="block text-[9.5px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">No. Faktur</span>
                <span class="block text-xs sm:text-sm font-black text-slate-800 dark:text-slate-100 font-mono-num mt-0.5 truncate">{{ $retur->no_faktur }}</span>
            </div>
            <div class="p-3 min-w-0">
                <span class="block text-[9.5px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">Total Retur</span>
                <span class="block text-xs sm:text-sm font-black text-rose-600 dark:text-rose-400 font-mono-num mt-0.5 truncate">Rp {{ formatAngka($retur->total_retur) }}</span>
            </div>
        </div>
    </div>

    @if ($retur->tanggal == date('Y-m-d'))
        <form method="POST" class="deleteform m-0 p-0" action="/retur/{{ Crypt::encrypt($retur->no_retur) }}/delete">
            @csrf
            @method('DELETE')
            <button type="button"
                class="delete-confirm w-full h-[40px] sm:h-[38px] px-4 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span>Hapus Retur</span>
            </button>
        </form>
    @endif

    <!-- DETAIL BARANG RETUR -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-2">
            <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
            </div>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Detail Produk Retur</span>
        </div>

        <div class="p-3.5 space-y-3">
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

            <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/50 p-3 flex items-center justify-between">
                <span class="text-xs font-black text-slate-800 dark:text-white uppercase">Total Retur</span>
                <span class="text-sm font-black font-mono-num text-[#284c9a] dark:text-blue-400">Rp {{ formatAngka($subtotal) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
