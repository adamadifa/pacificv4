@extends('layouts.app')
@section('titlepage', 'Edit Pre-Order (PO)')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('pembelian.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Pembelian</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('po.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Pre-Order (PO)</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Edit PO</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Edit Pre-Order (PO)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Perbarui rincian pemesanan barang nomor bukti <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $po->no_bukti }}</span>.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('po.index') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('po.update', Crypt::encrypt($po->no_bukti)) }}" method="POST" id="formPO">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">
            
            <!-- LEFT PANEL: DATA TRANSAKSI UTAMA (3 COLS) -->
            <div class="lg:col-span-3 space-y-3">
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Pre-Order</h4>
                    </div>

                    <!-- No. Bukti -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="no_bukti" id="no_bukti" value="{{ $po->no_bukti }}" readonly
                                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden uppercase">
                        </div>
                    </div>

                    <!-- Tanggal PO -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tanggal" id="tanggal" value="{{ $po->tanggal }}" placeholder="Tanggal PO" readonly
                                class="flatpickr-datepmb w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div>
                        <div class="relative select2-container-v2">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_supplier" id="kode_supplier" class="select2Kodesupplier w-full">
                                <option value="">Pilih Supplier</option>
                                @foreach ($supplier as $d)
                                    <option value="{{ $d->kode_supplier }}" {{ $d->kode_supplier == $po->kode_supplier ? 'selected' : '' }}>
                                        {{ textUpperCase($d->nama_supplier) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Kategori Perusahaan (MP / CV MP) -->
                    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 space-y-2">
                        <span class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kategori Perusahaan</span>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer group">
                                <input type="radio" name="kategori_perusahaan" id="kat_mp" value="MP" {{ $po->kategori_perusahaan == 'MP' ? 'checked' : '' }} class="peer hidden">
                                <div class="h-[36px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center gap-1.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60 peer-checked:border-[#284c9a] peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/40 peer-checked:text-[#284c9a] dark:peer-checked:text-blue-300 peer-checked:font-bold shadow-2xs">
                                    <span class="text-xs">MP</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="kategori_perusahaan" id="kat_cvmp" value="CVMP" {{ $po->kategori_perusahaan == 'CVMP' ? 'checked' : '' }} class="peer hidden">
                                <div class="h-[36px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 flex items-center justify-center gap-1.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/60 peer-checked:border-[#284c9a] peer-checked:bg-blue-50/70 dark:peer-checked:bg-blue-950/40 peer-checked:text-[#284c9a] dark:peer-checked:text-blue-300 peer-checked:font-bold shadow-2xs">
                                    <span class="text-xs">CV MP</span>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT PANEL: DETAIL RINCIAN BARANG & TOTAL (9 COLS) -->
            <div class="lg:col-span-9 space-y-3">
                
                <!-- CARD FORM TAMBAH BARANG -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Tambah Barang ke Pre-Order</h4>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-400 block font-semibold">Total PO</span>
                            <span id="grandtotal_text" class="text-base font-black font-mono-num text-[#284c9a] dark:text-blue-400 leading-tight">0</span>
                        </div>
                    </div>

                    <!-- Baris Form Tambah Barang (No-Label) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-start">
                        
                        <!-- Nama Barang (Col-span 4) -->
                        <div class="lg:col-span-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="package" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="nama_barang" id="nama_barang" placeholder="Pilih Barang (Klik)" readonly
                                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                                <input type="hidden" id="kode_barang" name="kode_barang">
                            </div>
                        </div>

                        <!-- Qty (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="jumlah" id="jumlah" placeholder="Qty"
                                    class="number-separator text-right w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <!-- Harga (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="harga" id="harga" placeholder="Harga Satuan"
                                    class="number-separator text-right w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <!-- Keterangan (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan (opsional)"
                                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <!-- Tombol Tambah Barang (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <button type="button" id="btnTambahbarang"
                                class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Tambah</span>
                            </button>
                        </div>

                    </div>

                </div>

                <!-- TABEL DATA RINCIAN ITEM PO -->
                <div id="error_rincian_barang" class="hidden p-2.5 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                    <span>Rincian barang Pre-Order tidak boleh kosong! Tambahkan minimal 1 barang ke daftar di bawah.</span>
                </div>

                <div id="cardTabelBarang" class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs transition">
                    <div class="overflow-x-auto min-h-[160px]">
                        <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                            <thead>
                                <tr class="bg-[#284c9a] text-white">
                                    <th colspan="7" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="shopping-bag" class="w-3.5 h-3.5 text-blue-200"></i>
                                                <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Rincian Barang PO</span>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-[#1e3a78] text-white text-[11px]">
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">KODE</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">NAMA BARANG</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[150px]">KETERANGAN</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">QTY</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">HARGA</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">TOTAL</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-12">#</th>
                                </tr>
                            </thead>
                            <tbody id="loadbarang" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                @php $barisIndex = 0; @endphp
                                @foreach ($detail as $item)
                                    @php
                                        $barisIndex++;
                                        $subtotal = $item->jumlah * $item->harga;
                                    @endphp
                                    <tr id="index_{{ $barisIndex }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                            <input type="hidden" name="kode_barang_item[]" value="{{ $item->kode_barang }}" />
                                            <input type="hidden" name="jumlah_item[]" value="{{ formatAngkaDesimal($item->jumlah) }}" />
                                            <input type="hidden" name="harga_item[]" value="{{ formatAngkaDesimal($item->harga) }}" />
                                            <input type="hidden" name="keterangan_item[]" value="{{ $item->keterangan }}" />
                                            {{ $item->kode_barang }}
                                        </td>
                                        <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">{{ $item->nama_barang }}</td>
                                        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ $item->keterangan ?: '-' }}</td>
                                        <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-200">{{ formatAngkaDesimal($item->jumlah) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">{{ formatAngkaDesimal($item->harga) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100 totalharga">{{ formatAngkaDesimal($subtotal) }}</td>
                                        <td class="py-1.5 px-3 text-center">
                                            <a href="#" id="index_{{ $barisIndex }}" class="delete w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs" title="Hapus Barang">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-800">
                                    <td colspan="5" class="py-2.5 px-3 text-right uppercase text-slate-700 dark:text-slate-300 text-[11px]">
                                        Total Pre-Order:
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

                <!-- SUBMIT / UPDATE SECTION -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-3.5 shadow-2xs space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="agreement" class="agreement w-4 h-4 rounded-sm border-slate-300 text-[#284c9a] focus:ring-brand-500">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Yakin data perubahan Pre-Order (PO) sudah benar dan siap disimpan?</span>
                    </label>

                    <div id="saveButton" class="hidden">
                        <button type="submit" id="btnSimpan"
                            class="w-full sm:w-auto px-6 h-[38px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-xs cursor-pointer">
                            <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            <span>Perbarui Pre-Order (PO)</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>

<!-- MODAL PILIH BARANG (STATIC BACKDROP, EXPLICIT CLOSE) -->
<div class="modal fade" id="modalBarang" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden transition-all duration-150">
            
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Data Master Barang</h3>
                </div>
                <button type="button" data-bs-dismiss="modal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-4 sm:p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap" id="tabelbarang" width="100%">
                        <thead>
                            <tr class="bg-[#284c9a] text-white text-[11px]">
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white border-0 w-24">KODE</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white border-0 min-w-[200px]">NAMA BARANG</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white border-0 w-24">SATUAN</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white border-0 w-32">JENIS BARANG</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white border-0 w-32">KATEGORI</th>
                                <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white border-0 w-16">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const form = $("#formPO");
        let baris = {{ count($detail) }};

        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ',',
            });
        }

        if (typeof flatpickr !== 'undefined') {
            $(".flatpickr-datepmb").flatpickr({
                dateFormat: "Y-m-d"
            });
        }

        // Initialize Select2 dropdowns
        $('.select2Kodesupplier').select2({ placeholder: 'Pilih Supplier', allowClear: true, width: '100%' });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $("#btnSimpan").html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan Perubahan...</span>
            `);
        }

        function resetForm() {
            form.find("#kode_barang").val("");
            form.find("#nama_barang").val("");
            form.find("#jumlah").val("");
            form.find("#harga").val("");
            form.find("#keterangan").val("");
        }

        function loadTablebarang(kode_group = "000") {
            $('#tabelbarang').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'asc']],
                ajax: `/barangpembelian/${kode_group}/getbarangjson`,
                bAutoWidth: false,
                bDestroy: true,
                columns: [
                    { data: 'kode_barang', name: 'kode_barang', orderable: true, searchable: true },
                    { data: 'namabarang', name: 'nama_barang', orderable: true, searchable: true },
                    { data: 'satuan', name: 'satuan', orderable: true, searchable: false },
                    { data: 'jenisbarang', name: 'jenisbarang', orderable: true, searchable: false },
                    { data: 'nama_kategori', name: 'nama_kategori', orderable: true, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                drawCallback: function() {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        $("#nama_barang").click(function(e) {
            loadTablebarang("000");
            $("#modalBarang").modal("show");
        });

        $('#tabelbarang tbody').on('click', '.pilihBarang', function(e) {
            e.preventDefault();
            const kode_barang = $(this).attr('kode_barang');
            const nama_barang = $(this).attr('nama_barang');

            form.find("#kode_barang").val(kode_barang);
            form.find("#nama_barang").val(nama_barang);
            clearInlineError(form.find("#nama_barang"));
            $("#modalBarang").modal("hide");
            form.find("#jumlah").focus();
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
                sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
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

        function calculateTotal() {
            let grandTotal = 0;
            $('.totalharga').each(function() {
                grandTotal += parseFloat(convertNumber($(this).text())) || 0;
            });
            $('#grandtotal').text(numberFormat(grandTotal, '2', ',', '.'));
            $('#grandtotal_text').text(numberFormat(grandTotal, '2', ',', '.'));
        }

        // Initialize total calculation on load
        calculateTotal();

        function showInlineError($el, msg) {
            clearInlineError($el);
            $el.addClass("border-rose-500 ring-1 ring-rose-500");
            
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

        function addBarang() {
            const kode_barang = form.find("#kode_barang").val();
            const nama_barang = form.find("#nama_barang").val();
            const jumlah = form.find("#jumlah").val();
            const harga = form.find("#harga").val();
            const keterangan = form.find("#keterangan").val();

            if (!kode_barang) {
                showInlineError(form.find("#nama_barang"), "Barang wajib dipilih!");
                return;
            }
            if (!jumlah || convertNumber(jumlah) === 0) {
                showInlineError(form.find("#jumlah"), "Qty barang wajib diisi!");
                return;
            }
            if (!harga || convertNumber(harga) === 0) {
                showInlineError(form.find("#harga"), "Harga satuan wajib diisi!");
                return;
            }

            baris = baris + 1;
            let jml = convertNumber(jumlah);
            let hrg = convertNumber(harga);
            let total = parseFloat(jml) * parseFloat(hrg);
            let totalFormatted = numberFormat(total, '2', ',', '.');

            let barang = `
            <tr id="index_${baris}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                    <input type="hidden" name="kode_barang_item[]" value="${kode_barang}" />
                    <input type="hidden" name="jumlah_item[]" value="${jumlah}" />
                    <input type="hidden" name="harga_item[]" value="${harga}" />
                    <input type="hidden" name="keterangan_item[]" value="${keterangan}" />
                    ${kode_barang}
                </td>
                <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">${nama_barang}</td>
                <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">${keterangan || '-'}</td>
                <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-200">${jumlah}</td>
                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">${harga}</td>
                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100 totalharga">${totalFormatted}</td>
                <td class="py-1.5 px-3 text-center">
                    <a href="#" id="index_${baris}" class="delete w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs" title="Hapus Barang">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </a>
                </td>
            </tr>`;

            $('#loadbarang').append(barang);
            $("#cardTabelBarang").removeClass("border-rose-500 ring-1 ring-rose-500");
            $("#error_rincian_barang").addClass("hidden");
            if (typeof lucide !== 'undefined') lucide.createIcons();
            calculateTotal();
            resetForm();
        }

        form.find("#btnTambahbarang").click(function(e) {
            e.preventDefault();
            addBarang();
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            $(`#${id}`).remove();
            calculateTotal();
        });

        // Toggle Agreement checkbox
        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").removeClass('hidden');
            } else {
                form.find("#saveButton").addClass('hidden');
            }
        });

        // Form Submit Validation
        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val().trim();
            const kode_supplier = form.find("#kode_supplier").val();

            let hasError = false;

            if (!tanggal) {
                showInlineError(form.find("#tanggal"), "Tanggal PO wajib diisi!");
                hasError = true;
            }
            if (!kode_supplier) {
                showInlineError(form.find("#kode_supplier"), "Supplier wajib dipilih!");
                hasError = true;
            }
            if ($('#loadbarang tr').length === 0) {
                $("#cardTabelBarang").addClass("border-rose-500 ring-1 ring-rose-500");
                $("#error_rincian_barang").removeClass("hidden");
                if (typeof lucide !== 'undefined') lucide.createIcons();
                hasError = true;
            } else {
                $("#cardTabelBarang").removeClass("border-rose-500 ring-1 ring-rose-500");
                $("#error_rincian_barang").addClass("hidden");
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
