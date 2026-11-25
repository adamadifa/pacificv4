@extends('v2.layouts.app')
@section('titlepage', 'Detail BPPB')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Pembelian</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('bpbpembelian.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">BPPB</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Detail BPPB</span>
    </div>
@endsection

@section('content')
@php
    $totalJumlah = 0;
    $totalDiserahkan = 0;
    $totalSisa = 0;
    foreach ($detail as $d) {
        $sudahDiserahkan = $diserahkanTotal[$d->kode_barang] ?? 0;
        $sisa = $d->jumlah - $sudahDiserahkan;
        $totalJumlah += $d->jumlah;
        $totalDiserahkan += $sudahDiserahkan;
        $totalSisa += $sisa;
    }

    $percentComplete = $totalJumlah > 0 ? round(($totalDiserahkan / $totalJumlah) * 100) : 0;

    if ($totalSisa <= 0) {
        $statusBadge = 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800';
        $statusDot = 'bg-emerald-500';
        $statusText = 'Selesai';
    } elseif ($totalDiserahkan > 0) {
        $statusBadge = 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800';
        $statusDot = 'bg-amber-500';
        $statusText = 'Sebagian';
    } else {
        $statusBadge = 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700';
        $statusDot = 'bg-slate-400';
        $statusText = 'Belum Diserahkan';
    }
@endphp

<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Detail BPPB</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">
                Detail pengajuan Bukti Permintaan &amp; Penerimaan Barang (BPPB) Pembelian nomor 
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $bpb->no_bpb }}</span>.
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if (Auth::user()->id == '54' && $bpb->approve_gudang == '1')
                <a href="{{ route('bpbpembelian.cetak', Crypt::encrypt($bpb->no_bpb)) }}" target="_blank"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
                    <i data-lucide="printer" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>Cetak BPPB</span>
                </a>
            @endif
            <a href="{{ route('bpbpembelian.index') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('bpbpembelian.serahterimabpbstore') }}" method="POST" id="formSerahTerima" autocomplete="off" class="space-y-3.5">
        @csrf
        <input type="hidden" name="kode_dept" value="{{ $bpb->kode_dept }}">
        <input type="hidden" name="kode_cabang" value="{{ $bpb->kode_cabang }}">
        <input type="hidden" name="no_ref" value="{{ $bpb->no_bpb }}">

        <!-- SECTION 1: RINGKASAN INFORMASI & PROGRES PENYERAHAN -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-stretch">
            
            <!-- Card Informasi BPPB (8 COLS) -->
            <div class="lg:col-span-8 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden flex flex-col justify-between">
                <!-- Header Card -->
                <div class="px-3.5 py-2.5 bg-[#284c9a] text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="receipt" class="w-4 h-4 text-blue-200"></i>
                        <span class="text-xs font-bold uppercase tracking-wider text-white">Informasi BPPB</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $statusBadge }} bg-white/95 dark:bg-slate-900/90 shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                        <span>{{ $statusText }}</span>
                    </span>
                </div>

                <!-- Body Card -->
                <div class="p-3.5 space-y-3">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <!-- No. BPPB -->
                        <div>
                            <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">No. BPPB</span>
                            <span class="text-xs font-bold font-mono-num text-[#284c9a] dark:text-blue-400 mt-0.5 block truncate">{{ $bpb->no_bpb }}</span>
                        </div>
                        <!-- Tanggal -->
                        <div>
                            <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Tanggal</span>
                            <span class="text-xs font-bold font-mono-num text-slate-800 dark:text-slate-200 mt-0.5 block truncate">{{ DateToIndo($bpb->tanggal) }}</span>
                        </div>
                        <!-- Departemen -->
                        <div>
                            <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Departemen</span>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate">{{ $bpb->nama_dept ?: '-' }}</span>
                        </div>
                        <!-- Cabang -->
                        <div>
                            <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Cabang</span>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate">{{ $bpb->nama_cabang ?: '-' }}</span>
                        </div>
                    </div>

                    <!-- User Input -->
                    <div class="flex items-center gap-1.5 pt-2 border-t border-slate-100 dark:border-slate-800 text-[11px] text-slate-500 dark:text-slate-400">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                        <span>Diajukan oleh: <strong class="text-slate-700 dark:text-slate-200">{{ $bpb->nama_user ?: '-' }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Card Progres Penyerahan (4 COLS) -->
            <div class="lg:col-span-4 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden flex flex-col justify-between">
                <!-- Header Card -->
                <div class="px-3.5 py-2.5 bg-[#284c9a] text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="pie-chart" class="w-4 h-4 text-blue-200"></i>
                        <span class="text-xs font-bold uppercase tracking-wider text-white">Progres Penyerahan</span>
                    </div>
                    <span class="text-xs font-bold font-mono-num text-white">{{ $percentComplete }}%</span>
                </div>

                <!-- Body Card -->
                <div class="p-3.5 space-y-3 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-semibold text-slate-600 dark:text-slate-300 mb-1.5">
                            <span>Status Pemenuhan</span>
                            <span class="font-bold font-mono-num {{ $percentComplete == 100 ? 'text-emerald-600' : 'text-[#284c9a] dark:text-blue-400' }}">
                                {{ $totalDiserahkan }} / {{ $totalJumlah }} Item
                            </span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $percentComplete }}%"></div>
                        </div>
                    </div>

                    <!-- 3 Metrik -->
                    <div class="grid grid-cols-3 gap-2 text-center pt-2 border-t border-slate-100 dark:border-slate-800">
                        <div class="p-2 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Total Order</span>
                            <span class="text-xs font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ formatAngkaDesimal($totalJumlah) }}</span>
                        </div>
                        <div class="p-2 rounded-lg bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40">
                            <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase">Diserahkan</span>
                            <span class="text-xs font-bold font-mono-num text-emerald-600 dark:text-emerald-400">{{ formatAngkaDesimal($totalDiserahkan) }}</span>
                        </div>
                        <div class="p-2 rounded-lg bg-rose-50/50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/40">
                            <span class="block text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase">Sisa</span>
                            <span class="text-xs font-bold font-mono-num text-rose-600 dark:text-rose-400">{{ formatAngkaDesimal($totalSisa) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: DETAIL BARANG PERMINTAAN TABLE (KOMPAK ~36px) -->
        <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
            <div class="overflow-x-auto min-h-[200px]">
                <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                    <thead>
                        <!-- Baris 1: Strip Header Pacific Navy -->
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="{{ Auth::user()->id == '54' || Auth::user()->id == '2' ? '9' : '8' }}" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="package" class="w-3.5 h-3.5 text-blue-200"></i>
                                        <span class="text-xs font-bold text-white uppercase tracking-wider">Detail Barang Permintaan</span>
                                    </div>
                                    <span class="text-[10.5px] text-white/70 font-mono-num">
                                        Total: {{ count($detail) }} Item
                                    </span>
                                </div>
                            </th>
                        </tr>
                        <!-- Baris 2: Kolom Header -->
                        <tr class="bg-[#1e3a78] text-white">
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center border-r border-[#284c9a]/40" style="width: 4%">NO</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40" style="width: 12%">KODE</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40">NAMA BARANG</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right border-r border-[#284c9a]/40" style="width: 10%">JUMLAH</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right border-r border-[#284c9a]/40" style="width: 10%">DITERIMA</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right border-r border-[#284c9a]/40" style="width: 10%">SISA</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center border-r border-[#284c9a]/40" style="width: 12%">PROGRES</th>
                            @if (Auth::user()->id == '54' || Auth::user()->id == '2')
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right border-r border-[#284c9a]/40" style="width: 12%">SERAHKAN</th>
                            @endif
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90" style="width: 15%">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($detail as $d)
                            @php
                                $sudahDiserahkan = $diserahkanTotal[$d->kode_barang] ?? 0;
                                $sisa = $d->jumlah - $sudahDiserahkan;
                                $itemProgress = $d->jumlah > 0 ? round(($sudahDiserahkan / $d->jumlah) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-1.5 px-3 text-center font-bold text-slate-500 border-r border-slate-100 dark:border-slate-800">{{ $loop->iteration }}</td>
                                <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800">
                                    <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $d->kode_barang }}</span>
                                </td>
                                <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 font-semibold text-slate-800 dark:text-slate-100">
                                    {{ textUpperCase($d->nama_barang) }}
                                    @if ($d->satuan)
                                        <span class="text-slate-400 font-normal">({{ $d->satuan }})</span>
                                    @endif
                                </td>
                                <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                    {{ formatAngkaDesimal($d->jumlah) }}
                                </td>
                                <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ formatAngkaDesimal($sudahDiserahkan) }}
                                </td>
                                <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                                    {{ formatAngkaDesimal($sisa) }}
                                </td>
                                <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-16 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $itemProgress }}%"></div>
                                        </div>
                                        <span class="text-[10.5px] font-mono-num font-bold text-slate-500">{{ $itemProgress }}%</span>
                                    </div>
                                </td>
                                @if (Auth::user()->id == '54' || Auth::user()->id == '2')
                                    <td class="py-1 px-2 border-r border-slate-100 dark:border-slate-800">
                                        <input type="number" name="diserahkan[{{ $d->kode_barang }}]"
                                            class="diserahkan w-full h-[32px] px-2 text-xs font-semibold text-right rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 disabled:opacity-50 disabled:bg-slate-50 dark:disabled:bg-slate-800 disabled:cursor-not-allowed"
                                            data-sisa="{{ $sisa }}" min="0" placeholder="0"
                                            {{ $sisa <= 0 || $bpb->approve_gudang == 0 ? 'readonly' : '' }}>
                                    </td>
                                @endif
                                <td class="py-1.5 px-3 text-slate-500 dark:text-slate-400 text-xs">
                                    {{ $d->keterangan ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECTION 3: FORM SERAH TERIMA (IF PERMITTED & ADA SISA) -->
        @if (Auth::user()->id == '54' || (Auth::user()->id == '2' && $totalSisa > 0))
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 shadow-2xs border-l-4 border-l-emerald-500">
                <div class="flex items-center gap-2 pb-2.5 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <i data-lucide="send" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Form Serah Terima BPPB</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start">
                    <!-- Tanggal Serah Terima -->
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Tanggal Serah Terima <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tanggal_diserahkan" id="tanggal_diserahkan" value="{{ date('Y-m-d') }}" readonly required
                                class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 cursor-pointer">
                        </div>
                    </div>

                    <!-- Pilih Supplier -->
                    <div class="sm:col-span-4 relative select2-container-v2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Pilih Supplier <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_supplier" id="kode_supplier" class="select2KodeSupplier w-full" required>
                                <option value="">Pilih Supplier</option>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->kode_supplier }}">
                                        {{ textUpperCase($s->nama_supplier) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- PPN -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            PPN <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="percent" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="ppn" id="ppn" required
                                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 cursor-pointer">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Simpan Serah Terima (Anti-Shift with Transparent Spacer) -->
                    <div class="sm:col-span-3">
                        <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                        <button type="submit" id="btnSubmitSerahTerima"
                            class="w-full h-[36px] px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold rounded-lg text-xs flex items-center justify-center gap-1.5 transition shadow-xs cursor-pointer">
                            <i data-lucide="save" class="w-3.5 h-3.5"></i>
                            <span>Simpan Serah Terima</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </form>

    <!-- SECTION 4: HISTORY SERAH TERIMA -->
    <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
        <!-- Header Strip Pacific Navy -->
        <div class="px-3.5 py-2.5 bg-[#284c9a] text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="history" class="w-4 h-4 text-blue-200"></i>
                <span class="text-xs font-bold uppercase tracking-wider text-white">History Serah Terima BPPB</span>
            </div>
            <span class="text-[10.5px] text-white/70 font-mono-num">
                Total: {{ count($serahTerima) }} Riwayat
            </span>
        </div>

        <!-- History Cards List -->
        <div class="p-3.5 space-y-3">
            @forelse ($serahTerima as $s)
                @php
                    $isDiterima = $s->diterima == 1;
                    $borderLeftColor = $isDiterima ? 'border-l-emerald-500' : 'border-l-amber-500';
                @endphp
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 overflow-hidden shadow-2xs border-l-4 {{ $borderLeftColor }}">
                    <!-- Card Subheader -->
                    <div class="px-3.5 py-2 bg-slate-100/70 dark:bg-slate-800/80 border-b border-slate-200/80 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <i data-lucide="file-check-2" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                            <span class="text-xs font-bold font-mono-num text-slate-800 dark:text-slate-100">{{ $s->no_bukti }}</span>
                            <span class="text-slate-300 dark:text-slate-600">|</span>
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">{{ DateToIndo($s->tanggal) }}</span>
                        </div>

                        <div>
                            @if (!$isDiterima)
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                        <i data-lucide="clock" class="w-3 h-3 text-amber-500"></i>
                                        <span>Menunggu Konfirmasi</span>
                                    </span>
                                    <select class="terimaSurat h-[28px] px-2 text-xs font-bold rounded-lg border border-[#284c9a] bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 focus:outline-hidden cursor-pointer"
                                        data-no_bukti="{{ $s->no_bukti }}">
                                        <option value="">Pilih Status</option>
                                        <option value="1">Terima</option>
                                    </select>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                    <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-500"></i>
                                    <span>Sudah Diterima</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Details Table inside History Card (~36px) -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                            <thead class="bg-slate-200/60 dark:bg-slate-800">
                                <tr>
                                    <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-center border-r border-slate-200 dark:border-slate-700" style="width: 4%">NO</th>
                                    <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 border-r border-slate-200 dark:border-slate-700" style="width: 12%">KODE</th>
                                    <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 border-r border-slate-200 dark:border-slate-700">NAMA BARANG</th>
                                    <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-right border-r border-slate-200 dark:border-slate-700" style="width: 15%">JUMLAH</th>
                                    <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 border-r border-slate-200 dark:border-slate-700" style="width: 10%">SATUAN</th>
                                    <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 border-r border-slate-200 dark:border-slate-700">KETERANGAN</th>
                                    @if (!$isDiterima)
                                        <th class="py-1.5 px-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-center" style="width: 8%">AKSI</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                                @foreach ($historyDetail[$s->no_bukti] ?? [] as $h)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                        <td class="py-1.5 px-3 text-center font-bold text-slate-500 border-r border-slate-100 dark:border-slate-800">{{ $loop->iteration }}</td>
                                        <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                            {{ $h->kode_barang }}
                                        </td>
                                        <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 font-semibold text-slate-800 dark:text-slate-100">
                                            {{ textUpperCase($h->nama_barang) }}
                                        </td>
                                        <td class="py-1 px-2 border-r border-slate-100 dark:border-slate-800 text-right">
                                            <input type="text"
                                                class="editJumlah w-28 h-[28px] px-2 text-xs font-semibold font-mono-num text-right rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] disabled:opacity-50 disabled:bg-slate-50"
                                                value="{{ $h->jumlah }}"
                                                data-no_bukti="{{ $h->no_bukti }}"
                                                data-kode_barang="{{ $h->kode_barang }}"
                                                {{ $isDiterima || $totalSisa <= 0 ? 'readonly' : '' }}>
                                        </td>
                                        <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 text-slate-500 font-semibold">
                                            {{ $h->satuan }}
                                        </td>
                                        <td class="py-1 px-2 border-r border-slate-100 dark:border-slate-800">
                                            <input type="text"
                                                class="editKeterangan w-full h-[28px] px-2 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] disabled:opacity-50 disabled:bg-slate-50"
                                                value="{{ $h->keterangan }}"
                                                data-no_bukti="{{ $h->no_bukti }}"
                                                data-kode_barang="{{ $h->kode_barang }}"
                                                placeholder="Edit keterangan..."
                                                {{ $isDiterima ? 'readonly' : '' }}>
                                        </td>
                                        @if (!$isDiterima)
                                            <td class="py-1.5 px-3 text-center">
                                                <button type="button"
                                                    class="deleteHistoryBtn w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/50 inline-flex items-center justify-center transition cursor-pointer"
                                                    data-no_bukti="{{ $h->no_bukti }}"
                                                    data-kode_barang="{{ $h->kode_barang }}"
                                                    title="Hapus Data">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="py-10 text-center border border-dashed border-slate-200 dark:border-slate-800 rounded-xl bg-slate-50/50 dark:bg-slate-850">
                    <i data-lucide="history" class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2"></i>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500">Belum ada history serah terima untuk BPPB ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Flatpickr
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Select2 Supplier
        const $select2Supplier = $('.select2KodeSupplier');
        if ($select2Supplier.length && $.fn.select2) {
            $select2Supplier.select2({
                placeholder: 'Pilih Supplier',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        // Inline Edit Jumlah
        $(".editJumlah").on("change", function() {
            let jumlah = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpbpembelian.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                kode_barang: kode_barang,
                jumlah: jumlah
            }, function(resp) {
                Swal.fire({
                    icon: "success",
                    title: "Jumlah Diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });
            }).fail(function() {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Memperbarui Jumlah",
                    timer: 1200,
                    showConfirmButton: false
                });
            });
        });

        // Inline Edit Keterangan
        $(".editKeterangan").on("change", function() {
            let keterangan = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpbpembelian.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                kode_barang: kode_barang,
                keterangan: keterangan
            }, function(resp) {
                Swal.fire({
                    icon: "success",
                    title: "Keterangan Diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });
            }).fail(function() {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Memperbarui Keterangan",
                    timer: 1200,
                    showConfirmButton: false
                });
            });
        });

        // Status Terima Surat
        $(".terimaSurat").on("change", function() {
            let no_bukti = $(this).data("no_bukti");

            $.post("{{ route('bpbpembelian.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                diterima: 1
            }, function(resp) {
                Swal.fire({
                    icon: "success",
                    title: "Status Serah Terima Diperbarui",
                    timer: 900,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            }).fail(function() {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Mengubah Status",
                    timer: 1200,
                    showConfirmButton: false
                });
            });
        });

        // Delete History
        $(".deleteHistoryBtn").on("click", function(e) {
            e.preventDefault();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            Swal.fire({
                title: "Hapus Data Serah Terima?",
                text: "Data yang dihapus tidak dapat dipulihkan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#284c9a",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('bpbpembelian.deleteSerahTerima') }}", {
                        _token: "{{ csrf_token() }}",
                        no_bukti: no_bukti,
                        kode_barang: kode_barang
                    }, function(res) {
                        location.reload();
                    }).fail(function() {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal Menghapus Data",
                            timer: 1200,
                            showConfirmButton: false
                        });
                    });
                }
            });
        });

        // Validasi Jumlah Diserahkan
        $(".diserahkan").on("input", function() {
            let row = $(this).closest("tr");
            let sisa = parseFloat($(this).data("sisa")) || 0;
            let val = parseFloat($(this).val()) || 0;

            if (val > sisa) {
                Swal.fire({
                    icon: "error",
                    title: "Jumlah Tidak Valid!",
                    text: `Jumlah diserahkan (${val}) melebihi sisa (${sisa})`,
                    confirmButtonColor: "#284c9a"
                });

                $(this).val(sisa);
                $(this).addClass("!border-rose-500 !ring-1 !ring-rose-500");
                row.addClass("bg-rose-50/50 dark:bg-rose-950/20");
            } else {
                $(this).removeClass("!border-rose-500 !ring-1 !ring-rose-500");
                row.removeClass("bg-rose-50/50 dark:bg-rose-950/20");
            }
        });

        // Anti-double submit form serah terima
        $("#formSerahTerima").on("submit", function() {
            const $btn = $("#btnSubmitSerahTerima");
            $btn.prop("disabled", true).addClass("opacity-75 cursor-not-allowed pointer-events-none");
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
            return true;
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
