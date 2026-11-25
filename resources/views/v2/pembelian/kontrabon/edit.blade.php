@extends('layouts.app')
@section('titlepage', 'Edit Kontra Bon')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('kontrabonpmb.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Kontrabon</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Edit Kontra Bon</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Edit Kontra Bon</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">
                Perbarui dokumen kontra bon nomor 
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $kontrabon->no_kontrabon }}</span>.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('kontrabonpmb.index') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('kontrabonpmb.update', Crypt::encrypt($kontrabon->no_kontrabon)) }}" method="POST" id="formKontrabon" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">
            
            <!-- LEFT PANEL: INFORMASI KONTRA BON (3 COLS) -->
            <div class="lg:col-span-3 space-y-3">
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Kontra Bon</h4>
                    </div>

                    <!-- No. Kontrabon -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="no_kontrabon" id="no_kontrabon" value="{{ $kontrabon->no_kontrabon }}" readonly
                                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono-num focus:outline-hidden cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tanggal" id="tanggal" value="{{ $kontrabon->tanggal }}" placeholder="Pilih Tanggal" readonly
                                class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                        </div>
                    </div>

                    <!-- Supplier (Disabled + Hidden Input) -->
                    <div>
                        <div class="relative select2-container-v2">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_supplier" id="kode_supplier" class="select2Kodesupplier w-full" disabled>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->kode_supplier }}" @selected($s->kode_supplier == $kontrabon->kode_supplier)>
                                        {{ textUpperCase($s->nama_supplier) }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="kode_supplier" value="{{ $kontrabon->kode_supplier }}">
                        </div>
                    </div>

                    <!-- Jenis Pengajuan -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kategori" id="kategori"
                                class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Jenis Pengajuan</option>
                                <option value="KB" @selected($kontrabon->kategori == 'KB')>Kontra Bon</option>
                                <option value="IM" @selected($kontrabon->kategori == 'IM')>Internal Memo</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3 h-3"></i>
                            </div>
                        </div>
                    </div>

                    <!-- No. Dokumen -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="no_dokumen" id="no_dokumen" value="{{ $kontrabon->no_dokumen }}" placeholder="No. Dokumen (opsional)"
                                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                        </div>
                    </div>

                    <!-- Jenis Bayar -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="jenis_bayar" id="jenis_bayar"
                                class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Jenis Bayar</option>
                                <option value="TN" @selected($kontrabon->jenis_bayar == 'TN')>Tunai</option>
                                <option value="TF" @selected($kontrabon->jenis_bayar == 'TF')>Transfer</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3 h-3"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT PANEL: DETAIL RINCIAN FAKTUR & TOTAL (9 COLS) -->
            <div class="lg:col-span-9 space-y-3">
                
                <!-- CARD FORM TAMBAH ITEM (NO-LABEL, 1 BARIS KOMPAK) -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Tambah Faktur ke Kontra Bon</h4>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-400 block font-semibold">Total Kontra Bon</span>
                            <span id="grandtotal_text" class="text-base font-black font-mono-num text-[#284c9a] dark:text-blue-400 leading-tight">0</span>
                        </div>
                    </div>

                    <!-- Baris Form Tambah Item (No-Label) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-start">
                        
                        <!-- No. Bukti Pembelian (Col-span 4) -->
                        <div class="lg:col-span-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="no_bukti" id="no_bukti" placeholder="Pilih Faktur (Klik)" readonly
                                    class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Faktur (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <input type="text" name="total_pembelian" id="total_pembelian" placeholder="Total Faktur" readonly
                                    class="w-full h-[36px] px-2.5 text-xs font-semibold font-mono-num text-right rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 focus:outline-hidden cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Jml Bayar (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <input type="text" name="jumlah" id="jumlah" placeholder="Jml Bayar"
                                    class="number-separator text-right w-full h-[36px] px-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                            </div>
                        </div>

                        <!-- Keterangan (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan (opsional)"
                                    class="w-full h-[36px] px-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                            </div>
                        </div>

                        <!-- Tombol Tambah Item (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <button type="button" id="btnTambahitem"
                                class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Tambah</span>
                            </button>
                        </div>

                    </div>

                </div>

                <!-- ERROR DAFTAR FAKTUR KOSONG -->
                <div id="error_daftar_faktur" class="hidden p-2.5 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                    <span>Daftar faktur Kontra Bon tidak boleh kosong! Tambahkan minimal 1 faktur ke daftar di bawah.</span>
                </div>

                <!-- TABEL DATA RINCIAN ITEM KONTRABON -->
                <div id="cardTabelFaktur" class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs transition">
                    <div class="overflow-x-auto min-h-[160px]">
                        <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                            <thead>
                                <tr class="bg-[#284c9a] text-white">
                                    <th colspan="4" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="receipt" class="w-3.5 h-3.5 text-blue-200"></i>
                                                <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Faktur Pembelian</span>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-[#1e3a78] text-white text-[11px]">
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-44">NO. BUKTI</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">KETERANGAN</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">JUMLAH</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-12">#</th>
                                </tr>
                            </thead>
                            <tbody id="loadpembelian" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                @foreach ($detail as $d)
                                    @php
                                        $no_bukti_clean = preg_replace('/[^a-zA-Z0-9]/', '', $d->no_bukti);
                                    @endphp
                                    <tr id="{{ $no_bukti_clean }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                            <input type="hidden" name="no_bukti_item[]" value="{{ $d->no_bukti }}"/>
                                            {{ $d->no_bukti }}
                                        </td>
                                        <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                                            <input type="hidden" name="keterangan_item[]" value="{{ $d->keterangan }}"/>
                                            {{ $d->keterangan ?: '-' }}
                                        </td>
                                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100 totalbayar">
                                            <input type="hidden" name="jumlah_item[]" value="{{ $d->jumlah }}"/>
                                            {{ formatAngkaDesimal($d->jumlah) }}
                                        </td>
                                        <td class="py-1.5 px-3 text-center">
                                            <a href="#" id="{{ $no_bukti_clean }}" class="deleteItemBtn w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs" title="Hapus Faktur">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-800">
                                    <td colspan="2" class="py-2.5 px-3 text-right uppercase text-slate-700 dark:text-slate-300 text-[11px]">
                                        Total Kontra Bon:
                                    </td>
                                    <td id="grandtotal" class="py-2.5 px-3 text-right font-mono-num font-black text-slate-900 dark:text-slate-100 text-xs">
                                        0
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- SUBMIT / SIMPAN SECTION (RIGHT-ALIGNED) -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-3.5 shadow-2xs space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="agreement" class="agreement w-4 h-4 rounded-sm border-slate-300 text-[#284c9a] focus:ring-brand-500 cursor-pointer">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Yakin perubahan data transaksi Kontra Bon ini sudah benar dan siap disimpan?</span>
                    </label>

                    <div id="saveButton" class="hidden pt-2 border-t border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('kontrabonpmb.index') }}"
                                class="h-[36px] px-4 inline-flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
                                Batal
                            </a>
                            <button type="submit" id="btnSimpan"
                                class="w-full sm:w-auto px-6 h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-xs cursor-pointer">
                                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<!-- STATIC BACKDROP MODAL DATA PEMBELIAN V2 -->
<div id="modalPembelian" class="fixed inset-0 z-50 hidden">
    <div id="modalPembelianBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-3 sm:p-5 overflow-y-auto">
        <div id="modalPembelianDialog"
            class="relative w-full max-w-6xl 2xl:max-w-7xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-t-xl">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">Pilih Faktur Pembelian Supplier</h5>
                        <p class="text-[11px] text-slate-400 mt-0.5">Pilih salah satu faktur pembelian supplier untuk dimasukkan ke dalam daftar kontra bon.</p>
                    </div>
                </div>
                <button type="button" id="btnCloseModalPembelian"
                    class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer shrink-0">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-5 sm:p-6 space-y-4">
                <div class="overflow-x-auto rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs bg-white dark:bg-slate-900">
                    <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap" id="tabelpembelian" style="width: 100%">
                        <thead>
                            <!-- Baris 1: Strip Header Pacific Navy -->
                            <tr class="bg-[#284c9a] text-white">
                                <th colspan="8" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="receipt" class="w-4 h-4 text-blue-200"></i>
                                            <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Faktur Pembelian Supplier</span>
                                        </div>
                                        <span class="text-[11px] text-white/70">Klik tombol aksi pada baris faktur yang ingin dipilih</span>
                                    </div>
                                </th>
                            </tr>
                            <!-- Baris 2: Kolom Header -->
                            <tr class="bg-[#1e3a78] text-white text-[11px]">
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40 min-w-[140px]">NO. BUKTI</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40 min-w-[100px]">TANGGAL</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40 min-w-[140px]">ASAL AJUAN</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40 text-center min-w-[65px]">PPN</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40 text-right min-w-[120px]">SUBTOTAL</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40 text-right min-w-[110px]">PENY JK</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40 text-right min-w-[120px]">TOTAL</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 text-center min-w-[70px]">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    #modalPembelian .dataTables_wrapper {
        width: 100% !important;
    }
    #modalPembelian .dataTables_wrapper .dataTables_length,
    #modalPembelian .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0.875rem !important;
    }
    #modalPembelian .dataTables_wrapper .dataTables_info,
    #modalPembelian .dataTables_wrapper .dataTables_paginate {
        margin-top: 0.875rem !important;
    }
    #tabelpembelian td {
        padding: 8px 12px !important;
        vertical-align: middle !important;
    }
    .pilihnobukti {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 28px !important;
        height: 28px !important;
        border-radius: 0.375rem !important;
        background-color: #eff6ff !important;
        color: #284c9a !important;
        font-weight: 700 !important;
        transition: all 0.15s ease !important;
        cursor: pointer !important;
    }
    .pilihnobukti:hover {
        background-color: #284c9a !important;
        color: #ffffff !important;
    }
    .dark .pilihnobukti {
        background-color: rgba(40, 76, 154, 0.3) !important;
        color: #93c5fd !important;
    }
    .dark .pilihnobukti:hover {
        background-color: #284c9a !important;
        color: #ffffff !important;
    }
</style>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const form = $("#formKontrabon");
        let total_pembelian = 0;
        let total_bayar = 0;
        let sisa_bayar = 0;
        let tablePembelianDT = null;

        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ',',
            });
        }

        // Flatpickr
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        // Select2 Supplier
        const $select2Supplier = $('.select2Kodesupplier');
        if ($select2Supplier.length && $.fn.select2) {
            $select2Supplier.select2({
                placeholder: 'Pilih Supplier',
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        // Modal Helpers (Static Backdrop, Explicit Close, Bounce)
        const $modal = $("#modalPembelian");
        const $dialog = $("#modalPembelianDialog");

        function openModalPembelian() {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closeModalPembelian() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
        }

        function bounceModal() {
            $dialog.addClass('scale-[1.015] shadow-2xl');
            setTimeout(() => $dialog.removeClass('scale-[1.015] shadow-2xl'), 150);
        }

        $("#modalPembelianBackdrop").on('click', function(e) {
            e.stopPropagation();
            bounceModal();
        });

        $("#btnCloseModalPembelian").on('click', closeModalPembelian);

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                bounceModal();
            }
        });

        function convertNumber(number) {
            if (!number) return 0;
            let formatted = number.toString().replace(/\./g, '');
            formatted = formatted.replace(/,/g, '.');
            return parseFloat(formatted) || 0;
        }

        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? '.' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function removeSpecialCharacters(str) {
            return str.replace(/[^a-zA-Z0-9]/g, '');
        }

        function calculateTotal() {
            let grandTotal = 0;
            $('.totalbayar').each(function() {
                grandTotal += parseFloat(convertNumber($(this).text())) || 0;
            });
            $('#grandtotal').text(numberFormat(grandTotal, '2', ',', '.'));
            $('#grandtotal_text').text(numberFormat(grandTotal, '2', ',', '.'));
        }

        // Calculate initial total
        calculateTotal();

        function resetInputItem() {
            form.find("#no_bukti").val("");
            form.find("#total_pembelian").val("");
            form.find("#jumlah").val("");
            form.find("#keterangan").val("");
        }

        // Load DataTable Pembelian
        function loadTabelpembelian(kode_supplier) {
            if (tablePembelianDT) {
                tablePembelianDT.clear().destroy();
                $('#tabelpembelian tbody').empty();
            }

            tablePembelianDT = $('#tabelpembelian').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'asc']],
                ajax: `/pembelian/${kode_supplier}/getpembelianbysupplierjson`,
                bAutoWidth: false,
                bDestroy: true,
                columns: [
                    { 
                        data: 'no_bukti', 
                        name: 'no_bukti', 
                        orderable: true, 
                        searchable: true,
                        className: 'font-mono-num font-semibold text-slate-800 dark:text-slate-200'
                    },
                    { 
                        data: 'tanggal', 
                        name: 'tanggal', 
                        orderable: true, 
                        searchable: false,
                        className: 'font-mono-num text-slate-600 dark:text-slate-300'
                    },
                    { 
                        data: 'asal_pengajuan', 
                        name: 'kode_asal_pengajuan', 
                        orderable: true, 
                        searchable: false,
                        className: 'text-slate-700 dark:text-slate-300'
                    },
                    { 
                        data: 'cekppn', 
                        name: 'ppn', 
                        orderable: true, 
                        searchable: false, 
                        className: 'text-center' 
                    },
                    { 
                        data: 'subtotal', 
                        name: 'subtotal', 
                        orderable: true, 
                        searchable: false, 
                        className: 'text-right font-mono-num font-semibold text-slate-700 dark:text-slate-300' 
                    },
                    { 
                        data: 'penyesuaianjk', 
                        name: 'penyesuaian_jk', 
                        orderable: true, 
                        searchable: false, 
                        className: 'text-right font-mono-num text-slate-600 dark:text-slate-400' 
                    },
                    { 
                        data: 'totalpembelian', 
                        name: 'total_pembelian', 
                        orderable: true, 
                        searchable: false, 
                        className: 'text-right font-mono-num font-bold text-[#284c9a] dark:text-blue-400' 
                    },
                    { 
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false, 
                        className: 'text-center' 
                    }
                ],
                drawCallback: function() {
                    $('#tabelpembelian tbody .pilihnobukti').each(function() {
                        $(this).addClass('pilihnobukti');
                        if (!$(this).find('svg').length) {
                            $(this).html('<i data-lucide="check" class="w-3.5 h-3.5"></i>');
                        }
                    });
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        // Trigger buka modal pembelian
        form.find("#no_bukti").on('click', function(e) {
            e.preventDefault();
            const kode_supplier = form.find("input[name='kode_supplier']").val();
            if (!kode_supplier) {
                showInlineError(form.find("#kode_supplier"), "Silakan pilih supplier terlebih dahulu!");
                return;
            }
            openModalPembelian();
            loadTabelpembelian(kode_supplier);
        });

        // Pilih no bukti dari modal
        $('#tabelpembelian tbody').on('click', '.pilihnobukti', function(e) {
            e.preventDefault();
            const no_bukti = $(this).attr('no_bukti');
            total_pembelian = $(this).attr('total_pembelian') || 0;
            total_bayar = $(this).attr('total_bayar') || 0;
            sisa_bayar = parseFloat(total_pembelian) - parseFloat(total_bayar);

            form.find("#no_bukti").val(no_bukti);
            form.find("#total_pembelian").val(numberFormat(total_pembelian, '2', ',', '.'));
            form.find("#jumlah").val(numberFormat(sisa_bayar > 0 ? sisa_bayar : total_pembelian, '2', ',', '.'));

            clearInlineError(form.find("#no_bukti"));
            closeModalPembelian();
            form.find("#jumlah").focus();
        });

        // Inline Validation Helpers
        function showInlineError($el, msg) {
            $el.addClass("border-rose-500 ring-1 ring-rose-500");
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $rel = $el.closest(".relative");
            if ($rel.length && !($rel[0].className && $rel[0].className.indexOf('col-span') !== -1)) {
                $rel.find("i").addClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            } else {
                $el.siblings(".field-error-msg").remove();
                $el.parent().find("> .field-error-msg").remove();
                $el.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $el.focus();
        }

        function clearInlineError($el) {
            $el.removeClass("border-rose-500 ring-1 ring-rose-500");
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find("i").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.parent().find(".field-error-msg").remove();
            }
            $el.siblings(".field-error-msg").remove();
            $el.parent().find("> .field-error-msg").remove();
            $el.parent().find(".field-error-msg").remove();
        }

        // Tambah item ke tabel
        form.find("#btnTambahitem").on('click', function(e) {
            e.preventDefault();
            const no_bukti = form.find("#no_bukti").val();
            const jumlah = form.find("#jumlah").val();
            const jml = convertNumber(jumlah);
            const keterangan = form.find("#keterangan").val();
            const no_bukti_index = removeSpecialCharacters(no_bukti);

            if (!no_bukti) {
                showInlineError(form.find("#no_bukti"), "Faktur pembelian belum dipilih!");
                return;
            }

            if (!jumlah || jml <= 0) {
                showInlineError(form.find("#jumlah"), "Qty/Jumlah bayar wajib diisi!");
                return;
            }

            if ($('#loadpembelian').find('#' + no_bukti_index).length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Faktur Sudah Ada!',
                    text: 'Faktur pembelian ini sudah ada di dalam daftar kontra bon.',
                    confirmButtonColor: '#284c9a'
                });
                return;
            }

            let newItem = `
                <tr id="${no_bukti_index}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                        <input type="hidden" name="no_bukti_item[]" value="${no_bukti}"/>
                        ${no_bukti}
                    </td>
                    <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                        <input type="hidden" name="keterangan_item[]" value="${keterangan}"/>
                        ${keterangan || '-'}
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100 totalbayar">
                        <input type="hidden" name="jumlah_item[]" value="${jumlah}"/>
                        ${jumlah}
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <a href="#" id="${no_bukti_index}" class="deleteItemBtn w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs" title="Hapus Faktur">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </a>
                    </td>
                </tr>
            `;

            form.find("#loadpembelian").append(newItem);
            $("#cardTabelFaktur").removeClass("border-rose-500 ring-1 ring-rose-500");
            $("#error_daftar_faktur").addClass("hidden");
            if (typeof lucide !== 'undefined') lucide.createIcons();
            calculateTotal();
            resetInputItem();
        });

        // Hapus item dari tabel
        $(document).on('click', '.deleteItemBtn', function(e) {
            e.preventDefault();
            const id = $(this).attr('id');
            $(`#${id}`).remove();
            calculateTotal();
        });

        // Toggle Tombol Submit via Agreement
        form.find('.agreement').on('change', function() {
            if (this.checked) {
                form.find("#saveButton").removeClass('hidden');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            } else {
                form.find("#saveButton").addClass('hidden');
            }
        });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Perubahan...</span>
            `);
        }

        // Submit Form Validation & Anti-Double Submit
        form.on('submit', function(e) {
            const tanggal = form.find("#tanggal").val().trim();
            const kategori = form.find("#kategori").val();
            const jenis_bayar = form.find("#jenis_bayar").val();
            let hasError = false;

            if (!tanggal) {
                showInlineError(form.find("#tanggal"), "Tanggal wajib diisi!");
                hasError = true;
            }

            if (!kategori) {
                showInlineError(form.find("#kategori"), "Jenis pengajuan wajib dipilih!");
                hasError = true;
            }

            if (!jenis_bayar) {
                showInlineError(form.find("#jenis_bayar"), "Jenis bayar wajib dipilih!");
                hasError = true;
            }

            if ($('#loadpembelian tr').length === 0) {
                $("#cardTabelFaktur").addClass("border-rose-500 ring-1 ring-rose-500");
                $("#error_daftar_faktur").removeClass("hidden");
                if (typeof lucide !== 'undefined') lucide.createIcons();
                hasError = true;
            } else {
                $("#cardTabelFaktur").removeClass("border-rose-500 ring-1 ring-rose-500");
                $("#error_daftar_faktur").addClass("hidden");
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            buttonDisable();
        });

        // Live cleanup on input & select
        form.on("input change", "input, select", function() {
            clearInlineError($(this));
        });
        $(document).on("select2:select select2:clear", function(e) {
            clearInlineError($(e.target));
        });
    });
</script>
@endpush
