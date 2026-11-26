@extends('layouts.app')
@section('titlepage', 'Buat Form Permintaan Barang (FPB)')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('fpb.index') }}" class="text-slate-400 hover:text-[#284c9a] transition">FPB</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat FPB</span>
    </div>
@endsection

@section('content')
<div class="space-y-3">
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between gap-2">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Form Permintaan Barang (FPB)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Filter faktur penjualan salesman, ceklis faktur, dan rekap barang otomatis.</p>
        </div>
        <a href="{{ route('fpb.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center gap-1.5 shrink-0 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('fpb.store') }}" method="POST" id="formFpb" autocomplete="off" class="space-y-3">
        @csrf

        <!-- CARD 1: HEADER FPB -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5">
            <div class="flex items-center gap-2 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                <i data-lucide="clipboard-list" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Informasi FPB</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                <div>
                    <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tanggal FPB <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                    </div>
                </div>

                @if ($isPst)
                    <div>
                        <label for="kode_cabang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Cabang <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_cabang" id="kode_cabang"
                                class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                <option value="">Pilih Cabang</option>
                                @foreach ($cabang as $c)
                                    <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="kode_cabang" id="kode_cabang" value="{{ $userCabang }}">
                @endif

                <div>
                    <label for="kode_salesman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Salesman <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_salesman" id="kode_salesman" class="select2Salesman w-full">
                            <option value="">Pilih Salesman</option>
                            @foreach ($salesman as $s)
                                <option value="{{ $s->kode_salesman }}">{{ $s->nama_salesman }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="area_rute" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Area / Rute</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="area_rute" id="area_rute" placeholder="Contoh: Sindangkasih - Ciawi"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>

                <div>
                    <label for="kode_driver" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Sopir (1 Orang) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_driver" id="kode_driver" class="select2Driver w-full">
                            <option value="">Pilih Sopir</option>
                            @foreach ($drivers as $dr)
                                <option value="{{ $dr->kode_driver_helper }}">{{ $dr->nama_driver_helper }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="kode_kendaraan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. Kendaraan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="car" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_kendaraan" id="kode_kendaraan" class="select2Kendaraan w-full">
                            <option value="">Pilih Kendaraan</option>
                            @foreach ($kendaraan as $k)
                                <option value="{{ $k->kode_kendaraan }}">{{ $k->no_polisi }} ({{ $k->merek }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="kode_helper" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kenek / Helper (Bisa Lebih Dari 1)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_helper[]" id="kode_helper" multiple class="select2Helper w-full">
                            @foreach ($helpers as $hl)
                                <option value="{{ $hl->kode_driver_helper }}">{{ $hl->nama_driver_helper }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 2: FILTER PERIODE PENJUALAN & CEKLIST FAKTUR -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5 space-y-3">
            <div class="flex items-center justify-between gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="receipt" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Filter Periode Penjualan & Ceklis Faktur</h4>
                </div>
                <span id="badgeSelectedFaktur" class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-300 text-[10px] font-bold font-mono-num">
                    0 Faktur Dipilih
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-start">
                <div>
                    <label for="periode_dari" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Dari Tanggal Penjualan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" id="periode_dari" value="{{ date('Y-m-d') }}" placeholder="Dari Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                    </div>
                </div>

                <div>
                    <label for="periode_sampai" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Sampai Tanggal Penjualan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" id="periode_sampai" value="{{ date('Y-m-d') }}" placeholder="Sampai Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                    </div>
                </div>

                <div>
                    <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                    <button type="button" id="btnLoadFaktur"
                        class="w-full h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Tampilkan Faktur</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-200/80 dark:border-slate-800">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#1e3a78]">
                            <th class="py-2 px-3 text-center w-10">
                                <input type="checkbox" id="checkAllFaktur" class="rounded border-white/40 text-[#284c9a] focus:ring-0 cursor-pointer">
                            </th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">No. Faktur</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Tanggal</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Kode Pelanggan</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Nama Pelanggan</th>
                            @foreach ($produk as $p)
                                <th class="py-2 px-2 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center" title="{{ $p->nama_produk }}">{{ $p->kode_produk }}</th>
                            @endforeach
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center">Jenis</th>
                        </tr>
                    </thead>
                    <tbody id="fakturListBody" class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-200">
                        <tr>
                            <td colspan="{{ 6 + $produk->count() }}" class="py-6 text-center text-xs text-slate-400">
                                Pilih Salesman dan klik "Tampilkan Faktur" untuk memuat daftar faktur.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARD 3: PREVIEW REKAP BARANG YANG DIMINTA (OTOMATIS DARI DETAIL FAKTUR) -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="bg-[#284c9a] px-3.5 py-2 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="package-check" class="w-3.5 h-3.5 text-blue-200"></i>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Rekap Barang Permintaan (Dari Faktur Terpilih)</h4>
                </div>
                <span id="badgeTotalProduk" class="px-2 py-0.5 rounded-md bg-white/15 text-white text-[10px] font-bold font-mono-num">
                    0 Item
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#1e3a78]">
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center w-10">No</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Kode Barang</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Nama Barang</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right">Jumlah Permintaan (Dus)</th>
                        </tr>
                    </thead>
                    <tbody id="rekapProdukBody" class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-200">
                        <tr>
                            <td colspan="4" class="py-6 text-center text-xs text-slate-400">
                                Belum ada faktur yang diceklis.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARD 4: CATATAN & SUBMIT -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5 space-y-3">
            <div>
                <label for="catatan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Catatan</label>
                <div class="relative">
                    <div class="absolute top-2.5 left-2.5 pointer-events-none text-slate-400 z-10">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <textarea name="catatan" id="catatan" rows="2" placeholder="Catatan tambahan (opsional)..."
                        class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('fpb.index') }}"
                    class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center justify-center transition">
                    Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Simpan FPB</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formFpb");
        let fakturDataMap = {};
        const produkList = @json($produk->pluck('kode_produk'));

        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                allowInput: false
            });
        }

        $('.select2Salesman').select2({ placeholder: 'Pilih Salesman', width: '100%' });
        $('.select2Driver').select2({ placeholder: 'Pilih Sopir', width: '100%' });
        $('.select2Kendaraan').select2({ placeholder: 'Pilih Kendaraan', allowClear: true, width: '100%' });
        $('.select2Helper').select2({ placeholder: 'Pilih Kenek / Helper', width: '100%' });

        @if ($isPst)
        $('#kode_cabang').on('change', function() {
            const kodeCabang = $(this).val();
            const $salesman = $('#kode_salesman');
            const $driver = $('#kode_driver');
            const $helper = $('#kode_helper');
            const $kendaraan = $('#kode_kendaraan');

            $salesman.html('<option value="">Pilih Salesman</option>').trigger('change');
            $driver.html('<option value="">Pilih Sopir</option>').trigger('change');
            $helper.html('').trigger('change');
            $kendaraan.html('<option value="">Pilih Kendaraan</option>').trigger('change');

            if (!kodeCabang) return;

            $.get("{{ route('fpb.getbycabang') }}", { kode_cabang: kodeCabang }, function(res) {
                res.salesman.forEach(s => $salesman.append(`<option value="${s.kode_salesman}">${s.nama_salesman}</option>`));
                res.drivers.forEach(d => $driver.append(`<option value="${d.kode_driver_helper}">${d.nama_driver_helper}</option>`));
                res.helpers.forEach(h => $helper.append(`<option value="${h.kode_driver_helper}">${h.nama_driver_helper}</option>`));
                res.kendaraan.forEach(k => $kendaraan.append(`<option value="${k.kode_kendaraan}">${k.no_polisi} (${k.merek})</option>`));
                $salesman.trigger('change');
                $driver.trigger('change');
                $helper.trigger('change');
                $kendaraan.trigger('change');
            });
        });
        @endif

        function formatIndo(num) {
            const rounded = parseFloat(num.toFixed(2));
            const parts = rounded.toString().split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return parts.join(',');
        }

        function updateRekapProduk() {
            const checkedFakturs = [];
            $('.faktur-checkbox:checked').each(function() {
                checkedFakturs.push($(this).val());
            });

            $('#badgeSelectedFaktur').text(`${checkedFakturs.length} Faktur Dipilih`);

            const productTotals = {};
            checkedFakturs.forEach(function(noFaktur) {
                const items = fakturDataMap[noFaktur] || [];
                items.forEach(function(item) {
                    if (!productTotals[item.kode_produk]) {
                        productTotals[item.kode_produk] = {
                            kode_produk: item.kode_produk,
                            nama_produk: item.nama_produk,
                            qty_dus: 0
                        };
                    }
                    productTotals[item.kode_produk].qty_dus += parseFloat(item.qty_dus || 0);
                });
            });

            const keys = Object.keys(productTotals).sort();
            $('#badgeTotalProduk').text(`${keys.length} Item`);

            if (keys.length === 0) {
                $('#rekapProdukBody').html('<tr><td colspan="4" class="py-6 text-center text-xs text-slate-400">Belum ada faktur yang diceklis.</td></tr>');
                return;
            }

            let html = '';
            keys.forEach(function(kode, idx) {
                const p = productTotals[kode];
                html += `<tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                    <td class="py-1.5 px-3 text-center font-mono-num font-bold">${idx + 1}</td>
                    <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">${p.kode_produk}</td>
                    <td class="py-1.5 px-3 font-semibold">${p.nama_produk}</td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-black">${formatIndo(p.qty_dus)}</td>
                </tr>`;
            });
            $('#rekapProdukBody').html(html);
        }

        $('#btnLoadFaktur').on('click', function() {
            const kodeSalesman = $('#kode_salesman').val();
            const dari = $('#periode_dari').val();
            const sampai = $('#periode_sampai').val();

            if (!kodeSalesman) {
                setFieldError($('#kode_salesman'), 'Pilih salesman terlebih dahulu.');
                $('#kode_salesman').focus();
                return;
            }

            $('#fakturListBody').html(`<tr><td colspan="${6 + produkList.length}" class="py-6 text-center text-xs text-slate-400">Memuat faktur penjualan...</td></tr>`);
            $('#checkAllFaktur').prop('checked', false);

            $.get("{{ route('fpb.getfaktur') }}", {
                kode_salesman: kodeSalesman,
                dari: dari,
                sampai: sampai
            }, function(res) {
                fakturDataMap = {};
                if (!res.fakturs || res.fakturs.length === 0) {
                    $('#fakturListBody').html(`<tr><td colspan="${6 + produkList.length}" class="py-6 text-center text-xs text-slate-400">Tidak ada faktur penjualan pada periode ini.</td></tr>`);
                    updateRekapProduk();
                    return;
                }

                let html = '';
                res.fakturs.forEach(function(f) {
                    fakturDataMap[f.no_faktur] = f.items;
                    const itemMap = {};
                    (f.items || []).forEach(function(it) {
                        itemMap[it.kode_produk] = parseFloat(it.qty_dus || 0);
                    });

                    let produkCells = '';
                    produkList.forEach(function(kode) {
                        const qty = itemMap[kode];
                        produkCells += `<td class="py-1.5 px-2 text-center font-mono-num">${qty ? formatIndo(qty) : ''}</td>`;
                    });

                    const badgeClass = f.jenis_transaksi === 'TUNAI'
                        ? 'bg-emerald-50 text-emerald-700'
                        : 'bg-blue-50 text-[#284c9a]';
                    html += `<tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 cursor-pointer faktur-row">
                        <td class="py-1.5 px-3 text-center">
                            <input type="checkbox" name="no_faktur[]" value="${f.no_faktur}"
                                class="faktur-checkbox rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a] cursor-pointer">
                        </td>
                        <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">${f.no_faktur}</td>
                        <td class="py-1.5 px-3 font-mono-num">${f.tanggal}</td>
                        <td class="py-1.5 px-3 font-mono-num">${f.kode_pelanggan}</td>
                        <td class="py-1.5 px-3 font-semibold">${f.nama_pelanggan}</td>
                        ${produkCells}
                        <td class="py-1.5 px-3 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold ${badgeClass}">${f.jenis_transaksi}</span>
                        </td>
                    </tr>`;
                });
                $('#fakturListBody').html(html);
                updateRekapProduk();
            });
        });

        $('#checkAllFaktur').on('change', function() {
            $('.faktur-checkbox').prop('checked', this.checked);
            updateRekapProduk();
        });

        $(document).on('change', '.faktur-checkbox', function() {
            const total = $('.faktur-checkbox').length;
            const checked = $('.faktur-checkbox:checked').length;
            $('#checkAllFaktur').prop('checked', total > 0 && total === checked);
            updateRekapProduk();
        });

        function setFieldError($el, msg) {
            const $wrap = $el.closest('.relative');
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $wrap.find('> div:first-child').removeClass('text-slate-400').addClass('text-rose-500');
            $wrap.parent().find('.field-error-msg').remove();
            $wrap.after(`<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>${msg}</span></p>`);
        }

        function clearFieldError($el) {
            const $wrap = $el.closest('.relative');
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $wrap.find('> div:first-child').removeClass('text-rose-500').addClass('text-slate-400');
            $wrap.parent().find('.field-error-msg').remove();
        }

        $form.find('input, select').on('input change select2:select', function() {
            clearFieldError($(this));
        });

        $form.on('submit', function(e) {
            let firstError = null;
            const required = [
                @if ($isPst)
                { id: '#kode_cabang', msg: 'Cabang wajib dipilih.' },
                @endif
                { id: '#tanggal', msg: 'Tanggal FPB wajib diisi.' },
                { id: '#kode_salesman', msg: 'Salesman wajib dipilih.' },
                { id: '#kode_driver', msg: 'Sopir wajib dipilih.' }
            ];

            required.forEach(function(f) {
                const $el = $form.find(f.id);
                if (!($el.val() || '').trim()) {
                    setFieldError($el, f.msg);
                    if (!firstError) firstError = $el;
                }
            });

            if (firstError) {
                e.preventDefault();
                firstError.focus();
                return false;
            }

            if ($('.faktur-checkbox:checked').length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: 'Faktur Belum Dipilih',
                    text: 'Silakan tampilkan dan ceklis minimal 1 faktur penjualan.',
                    icon: 'warning'
                });
                return false;
            }

            const $btn = $("#btnSimpan");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span>Menyimpan...</span>`);
        });
    });
</script>
@endpush
