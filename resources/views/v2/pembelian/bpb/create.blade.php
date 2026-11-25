@extends('v2.layouts.app')
@section('titlepage', 'Input BPPB')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Pembelian</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('bpbpembelian.index') }}" class="text-slate-400 hover:text-brand-600 transition">BPPB</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Input BPPB</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Input BPPB (Bukti Permintaan &amp; Penerimaan Barang)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Buat pengajuan Bukti Permintaan &amp; Penerimaan Barang (BPPB) Pembelian baru.</p>
        </div>

        <a href="{{ route('bpbpembelian.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('bpbpembelian.store') }}" method="POST" id="formcreatebpb" class="space-y-4" autocomplete="off">
        @csrf

        <!-- CARD 1: INFORMASI TANGGAL & CARI BARANG (MODAL) -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
                
                <!-- Tanggal -->
                <div class="sm:col-span-6 field-group">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tanggal Pengajuan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}"
                            placeholder="Pilih Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                    </div>
                </div>

                <!-- Tombol Cari Barang via Modal -->
                <div class="sm:col-span-6">
                    <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                    <button type="button"
                        class="btnCariBarang w-full h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari Barang Lainnya (Katalog Master)</span>
                    </button>
                </div>

                <!-- Hidden Kode Supplier jika diperlukan backend -->
                <div class="hidden">
                    <select name="kode_supplier" class="select2KodeSupplier">
                        <option value=""></option>
                        @foreach ($supplier as $s)
                            <option value="{{ $s->kode_supplier }}">{{ textUpperCase($s->nama_supplier) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- FILTER SECTION: PENCARIAN & KATEGORI STOK BARANG (NO-LABEL ~36px) -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                <div class="sm:col-span-7 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" id="searchBarang"
                        placeholder="Cari kode atau nama barang..."
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                </div>
                <div class="sm:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    </div>
                    <select id="filterKategori"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->kode_kategori }}">{{ textUpperCase($k->nama_kategori) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- CARD 2: DAFTAR STOK BARANG (TERSEDIA UNTUK DIAJUKAN) -->
        <div class="rounded-xl overflow-hidden shadow-2xs border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
            <!-- Tabel Data Stok Barang -->
            <div class="overflow-x-auto max-h-[380px] overflow-y-auto">
                <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap" id="tableBarang">
                    <thead class="sticky top-0 z-10">
                        <!-- Baris 1: Strip Header Pacific Navy -->
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="7" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="package" class="w-3.5 h-3.5 text-blue-200"></i>
                                        <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Stok Barang Tersedia</span>
                                    </div>
                                    <span class="text-[10.5px] text-white/70 font-mono-num">
                                        Total: {{ count($barang) }} Item
                                    </span>
                                </div>
                            </th>
                        </tr>
                        <!-- Baris 2: Kolom Header -->
                        <tr class="bg-[#1e3a78] text-white text-[11px]">
                            <th class="py-2 px-3 text-center border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 w-12">
                                <input type="checkbox" id="checkAllBarang" class="w-4 h-4 rounded text-brand-600 border-slate-300 dark:border-slate-700 focus:ring-brand-500 cursor-pointer">
                            </th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 w-32">KODE</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 min-w-[200px]">NAMA BARANG</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 w-28">JENIS</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 min-w-[150px]">KATEGORI</th>
                            <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 w-20">SATUAN</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 w-28">SISA STOK</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @foreach ($barang as $b)
                            <tr data-kode_kategori="{{ $b->kode_kategori }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                <td class="py-1.5 px-3 text-center">
                                    <input type="checkbox" class="pilih-barang w-4 h-4 rounded text-brand-600 border-slate-300 dark:border-slate-700 focus:ring-brand-500 cursor-pointer"
                                        data-kode="{{ $b->kode_barang }}"
                                        data-nama="{{ $b->nama_barang }}"
                                        data-ket="{{ $b->keterangan }}"
                                        data-satuan="{{ $b->satuan }}"
                                        data-sisa="{{ $b->sisa }}">
                                </td>
                                <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                    {{ $b->kode_barang }}
                                </td>
                                <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                    {{ strtoupper($b->nama_barang) }}
                                </td>
                                <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400 font-mono-num">
                                    {{ strtoupper($b->kode_group) }}
                                </td>
                                <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                                    {{ strtoupper($b->nama_kategori) }}
                                </td>
                                <td class="py-1.5 px-3 text-center">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[10.5px] font-mono-num font-bold text-slate-600 dark:text-slate-400">
                                        {{ strtoupper($b->satuan) }}
                                    </span>
                                </td>
                                <td class="py-1.5 px-3 text-right">
                                    <input type="text"
                                        class="jumlah-barang number-separator w-24 h-[28px] px-2 text-right text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500"
                                        value="{{ $b->sisa }}" disabled>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARD 3: DAFTAR BARANG YANG DIAJUKAN (KERANJANG BPPB) -->
        <div class="rounded-xl overflow-hidden shadow-2xs border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
            <!-- Tabel Barang Terpilih -->
            <div class="overflow-x-auto min-h-[140px]">
                <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap" id="tabledetail">
                    <thead class="sticky top-0 z-10">
                        <!-- Baris 1: Strip Header Pacific Navy -->
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="5" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="list" class="w-3.5 h-3.5 text-blue-200"></i>
                                        <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Barang yang Diajukan</span>
                                    </div>
                                    <span class="text-[10.5px] text-white/70 font-mono-num" id="badgeDetailCount">
                                        Total: 0 Item
                                    </span>
                                </div>
                            </th>
                        </tr>
                        <!-- Baris 2: Kolom Header -->
                        <tr class="bg-[#1e3a78] text-white text-[11px]">
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 w-36">KODE</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 min-w-[220px]">NAMA BARANG</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 w-32 text-right">JUMLAH</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 border-r border-[#284c9a]/40 min-w-[200px]">KETERANGAN</th>
                            <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-[#284c9a]/40 w-16">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="loaddetail" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        <!-- Item diajukan akan dimunculkan di sini -->
                    </tbody>
                </table>
            </div>

            <!-- Empty State Notifikasi Rincian -->
            <div id="emptyDetailNotif" class="py-8 text-center text-slate-400 border-t border-slate-100 dark:border-slate-800">
                <div class="flex flex-col items-center justify-center gap-1.5">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-slate-300 dark:text-slate-600"></i>
                    <span class="text-xs">Belum ada barang yang dipilih untuk diajukan.</span>
                </div>
            </div>
        </div>

        <!-- SUBMIT / SIMPAN SECTION (RIGHT-ALIGNED) -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs space-y-3">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="agreement" class="agreement w-4 h-4 rounded-sm border-slate-300 text-[#284c9a] focus:ring-brand-500 cursor-pointer">
                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Yakin data transaksi BPPB ini sudah benar dan siap disimpan?</span>
            </label>

            <div id="saveButton" class="hidden pt-2 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('bpbpembelian.index') }}"
                        class="h-[36px] px-4 inline-flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
                        Batal
                    </a>
                    <button type="submit" id="btnSimpan"
                        class="w-full sm:w-auto px-6 h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-xs cursor-pointer">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                        <span>Simpan BPPB</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- STATIC BACKDROP MODAL KATALOG MASTER BARANG (V2) -->
<div id="modalBarang" class="fixed inset-0 z-50 hidden">
    <div id="modalBarangBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalBarangDialog"
            class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Katalog Master Barang Pembelian</h5>
                </div>
                <button type="button" id="btnCloseModalBarang"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-800">
                    <table class="w-full text-xs text-left" id="tabelbarang">
                        <thead class="bg-[#1e3a78] text-white text-[11px]">
                            <tr>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 w-28">KODE</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">NAMA BARANG</th>
                                <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-20">SATUAN</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 w-28">JENIS</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 w-32">KATEGORI</th>
                                <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 w-20">AKSI</th>
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
    $(function() {
        const formCreate = $("#formcreatebpb");
        let tableBarangDT = null;

        // Modal Katalog Barang Helpers
        const $modalBarang = $("#modalBarang");
        const $dialogBarang = $("#modalBarangDialog");

        function openModalBarang() {
            $modalBarang.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closeModalBarang() {
            $modalBarang.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            if (tableBarangDT) {
                tableBarangDT.clear().destroy();
                tableBarangDT = null;
            }
        }

        function bounceModalBarang() {
            $dialogBarang.addClass('scale-[1.015] shadow-2xl');
            setTimeout(() => $dialogBarang.removeClass('scale-[1.015] shadow-2xl'), 150);
        }

        $("#modalBarangBackdrop").on('click', function(e) {
            e.stopPropagation();
            bounceModalBarang();
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$modalBarang.hasClass('hidden')) {
                bounceModalBarang();
            }
        });

        $("#btnCloseModalBarang").on('click', closeModalBarang);

        function updateEmptyState() {
            const count = $('#loaddetail tr').length;
            $('#badgeDetailCount').text(`Total: ${count} Item`);
            if (count === 0) {
                $('#emptyDetailNotif').removeClass('hidden');
            } else {
                $('#emptyDetailNotif').addClass('hidden');
            }
        }
        updateEmptyState();

        // DataTables server-side master barang
        function loadTablebarang(kode_group = "000") {
            if (tableBarangDT) {
                tableBarangDT.clear().destroy();
                $('#tabelbarang tbody').empty();
            }

            tableBarangDT = $('#tabelbarang').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'asc']],
                ajax: `/barangpembelian/${kode_group}/getbarangjson`,
                bAutoWidth: false,
                destroy: true,
                columns: [
                    { data: 'kode_barang', name: 'kode_barang' },
                    { data: 'namabarang', name: 'nama_barang' },
                    { data: 'satuan', name: 'satuan', className: 'text-center' },
                    { data: 'jenisbarang', name: 'jenisbarang' },
                    { data: 'nama_kategori', name: 'nama_kategori' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<button type="button" class="h-6 px-2.5 rounded bg-[#284c9a] hover:bg-[#1e3a78] text-white text-[11px] font-bold pilihBarang cursor-pointer"
                                kode_barang="${row.kode_barang}"
                                nama_barang="${row.namabarang}">Pilih</button>`;
                        }
                    }
                ],
                rowCallback: function(row, data, index) {
                    if ($('#index_' + data.kode_barang).length > 0) {
                        $(row).addClass('bg-slate-100 opacity-60 pointer-events-none');
                    }
                }
            });
        }

        $('.btnCariBarang').on('click', function(e) {
            e.preventDefault();
            let id_user = "{{ Auth::user()->id }}";
            let kode_group;

            if (id_user == '74') {
                kode_group = "MKT";
            } else if (id_user == '54') {
                kode_group = "000";
            } else {
                kode_group = "{{ Auth::user()->kode_dept }}";
                if (kode_group == 'GDG') {
                    kode_group = 'GDL';
                }
            }

            loadTablebarang(kode_group);
            openModalBarang();
        });

        // Pilih Barang dari Modal
        $('#tabelbarang tbody').on('click', '.pilihBarang', function(e) {
            e.preventDefault();
            const kode = $(this).attr('kode_barang');
            const nama = $(this).attr('nama_barang');

            if ($('#index_' + kode).length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Barang sudah dipilih!',
                    text: 'Barang ini sudah ada dalam daftar pengajuan.',
                    confirmButtonColor: '#284c9a'
                });
                return;
            }

            let html = `
                <tr id="index_${kode}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                    <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                        <input type="hidden" name="kode_barang[]" value="${kode}">
                        ${kode}
                    </td>
                    <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                        ${nama.toUpperCase()}
                    </td>
                    <td class="py-1.5 px-3 text-right">
                        <input type="text"
                            class="number-separator jml-keranjang w-24 h-[28px] px-2 text-right text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500"
                            name="jml[]"
                            value="1">
                    </td>
                    <td class="py-1.5 px-3">
                        <input type="text"
                            class="w-full h-[28px] px-2.5 text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-1 focus:ring-brand-500"
                            name="ket[]"
                            placeholder="Keterangan pengajuan...">
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="hapus w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                            data-kode="${kode}" title="Hapus">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#loaddetail').append(html);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            updateEmptyState();
            closeModalBarang();
        });

        // Filter kategori in-page
        $("#filterKategori").on("change", function() {
            let kategori = $(this).val();
            $("#tableBarang tbody tr").each(function() {
                let rowKategori = $(this).data("kode_kategori");
                if (kategori === "" || rowKategori == kategori) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Search in-page
        $('#searchBarang').on('keyup', function() {
            let keyword = $(this).val().toLowerCase();
            $('#tableBarang tbody tr').each(function() {
                const row = $(this);
                if (row.hasClass('in-cart')) {
                    row.hide();
                    return;
                }
                row.toggle(row.text().toLowerCase().indexOf(keyword) > -1);
            });
        });

        // Pilih barang dari tabel in-page
        $(document).on('change', '.pilih-barang', function() {
            const row = $(this).closest('tr');
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            const satuan = $(this).data('satuan');
            const sisa = $(this).data('sisa');
            const jumlahInput = row.find('.jumlah-barang');
            const ket = $(this).data('ket');

            if (this.checked) {
                jumlahInput.prop('disabled', false).val(sisa);
                row.addClass('in-cart').hide();

                if ($('#index_' + kode).length === 0) {
                    let html = `
                    <tr id="index_${kode}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                        <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                            <input type="hidden" name="kode_barang[]" value="${kode}">
                            ${kode}
                        </td>
                        <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                            ${nama.toUpperCase()} <span class="text-slate-400 font-normal">(${satuan.toUpperCase()})</span>
                        </td>
                        <td class="py-1.5 px-3 text-right">
                            <input type="text"
                                class="number-separator jml-keranjang w-24 h-[28px] px-2 text-right text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500"
                                name="jml[]"
                                value="${sisa}"
                                data-sisa="${sisa}"
                                data-kode="${kode}">
                        </td>
                        <td class="py-1.5 px-3">
                            <input type="text"
                                class="w-full h-[28px] px-2.5 text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-1 focus:ring-brand-500"
                                name="ket[]"
                                value="${ket ?? ''}"
                                placeholder="Keterangan...">
                        </td>
                        <td class="py-1.5 px-3 text-center">
                            <button type="button" class="hapus w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                data-kode="${kode}" title="Hapus">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                            </button>
                        </td>
                    </tr>
                    `;
                    $('#loaddetail').append(html);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    updateEmptyState();
                }
            }
        });

        // Validasi batas sisa input jml-keranjang
        $(document).on('keyup change', '.jml-keranjang', function() {
            let value = $(this).val().replaceAll('.', '');
            let jumlah = parseInt(value || 0);
            const sisa = parseInt($(this).data('sisa'));

            if (sisa && jumlah > sisa) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Melebihi Sisa Stok!',
                    text: `Jumlah maksimal yang dapat diajukan adalah ${sisa}`,
                    confirmButtonColor: '#284c9a'
                });
                jumlah = sisa;
                $(this).val(sisa);
            }

            if (jumlah <= 0) {
                $(this).val(1);
            }
        });

        // Check All Barang
        $('#checkAllBarang').on('change', function() {
            const checked = this.checked;
            $('#tableBarang tbody tr').each(function() {
                const row = $(this);
                if (row.hasClass('in-cart') || !row.is(':visible')) return;
                const checkbox = row.find('.pilih-barang');
                if (checked && !checkbox.prop('checked')) {
                    checkbox.prop('checked', true).trigger('change');
                }
                if (!checked && checkbox.prop('checked')) {
                    checkbox.prop('checked', false);
                }
            });
        });

        // Hapus barang dari keranjang detail
        $(document).on('click', '.hapus', function(e) {
            e.preventDefault();
            const kode = $(this).data('kode');
            $('#index_' + kode).remove();

            const row = $(`.pilih-barang[data-kode="${kode}"]`).closest('tr');
            row.removeClass('in-cart').show().find('.pilih-barang').prop('checked', false);
            row.find('.jumlah-barang').prop('disabled', true).val('');

            updateEmptyState();
        });

        // Agreement checkbox & saveButton toggle
        formCreate.find('.agreement').change(function() {
            if (this.checked) {
                formCreate.find("#saveButton").removeClass('hidden');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            } else {
                formCreate.find("#saveButton").addClass('hidden');
            }
        });

        // Submit Validation
        formCreate.submit(function(e) {
            const tanggal = formCreate.find("#tanggal").val().trim();

            if (!tanggal) {
                e.preventDefault();
                Swal.fire({
                    title: "Perhatian",
                    text: "Tanggal pengajuan wajib diisi!",
                    icon: "warning",
                    confirmButtonColor: "#284c9a"
                });
                return false;
            }

            if ($('#loaddetail tr').length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: "Rincian Barang Kosong",
                    text: "Silakan pilih minimal satu barang untuk diajukan!",
                    icon: "warning",
                    confirmButtonColor: "#284c9a"
                });
                return false;
            }

            // Anti double submit
            const $btn = $("#btnSimpan");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan BPPB...</span>
            `);
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
