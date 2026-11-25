@extends('layouts.app')
@section('titlepage', 'Input Penjualan SFA')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('sfa.pelanggan') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Pelanggan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($kode_pelanggan)) }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">{{ $kode_pelanggan }}</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Input Penjualan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 pb-20 lg:pb-0">

    <!-- PAGE HEADER -->
    <div class="flex flex-wrap items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Input Penjualan SFA</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Buat faktur penjualan baru untuk outlet pelanggan yang sedang check-in.</p>
        </div>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($kode_pelanggan)) }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1.5 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('penjualan.store') }}" method="POST" id="formPenjualan" class="space-y-4">
        @csrf
        <input type="hidden" name="limit_pelanggan" id="limit_pelanggan">
        <input type="hidden" name="sisa_piutang" id="sisa_piutang">
        <input type="hidden" name="siklus_pembayaran" id="siklus_pembayaran">
        <input type="hidden" name="max_kredit" id="max_kredit">
        <input type="hidden" name="status_pajak_faktur" id="status_pajak_faktur_input" value="0">

        @if (!empty($piutang) && $piutang->count() > 0)
            <div class="rounded-xl border border-rose-200 dark:border-rose-900/70 bg-rose-50/70 dark:bg-rose-950/30 overflow-hidden">
                <div class="px-3.5 py-2.5 border-b border-rose-200/80 dark:border-rose-900/60 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0"></i>
                    <span class="text-xs font-bold text-rose-800 dark:text-rose-200">
                        Pelanggan memiliki {{ $piutang->count() }} Faktur Belum Lunas
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs whitespace-nowrap">
                        <thead>
                            <tr class="bg-rose-100/70 dark:bg-rose-900/40 text-rose-900 dark:text-rose-200">
                                <th class="py-2 px-3 text-left text-[11px] font-bold uppercase tracking-wider">No. Faktur</th>
                                <th class="py-2 px-3 text-left text-[11px] font-bold uppercase tracking-wider">Tanggal</th>
                                <th class="py-2 px-3 text-right text-[11px] font-bold uppercase tracking-wider">Total Piutang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-200/60 dark:divide-rose-900/50">
                            @foreach ($piutang as $d)
                                @php
                                    $saldo_piutang = $d->total_piutang - $d->jmlbayar;
                                @endphp
                                <tr class="text-rose-900 dark:text-rose-100">
                                    <td class="py-1.5 px-3 font-mono-num font-bold">{{ $d->no_faktur }}</td>
                                    <td class="py-1.5 px-3">{{ $d->tanggal }}</td>
                                    <td class="py-1.5 px-3 text-right font-mono-num font-bold">{{ formatAngka($saldo_piutang) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- GRAND TOTAL & CUSTOMER SUMMARY STRIP -->
        <div class="rounded-xl overflow-hidden shadow-xs bg-[#284c9a] text-white relative">
            <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>
            <div class="relative z-10 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0">
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-white/70">Grand Total Penjualan</span>
                        <div class="text-2xl sm:text-3xl font-black font-mono-num tracking-tight leading-none mt-1">
                            Rp <span id="grandtotal_text">0</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 sm:gap-5 border-t sm:border-t-0 sm:border-l border-white/15 pt-3 sm:pt-0 sm:pl-5">
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-white/65">Limit</span>
                        <span id="limit_pelanggan_text" class="block text-xs sm:text-sm font-black font-mono-num mt-0.5">0</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-white/65">Piutang</span>
                        <span id="sisa_piutang_text" class="block text-xs sm:text-sm font-black font-mono-num mt-0.5">0</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-white/65">Faktur Kredit</span>
                        <span id="jmlfaktur_kredit" class="block text-xs sm:text-sm font-black font-mono-num mt-0.5">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

            <!-- LEFT: INFORMASI FAKTUR -->
            <div class="lg:col-span-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Faktur</span>
                </div>

                <div class="p-3.5 space-y-2">
                    <div class="grid grid-cols-2 lg:grid-cols-1 gap-2 items-start">
                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="barcode" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="no_faktur" id="no_faktur" placeholder="No. Faktur"
                                    class="w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium font-mono-num text-slate-700 dark:text-slate-200 read-only:bg-slate-100 dark:read-only:bg-slate-800 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            </div>
                        </div>

                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="tanggal" id="tanggal" readonly value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal"
                                    class="flatpickr-date w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-2 items-start">
                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="store" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="nama_pelanggan" id="nama_pelanggan" readonly placeholder="Nama Pelanggan"
                                    class="w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-medium text-slate-600 dark:text-slate-300">
                            </div>
                            <input type="hidden" id="kode_pelanggan" name="kode_pelanggan" value="{{ $kode_pelanggan }}">
                            <input type="hidden" id="kode_wilayah" name="kode_wilayah">
                        </div>

                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="user-check" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="nama_salesman" id="nama_salesman" readonly placeholder="Nama Salesman"
                                    class="w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-medium text-slate-600 dark:text-slate-300">
                            </div>
                            <input type="hidden" name="kode_salesman" id="kode_salesman">
                        </div>
                    </div>

                    <div class="field-group">
                        <textarea name="keterangan" id="keterangan" rows="2" placeholder="Keterangan (wajib jika kredit & ada piutang)"
                            class="w-full p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
                    </div>
                </div>
            </div>

            <!-- RIGHT: DETAIL PRODUK, POTONGAN, PEMBAYARAN -->
            <div class="lg:col-span-8 space-y-4">

                <!-- DETAIL PRODUK CARD -->
                <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                    <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                                <i data-lucide="package" class="w-3.5 h-3.5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Detail Penjualan Produk</span>
                        </div>

                        <button type="button" id="tambahproduk"
                            class="h-[32px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span>Tambah Produk</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs" id="tabelproduk">
                            <tbody id="loadproduk" class="divide-y divide-slate-100 dark:divide-slate-800/80"></tbody>
                            <tfoot>
                                <tr class="bg-slate-100/90 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-700">
                                    <td class="py-2.5 px-3.5 text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">Subtotal</td>
                                    <td class="py-2.5 px-3.5 text-right text-sm font-black text-slate-900 dark:text-white font-mono-num" id="subtotal">0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="produk_error_container" class="hidden px-3.5 py-2 border-t border-rose-200 dark:border-rose-900/60 bg-rose-50/70 dark:bg-rose-950/30"></div>
                </div>

                <!-- POTONGAN & PEMBAYARAN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">

                    <!-- POTONGAN OTOMATIS -->
                    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                        <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                                <i data-lucide="percent" class="w-3.5 h-3.5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Potongan Otomatis</span>
                        </div>

                        <div class="p-3.5 pt-4 grid grid-cols-2 gap-3">
                            @foreach ([
                                ['id' => 'potongan_aida', 'label' => 'Pot. AIDA'],
                                ['id' => 'potongan_swan', 'label' => 'Pot. SWAN'],
                                ['id' => 'potongan_stick', 'label' => 'Pot. STICK'],
                                ['id' => 'potongan_sambal', 'label' => 'Pot. SAMBAL'],
                            ] as $pot)
                                <div class="relative">
                                    <label for="{{ $pot['id'] }}"
                                        class="absolute left-2.5 -top-2 px-1 rounded bg-white dark:bg-slate-900 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider z-10 pointer-events-none">
                                        {{ $pot['label'] }}
                                    </label>
                                    <input type="text" name="{{ $pot['id'] }}" id="{{ $pot['id'] }}" readonly placeholder="0"
                                        class="money text-right w-full h-[38px] sm:h-[36px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-800/70 text-xs font-bold font-mono-num text-slate-700 dark:text-slate-200 focus:outline-none">
                                </div>
                            @endforeach

                            {{-- Hidden Potongan Istimewa & Penyesuaian inputs for JS parity --}}
                            <div class="hidden">
                                <input type="text" name="potis_aida" id="potis_aida" value="0">
                                <input type="text" name="potis_swan" id="potis_swan" value="0">
                                <input type="text" name="potis_stick" id="potis_stick" value="0">
                                <input type="text" name="peny_aida" id="peny_aida" value="0">
                                <input type="text" name="peny_swan" id="peny_swan" value="0">
                                <input type="text" name="peny_stick" id="peny_stick" value="0">
                            </div>
                        </div>
                    </div>

                    <!-- PEMBAYARAN -->
                    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                        <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Pembayaran</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10.5px] font-bold text-slate-600 dark:text-slate-300 font-mono-num">
                                Voucher: <span id="saldo_voucher_text">0</span>
                            </span>
                        </div>

                        <div class="p-3.5 space-y-2">
                            <div class="field-group">
                                <div class="relative">
                                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                                    </div>
                                    <select name="jenis_transaksi" id="jenis_transaksi"
                                        class="w-full h-[38px] sm:h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                        <option value="">Jenis Transaksi</option>
                                        <option value="T">TUNAI</option>
                                        <option value="K">KREDIT</option>
                                    </select>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="grandtotal" id="grandtotal" disabled placeholder="Grand Total (0)"
                                    class="text-right w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-black font-mono-num text-slate-800 dark:text-white">
                            </div>

                            <div id="jenis_bayar_tunai" class="field-group">
                                <div class="relative">
                                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                        <i data-lucide="banknote" class="w-4 h-4"></i>
                                    </div>
                                    <select name="jenis_bayar" id="jenis_bayar"
                                        class="w-full h-[38px] sm:h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                        <option value="">Jenis Bayar</option>
                                        <option value="TN">CASH</option>
                                        <option value="TR">TRANSFER</option>
                                    </select>
                                </div>
                            </div>

                            <div id="titipan">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                        <i data-lucide="coins" class="w-4 h-4"></i>
                                    </div>
                                    <input type="text" inputmode="numeric" name="titipan" id="titipan_input" placeholder="Titipan"
                                        class="money text-right w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium font-mono-num text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                </div>
                            </div>

                            <div id="voucher_tunai" class="field-group">
                                <div class="relative">
                                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                        <i data-lucide="ticket" class="w-4 h-4"></i>
                                    </div>
                                    <input type="text" inputmode="numeric" name="voucher" id="voucher" placeholder="Voucher"
                                        class="money text-right w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium font-mono-num text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                                <button type="submit" id="btnSimpan"
                                    class="w-full sm:w-auto h-[38px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                    <span>Simpan Penjualan</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

<!-- STICKY MOBILE CHECKOUT BAR -->
<div class="fixed bottom-0 inset-x-0 z-30 lg:hidden bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 px-4 py-2.5 flex items-center justify-between gap-3 shadow-lg">
    <div class="min-w-0">
        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Grand Total</span>
        <span class="block text-base font-black text-[#284c9a] dark:text-blue-400 font-mono-num truncate">
            Rp <span id="grandtotal_mobile_text">0</span>
        </span>
    </div>
    <button type="button" id="btnSimpanMobile"
        class="h-[40px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 shrink-0 cursor-pointer">
        <i data-lucide="send" class="w-3.5 h-3.5"></i>
        <span>Simpan</span>
    </button>
</div>

<!-- MODAL TAMBAH PRODUK V2 (BOTTOM SHEET ON MOBILE, STATIC BACKDROP, CLEAN WHITE HEADER) -->
<div id="modaleditProduk" class="fixed inset-0 z-50 hidden items-end sm:items-center justify-center bg-slate-950/60 backdrop-blur-[2px] p-0 sm:p-4">
    <div class="w-full max-w-lg rounded-t-2xl sm:rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xl overflow-hidden">
        <div class="px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="package-plus" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h5 class="modal-title text-sm font-bold text-slate-800 dark:text-slate-100">Tambah Produk</h5>
            </div>
            <button type="button" class="btn-close-modal-produk w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div id="loadmodaleditProduk" class="p-4 max-h-[82vh] overflow-y-auto"></div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $(".money").maskMoney();
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        let jmlfakturbelumlunas = 0;
        let jmlmaxfaktur = 0;
        let saldo_voucher = 0;
        let kode_cabang_pelanggan = '';
        let kode_pel = '';

        function openModalProduk() {
            $('#modaleditProduk').removeClass('hidden').addClass('flex');
        }

        function closeModalProduk() {
            $('#modaleditProduk').addClass('hidden').removeClass('flex');
        }

        $(document).on('click', '.btn-close-modal-produk', function() {
            closeModalProduk();
        });

        // V2 Inline Form Validator
        function showFieldError($field, message) {
            $field.addClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($field) {
            $field.removeClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500');
            $group.find('.field-error-msg').remove();
        }

        const $formPenjualan = $('#formPenjualan');
        $formPenjualan.on('input change', 'input, textarea, select', function() {
            clearFieldError($(this));
        });

        $(document).on('input keyup change', '#formAddproduk input, #formAddproduk select', function() {
            clearFieldError($(this));
            if ($(this).is('#jml_pack, #jml_pcs')) {
                clearFieldError($('#formAddproduk #jml_dus'));
            }
        });
        $(document).on('select2:select select2:clear', '#formAddproduk select', function() {
            clearFieldError($(this));
        });

        function convertToRupiah(number) {
            if (number) {
                let rupiah = '';
                const numberrev = number.toString().split('').reverse().join('');
                for (let i = 0; i < numberrev.length; i++) {
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + '.';
                }
                return rupiah.split('', rupiah.length - 1).reverse().join('');
            }
            return number;
        }

        function buttonDisable() {
            $('#btnSimpan, #btnSimpanMobile').prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Loading...</span>
            `);
        }

        function buttonEnable() {
            $('#btnSimpan').prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Simpan Penjualan</span>
            `);
            $('#btnSimpanMobile').prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Simpan</span>
            `);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function getPiutang(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPiutangpelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    $('#sisa_piutang_text').text(convertToRupiah(response.data) || '0');
                    $('#sisa_piutang').val(response.data);
                    buttonEnable();
                }
            });
        }

        function getFakturkredit(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getFakturkredit`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    jmlfakturbelumlunas = response.data.unpaid_faktur;
                    jmlmaxfaktur = response.data.jml_faktur;
                    $('#jmlfaktur_kredit').text(response.data.unpaid_faktur);
                    $('#siklus_pembayaran').val(response.data.siklus_pembayaran);
                    $('#max_kredit').val(response.data.jml_faktur);
                    buttonEnable();
                }
            });
        }

        function generatenofaktur() {
            const tanggal = $('#tanggal').val();
            const kode_salesman = $('#kode_salesman').val();
            buttonDisable();
            $.ajax({
                type: 'POST',
                url: '/penjualan/generatenofaktur',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    buttonEnable();
                    if (respond !== '0') {
                        $('#no_faktur').val(respond).prop('readonly', true);
                    }
                }
            });
        }

        function getPelanggan(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    const status_aktif_pelanggan = response.data.status_aktif_pelanggan;
                    if (status_aktif_pelanggan === '0') {
                        buttonEnable();
                        Swal.fire({
                            title: 'Oops!',
                            text: 'Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan !',
                            icon: 'warning',
                            showConfirmButton: true
                        });
                    } else {
                        $('#kode_pelanggan').val(response.data.kode_pelanggan);
                        kode_cabang_pelanggan = response.data.kode_cabang;
                        kode_pel = response.data.kode_pelanggan;
                        $('#nama_pelanggan').val(response.data.nama_pelanggan);
                        $('#limit_pelanggan_text').text(convertToRupiah(response.data.limit_pelanggan) || '0');
                        $('#limit_pelanggan').val(response.data.limit_pelanggan);
                        $('#kode_salesman').val(response.data.kode_salesman);
                        $('#nama_salesman').val(response.data.nama_salesman);
                        $('#kode_wilayah').val(response.data.kode_wilayah);
                        $('#saldo_voucher_text').text(response.saldo_voucher || 0);
                        saldo_voucher = response.saldo_voucher || 0;

                        getPiutang(kode_pelanggan);
                        getFakturkredit(kode_pelanggan);
                        generatenofaktur();
                    }
                }
            });
        }

        getPelanggan("{{ Crypt::encrypt($kode_pelanggan) }}");

        $('#tanggal, #kode_salesman').on('change', function() {
            generatenofaktur();
        });

        function convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah) {
            const jml_dus = Math.floor(jumlah / isi_pcs_dus);
            const sisa_dus = jumlah % isi_pcs_dus;
            let jml_pack = 0;
            let sisa_pack = 0;
            if (isi_pcs_pack !== '0' && isi_pcs_pack != '' && isi_pcs_pack != 0) {
                jml_pack = Math.floor(sisa_dus / isi_pcs_pack);
                sisa_pack = sisa_dus % isi_pcs_pack;
            } else {
                sisa_pack = sisa_dus;
            }
            return { dus: jml_dus, pack: jml_pack, pcs: sisa_pack };
        }

        $('#tambahproduk').on('click', function(e) {
            e.preventDefault();
            const kode_pelanggan = "{{ Crypt::encrypt($kode_pelanggan) }}";
            openModalProduk();
            $('#modaleditProduk').find('.modal-title').text('Tambah Produk');
            $('#loadmodaleditProduk').html('<div class="py-8 text-center text-xs font-semibold text-slate-400">Memuat daftar produk...</div>');
            $('#loadmodaleditProduk').load(`/sfa/penjualan/${kode_pelanggan}/addproduk`);
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            const key = $(this).attr('key');
            Swal.fire({
                title: 'Apakah Anda Yakin Ingin Menghapus Data Ini ?',
                text: 'Jika dihapus maka data akan hilang permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`.index_${key}`).remove();
                    loadsubtotal();
                }
            });
        });

        $(document).on('submit', '#formAddproduk', function(event) {
            event.preventDefault();

            const kode_harga = $(this).find('#kode_harga').val();
            const nama_produk = $(this).find('#kode_harga').find(':selected').text();
            const jml_dus = $(this).find('#jml_dus').val();
            const jml_pack = $(this).find('#jml_pack').val();
            const jml_pcs = $(this).find('#jml_pcs').val();
            let harga_dus = $(this).find('#harga_dus').val();
            let harga_pack = $(this).find('#harga_pack').val();
            let harga_pcs = $(this).find('#harga_pcs').val();
            const isi_pcs_dus = $(this).find('#isi_pcs_dus').val();
            const isi_pcs_pack = $(this).find('#isi_pcs_pack').val();
            const kode_kategori_diskon = $(this).find('#kode_kategori_diskon').val();
            const kode_produk = $(this).find('#kode_produk').val();
            const status_promosi = $(this).find('#status_promosi_modal').is(':checked') ? 1 : 0;

            const jmldus = jml_dus != '' ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            const jmlpack = jml_pack != '' ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            const jmlpcs = jml_pcs != '' ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            let hargadus = harga_dus != '' ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            let hargapack = harga_pack != '' ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            let hargapcs = harga_pcs != '' ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            const jumlah = (jmldus * parseInt(isi_pcs_dus || 0)) + (jmlpack * parseInt(isi_pcs_pack || 0)) + jmlpcs;
            const data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            const dus = data.dus;
            const pack = data.pack;
            const pcs = data.pcs;

            const index = kode_harga + status_promosi;

            if (status_promosi == 1) {
                hargadus = 0; hargapack = 0; hargapcs = 0;
                harga_dus = 0; harga_pack = 0; harga_pcs = 0;
            }

            const subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(hargapack)) + (parseInt(pcs) * parseInt(hargapcs));
            const subtotal_dus = parseInt(dus) * parseInt(hargadus);
            const subtotal_pack = parseInt(pack) * parseInt(hargapack);
            const subtotal_pcs = parseInt(pcs) * parseInt(hargapcs);

            const rowHeaderClass = status_promosi == 1 ? 'bg-amber-50 dark:bg-amber-950/40' : 'bg-slate-50/90 dark:bg-slate-800/60';
            const rowSubClass = status_promosi == 1 ? 'bg-amber-50/40 dark:bg-amber-950/20' : '';

            const produk = `
                <tr class="${rowHeaderClass} index_${index}">
                    <td class="py-1.5 px-3.5 font-bold text-slate-800 dark:text-slate-100">
                        <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                        <input type="hidden" name="status_promosi_produk[]" class="status_promosi" value="${status_promosi}"/>
                        <input type="hidden" name="kode_kategori_diskon[]" class="kode_kategori_diskon" value="${kode_kategori_diskon}"/>
                        <input type="hidden" name="kode_produk[]" class="kode_produk" value="${kode_produk}"/>
                        <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                        <input type="hidden" name="jumlah_dus[]" class="jumlah_dus" value="${dus}"/>
                        <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                        <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                        <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                        <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                        <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                        <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                        <span>${nama_produk}</span>
                        ${status_promosi == 1 ? '<span class="ml-1.5 px-1.5 py-0.5 rounded bg-amber-200 text-amber-900 text-[10px] font-bold">PROMOSI</span>' : ''}
                    </td>
                    <td class="py-1.5 px-3.5 text-right">
                        <button type="button" key="${index}" class="delete w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 inline-flex items-center justify-center transition cursor-pointer">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
                ${dus != '' && dus != 0 ? `
                <tr class="${rowSubClass} index_${index}">
                    <td class="py-1.5 px-3.5 text-slate-600 dark:text-slate-300 font-mono-num">${dus} Dus &times; ${harga_dus}</td>
                    <td class="py-1.5 px-3.5 text-right font-bold text-slate-800 dark:text-slate-100 font-mono-num">${convertToRupiah(subtotal_dus) || 0}</td>
                </tr>` : ''}
                ${pack != '' && pack != 0 ? `
                <tr class="${rowSubClass} index_${index}">
                    <td class="py-1.5 px-3.5 text-slate-600 dark:text-slate-300 font-mono-num">${pack} Pack &times; ${harga_pack}</td>
                    <td class="py-1.5 px-3.5 text-right font-bold text-slate-800 dark:text-slate-100 font-mono-num">${convertToRupiah(subtotal_pack) || 0}</td>
                </tr>` : ''}
                ${pcs != '' && pcs != 0 ? `
                <tr class="${rowSubClass} index_${index}">
                    <td class="py-1.5 px-3.5 text-slate-600 dark:text-slate-300 font-mono-num">${pcs} Pcs &times; ${harga_pcs}</td>
                    <td class="py-1.5 px-3.5 text-right font-bold text-slate-800 dark:text-slate-100 font-mono-num">${convertToRupiah(subtotal_pcs) || 0}</td>
                </tr>` : ''}
            `;

            const $kodeHargaField = $(this).find('#kode_harga');
            const $jmlDusField = $(this).find('#jml_dus');

            if (kode_harga == '') {
                showFieldError($kodeHargaField, 'Produk wajib dipilih.');
                $kodeHargaField.focus();
                return false;
            }
            if (jumlah == '' || jumlah == 0) {
                showFieldError($jmlDusField, 'Jumlah produk tidak boleh kosong.');
                $jmlDusField.focus();
                return false;
            }
            if ($('#tabelproduk').find('.index_' + index).length > 0) {
                showFieldError($kodeHargaField, 'Produk ini sudah ada di daftar.');
                $kodeHargaField.focus();
                return false;
            }

            $('#loadproduk').append(produk);
            $('#produk_error_container').addClass('hidden').empty();
            if (typeof lucide !== 'undefined') lucide.createIcons();
            closeModalProduk();
            loadsubtotal();
        });

        function loadsubtotal() {
            let subtotal = 0;
            $('#tabelproduk').find('.subtotal').each(function() {
                const val = parseInt($(this).val());
                subtotal += isNaN(val) ? 0 : val;
            });

            $('#subtotal').text(convertToRupiah(subtotal) || '0');
            hitungdiskonAida();
            hitungdiskonSwan();
            hitungdiskonStick();
            hitungdiskonSC();
            hitungdiskonSP();
            calculateGrandtotal();
        }

        function calculateTotalQuantityByCategory(category) {
            let totalQuantity = 0;
            $('#tabelproduk tbody tr').each(function() {
                if ($(this).find('.kode_kategori_diskon').val() === category && $(this).find('.status_promosi').val() === '0') {
                    totalQuantity += parseInt($(this).find('.jumlah_dus').val() || 0);
                }
            });
            return totalQuantity;
        }

        function calculateTotalQuantityByProduct(kode_produk) {
            let totalQuantity = 0;
            $('#tabelproduk tbody tr').each(function() {
                if ($(this).find('.kode_produk').val() === kode_produk && $(this).find('.status_promosi').val() === '0') {
                    totalQuantity += parseInt($(this).find('.jumlah_dus').val() || 0);
                }
            });
            return totalQuantity;
        }

        function calculateDiscount(totalQuantity, category) {
            let total_discount = 0;
            const nama_pelanggan = $('#nama_pelanggan').val() || '';
            const jenis_transaksi = $('#jenis_transaksi').val();
            const discountRules = <?php echo $diskon; ?>;

            for (let i = 0; i < discountRules.length; i++) {
                if (totalQuantity >= discountRules[i].min_qty &&
                    totalQuantity <= discountRules[i].max_qty &&
                    category === discountRules[i].kode_kategori_diskon) {
                    if (jenis_transaksi === 'T') {
                        total_discount = (totalQuantity * discountRules[i].diskon) + (totalQuantity * discountRules[i].diskon_tunai);
                    } else {
                        total_discount = totalQuantity * discountRules[i].diskon;
                    }
                    if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB')) {
                        total_discount = 0;
                    }
                    break;
                }
            }
            return total_discount;
        }

        $('#jenis_transaksi').on('change', function() {
            loadsubtotal();
            showhidetunai();
            showhidekredit();
        });

        function hitungdiskonAida() {
            const totalQuantity = calculateTotalQuantityByCategory('D002');
            const diskon = calculateDiscount(totalQuantity, 'D002');
            $('#potongan_aida').val(convertToRupiah(diskon) || 0);
            return diskon;
        }

        function hitungdiskonProductBP500() {
            return calculateTotalQuantityByProduct('BP500') * 2000;
        }

        function hitungdiskonSPPP500() {
            return calculateDiscount(calculateTotalQuantityByCategory('D008'), 'D008');
        }

        function hitungdiskonSPPP1000() {
            const blacklist_pelanggan = [
                'BGR-06675', 'BGR-06827', 'BGR-06854', 'BKI-00068', 'BKI-00122', 'BKI-00633', 'BKI-00841', 'BKI-00869', 'BKI-00959', 'BKI-00982',
                'BKI-01041', 'BKI-01108', 'BKI-01163', 'BKI-01164', 'BKI-01196', 'BKI-01198', 'BKI-01199', 'BKI-01222', 'BKI-01223', 'BKI-01234',
                'BKI-01277', 'BKI-01282', 'BKI-01283', 'BKI-01292', 'BKI-01299', 'BKI-01383', 'BKI-01387', 'BGR-06648', 'BKI-00052', 'BKI-00100',
                'BKI-00110', 'BKI-00113', 'BKI-00116', 'BKI-00140', 'BKI-00268', 'BKI-00293', 'BKI-00295', 'BKI-00315', 'BKI-00341', 'BKI-00350',
                'BKI-00351', 'BKI-00353', 'BKI-00370', 'BKI-00381', 'BKI-00384', 'BKI-00450', 'BKI-00552', 'BKI-00604', 'BKI-00615', 'BKI-00671',
                'BKI-00800', 'BKI-00813', 'BKI-00821', 'BKI-00831', 'BKI-00858', 'BKI-00871', 'BKI-00872', 'BKI-00879', 'BKI-00884', 'BKI-00985',
                'BKI-01006', 'BKI-01026', 'BKI-01052', 'BKI-01081', 'BKI-01111', 'BKI-01112', 'BKI-01119', 'BKI-01151', 'BKI-01171', 'BKI-01172',
                'BKI-01202', 'BKI-01203', 'BKI-01278', 'BKI-01287', 'BKI-01291', 'BKI-01295', 'BKI-01296', 'BKI-01297', 'BKI-01375', 'BKI-01376',
                'BKI-01377', 'BKI-01386', 'BKI-01388', 'BKI-01392', 'BKI-01394', 'BKI-01400', 'BGR-06669', 'BGR-06759', 'BKI-00007', 'BKI-00008',
                'BKI-00029', 'BKI-00121', 'BKI-00258', 'BKI-00367', 'BKI-00518', 'BKI-00585', 'BKI-00686', 'BKI-00735', 'BKI-00773', 'BKI-00777',
                'BKI-00902', 'BKI-00911', 'BKI-00912', 'BKI-00917', 'BKI-00936', 'BKI-00973', 'BKI-00979', 'BKI-01023', 'BKI-01040', 'BKI-01049',
                'BKI-01187', 'BKI-01193', 'BKI-01194', 'BKI-01201', 'BKI-01236', 'BKI-01238', 'BKI-01240', 'BKI-01242', 'BKI-01261', 'BKI-01266',
                'BKI-01285', 'BKI-01294', 'BGR-07636', 'BRG-07591', 'BRG-07480', 'BRG-07465', 'BRG-07650', 'BRG-07982', 'BRG-07984', 'BRG-07751',
                'BRG-07638', 'BRG-07597', 'BRG-07643', 'BRG-07966', 'BRG-07809', 'BRG-07693', 'BRG-07784', 'BKI-01419'
            ];
            let diskon = calculateDiscount(calculateTotalQuantityByCategory('D009'), 'D009');
            if (blacklist_pelanggan.includes(kode_pel)) diskon = 0;
            return diskon;
        }

        function hitungdiskonSAOSME() {
            const kode_cabang_diskon_saosme = ['BTN', 'CRB', 'SKB', 'TSM', 'BKI', 'KLT', 'PWK', 'BDG'];
            const diskon = calculateDiscount(calculateTotalQuantityByCategory('D010'), 'D010');
            return kode_cabang_diskon_saosme.includes(kode_cabang_pelanggan) ? diskon : 0;
        }

        function hitungdiskonSwan() {
            const totaldiskon = parseInt(calculateDiscount(calculateTotalQuantityByCategory('D001'), 'D001')) +
                parseInt(hitungdiskonProductBP500()) +
                parseInt(hitungdiskonSPPP500()) +
                parseInt(hitungdiskonSPPP1000()) +
                parseInt(hitungdiskonSAOSME());
            $('#potongan_swan').val(convertToRupiah(totaldiskon) || 0);
            return totaldiskon;
        }

        function hitungdiskonStick() {
            const diskon = calculateDiscount(calculateTotalQuantityByCategory('D003'), 'D003');
            $('#potongan_stick').val(convertToRupiah(diskon) || 0);
        }

        function hitungdiskonSP() {
            const diskon = calculateDiscount(calculateTotalQuantityByCategory('D004'), 'D004');
            $('#potongan_sp').val(convertToRupiah(diskon) || 0);
        }

        function hitungdiskonSC() {
            const diskon = calculateDiscount(calculateTotalQuantityByCategory('D005'), 'D005');
            $('#potongan_sambal').val(convertToRupiah(diskon) || 0);
        }

        function parseMoney(val) {
            return val != '' ? parseInt(val.toString().replace(/\./g, '')) || 0 : 0;
        }

        function calculateGrandtotal() {
            const subtotal = parseMoney($('#subtotal').text());
            const total_potongan = parseMoney($('#potongan_swan').val()) + parseMoney($('#potongan_aida').val()) +
                parseMoney($('#potongan_stick').val()) + parseMoney($('#potongan_sambal').val());
            const total_potongan_istimewa = parseMoney($('#potis_aida').val()) + parseMoney($('#potis_swan').val()) + parseMoney($('#potis_stick').val());
            const total_penyesuaian = parseMoney($('#peny_aida').val()) + parseMoney($('#peny_swan').val()) + parseMoney($('#peny_stick').val());

            const grandtotal = subtotal - total_potongan - total_potongan_istimewa - total_penyesuaian;
            const formattedGt = convertToRupiah(grandtotal) || '0';
            $('#grandtotal_text').text(formattedGt);
            $('#grandtotal_mobile_text').text(formattedGt);
            $('#grandtotal').val(formattedGt);
        }

        $('#btnSimpanMobile').on('click', function() {
            $('#formPenjualan').trigger('submit');
        });

        $('#potongan_aida, #potongan_swan, #potongan_stick, #potongan_sambal, #potis_aida, #potis_swan, #potis_stick, #peny_aida, #peny_swan, #peny_stick')
            .on('keyup keydown', calculateGrandtotal);

        function showhidetunai() {
            if ($('#jenis_transaksi').val() == 'T') {
                $('#jenis_bayar_tunai').show();
                $('#voucher_tunai').show();
            } else {
                $('#jenis_bayar_tunai').hide();
                $('#voucher_tunai').hide();
            }
        }

        function showhidekredit() {
            if ($('#jenis_transaksi').val() == 'K') {
                $('#titipan').show();
            } else {
                $('#titipan').hide();
            }
        }

        showhidetunai();
        showhidekredit();

        let isTaxConfirmed = false;
        $('#formPenjualan').on('submit', function(e) {
            const $no_faktur = $('#no_faktur');
            const $tanggal = $('#tanggal');
            const $nama_pelanggan = $('#nama_pelanggan');
            const $nama_salesman = $('#nama_salesman');
            const $jenis_transaksi = $('#jenis_transaksi');
            const $jenis_bayar = $('#jenis_bayar');
            const $keterangan = $('#keterangan');
            const $voucher = $('#voucher');

            const no_faktur = $no_faktur.val().trim();
            const tanggal = $tanggal.val().trim();
            const kode_pelanggan = $('#kode_pelanggan').val();
            const kode_salesman = $('#kode_salesman').val();
            const sisa_piutang_raw = $('#sisa_piutang').val();
            const sisa_piutang = (sisa_piutang_raw !== '' && !isNaN(sisa_piutang_raw)) ? parseInt(sisa_piutang_raw) : 0;
            const grandtotal = parseMoney($('#grandtotal').val());
            const totalPiutang = sisa_piutang + grandtotal;
            const limit_pelanggan = $('#limit_pelanggan').val() != '' ? parseInt($('#limit_pelanggan').val()) : 0;
            const siklus_pembayaran = String($('#siklus_pembayaran').val() || '0');
            const jenis_transaksi = $jenis_transaksi.val();
            const jenis_bayar = $jenis_bayar.val();
            const keterangan = $keterangan.val().trim();
            const voucher = parseMoney($voucher.val());

            let firstInvalid = null;

            if (no_faktur == '') {
                showFieldError($no_faktur, 'No. Faktur wajib diisi.');
                firstInvalid = firstInvalid || $no_faktur;
            }
            if (tanggal == '') {
                showFieldError($tanggal, 'Tanggal wajib diisi.');
                firstInvalid = firstInvalid || $tanggal;
            }
            if (kode_pelanggan == '') {
                showFieldError($nama_pelanggan, 'Pelanggan wajib diisi.');
                firstInvalid = firstInvalid || $nama_pelanggan;
            }
            if (kode_salesman == '') {
                showFieldError($nama_salesman, 'Salesman wajib diisi.');
                firstInvalid = firstInvalid || $nama_salesman;
            }
            if ($('#loadproduk tr').length == 0) {
                $('#produk_error_container').removeClass('hidden').html(`
                    <p class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>Detail produk penjualan belum ditambahkan.</span>
                    </p>
                `);
                firstInvalid = firstInvalid || $('#tambahproduk');
            }
            if (jenis_transaksi == '') {
                showFieldError($jenis_transaksi, 'Jenis Transaksi wajib dipilih.');
                firstInvalid = firstInvalid || $jenis_transaksi;
            }
            if (jenis_transaksi == 'T' && jenis_bayar == '') {
                showFieldError($jenis_bayar, 'Jenis Bayar wajib dipilih.');
                firstInvalid = firstInvalid || $jenis_bayar;
            }
            if (jenis_transaksi == 'K' && sisa_piutang > 0 && keterangan == '') {
                showFieldError($keterangan, 'Keterangan wajib diisi karena pelanggan masih memiliki piutang.');
                firstInvalid = firstInvalid || $keterangan;
            }
            if (voucher > saldo_voucher) {
                showFieldError($voucher, 'Voucher melebihi saldo voucher.');
                firstInvalid = firstInvalid || $voucher;
            }

            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return false;
            }

            if (jenis_transaksi == 'K' && limit_pelanggan <= 0) {
                e.preventDefault();
                Swal.fire({ title: 'Oops!', text: 'Pelanggan belum memiliki limit kredit, silahkan ajukan limit terlebih dahulu !', icon: 'warning', showConfirmButton: true });
                return false;
            } else if (jenis_transaksi == 'K' && siklus_pembayaran === '0' && totalPiutang > limit_pelanggan) {
                e.preventDefault();
                Swal.fire({ title: 'Oops!', text: 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !', icon: 'warning', showConfirmButton: true });
                return false;
            } else if (jenis_transaksi == 'K' && siklus_pembayaran === '1' && grandtotal > limit_pelanggan) {
                e.preventDefault();
                Swal.fire({ title: 'Oops!', text: 'Melebihi Limit, Silahkan Ajukan Penambahan Limit !', icon: 'warning', showConfirmButton: true });
                return false;
            } else if (jenis_transaksi == 'K' && parseInt(jmlfakturbelumlunas) >= parseInt(jmlmaxfaktur)) {
                e.preventDefault();
                Swal.fire({ title: 'Oops!', text: 'Melebihi Batas Jumlah Max. Faktur Kredit', icon: 'warning', showConfirmButton: true });
                return false;
            } else if (!isTaxConfirmed) {
                e.preventDefault();
                Swal.fire({
                    title: 'Pilih Status Pajak',
                    text: 'Apakah transaksi ini menggunakan Faktur Pajak?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: 'Faktur Pajak',
                    denyButtonText: 'Non Faktur Pajak',
                    confirmButtonColor: '#28a745',
                    denyButtonColor: '#6c757d',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#status_pajak_faktur_input').val(1);
                        isTaxConfirmed = true;
                        $('#jenis_transaksi').prop('disabled', false);
                        $('#jenis_bayar').prop('disabled', false);
                        buttonDisable();
                        $('#formPenjualan').submit();
                    } else if (result.isDenied) {
                        $('#status_pajak_faktur_input').val(0);
                        isTaxConfirmed = true;
                        $('#jenis_transaksi').prop('disabled', false);
                        $('#jenis_bayar').prop('disabled', false);
                        buttonDisable();
                        $('#formPenjualan').submit();
                    }
                });
                return false;
            }
        });
    });
</script>
@endpush
