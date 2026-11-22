@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $canFilterCabang = auth()->user()->hasAnyRole($roles_show_cabang);
@endphp

<form action="{{ route('dpb.update', Crypt::encrypt($dpb->no_dpb)) }}" method="POST" id="formDPBEdit" class="space-y-2.5" autocomplete="off">
    @csrf
    @method('PUT')

    <!-- SECTION PARAMETER HEADER -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-2.5 sm:p-3">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start">
            
            <!-- LEFT COLUMN: DOKUMEN & KENDARAAN (COL-SPAN-6) -->
            <div class="lg:col-span-6 space-y-2.5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                    
                    <!-- No DPB -->
                    <div class="field-group">
                        <label for="no_dpb" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            No. DPB
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="barcode" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="no_dpb" id="no_dpb" value="{{ $dpb->no_dpb }}" readonly
                                class="w-full h-[36px] pl-9 pr-3 text-xs font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 cursor-not-allowed">
                        </div>
                    </div>

                    @if ($canFilterCabang)
                        <!-- Cabang (Select2 V2) -->
                        <div class="field-group">
                            <label for="kode_cabang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                Cabang <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                    <i data-lucide="building-2" class="w-4 h-4"></i>
                                </div>
                                <select name="kode_cabang" id="kode_cabang"
                                    class="select2Kodecabang select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}" {{ $dpb->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                                            {{ strtoupper($c->nama_cabang) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="kode_cabang" id="kode_cabang" value="{{ $dpb->kode_cabang }}">
                    @endif

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                    
                    <!-- Salesman -->
                    <div class="field-group">
                        <label for="kode_salesman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Salesman <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <select name="kode_salesman" id="kode_salesman"
                                class="select2Kodesalesman select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                <option value="">Pilih Salesman</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kendaraan -->
                    <div class="field-group">
                        <label for="kode_kendaraan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Kendaraan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                <i data-lucide="truck" class="w-4 h-4"></i>
                            </div>
                            <select name="kode_kendaraan" id="kode_kendaraan"
                                class="select2Kodekendaraan select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                <option value="">Pilih Kendaraan</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Tujuan -->
                <div class="field-group">
                    <label for="tujuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tujuan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="tujuan" id="tujuan" value="{{ $dpb->tujuan }}" placeholder="Wilayah / Kota Tujuan"
                            class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    </div>
                </div>

                <!-- Info Hint -->
                <div class="p-2.5 rounded-lg bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200/60 dark:border-blue-800/60 flex items-start gap-2 text-[11px] text-blue-700 dark:text-blue-300">
                    <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5 text-blue-500"></i>
                    <span><b>Pengembalian:</b> Sisa Order, Retur / Reject Pasar. &nbsp;|&nbsp; <b>Barang Keluar:</b> Penjualan, Ganti Barang, Promo, Pelunasan.</span>
                </div>
            </div>

            <!-- RIGHT COLUMN: DRIVER & HELPER (COL-SPAN-6) -->
            <div class="lg:col-span-6 space-y-2.5">
                
                <!-- Driver -->
                <div class="field-group">
                    <label for="kode_driver" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Driver
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                            <i data-lucide="car" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_driver" id="kode_driver"
                            class="select2Kodedriver select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Pilih Driver</option>
                        </select>
                    </div>
                </div>

                <!-- Tambah Helper Baris -->
                <div class="grid grid-cols-12 gap-2 items-start">
                    <div class="col-span-9 field-group">
                        <label for="kode_helper" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Helper
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                <i data-lucide="users" class="w-4 h-4"></i>
                            </div>
                            <select name="helper" id="kode_helper"
                                class="select2Kodehelper select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                <option value="">Pilih Helper</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                        <button type="button" id="tambahhelper"
                            class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Tambah</span>
                        </button>
                    </div>
                </div>

                <!-- Tabel Detail Helper -->
                <div class="rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xs">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabledetailhelper">
                        <thead>
                            <tr class="bg-[#284c9a] text-white">
                                <th colspan="4" class="py-1.5 px-3 font-bold uppercase tracking-wider text-white text-[11px]">Daftar Helper Terlampir</th>
                            </tr>
                            <tr class="bg-[#1e3a78] text-white text-[10.5px]">
                                <th class="py-1.5 px-3 w-24">KODE</th>
                                <th class="py-1.5 px-3">NAMA HELPER</th>
                                <th class="py-1.5 px-3 text-right w-24">KUANTITAS</th>
                                <th class="py-1.5 px-2 text-center w-10">#</th>
                            </tr>
                        </thead>
                        <tbody id="loaddetailhelper" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                            @foreach ($driverhelper as $dh)
                                <tr id="index_{{ $dh->kode_driver_helper }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                    <td class="py-1 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                        <input type="hidden" name="kodehelper[]" value="{{ $dh->kode_driver_helper }}"/>
                                        {{ $dh->kode_driver_helper }}
                                    </td>
                                    <td class="py-1 px-3 font-medium text-slate-800 dark:text-slate-200">
                                        {{ $dh->nama_driver_helper }}
                                    </td>
                                    <td class="py-1 px-3 text-right">
                                        <input type="text" name="qtyhelper[]" value="{{ formatAngkaDesimal3($dh->jumlah) }}"
                                            class="qtyhelper w-20 text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500">
                                    </td>
                                    <td class="py-1 px-2 text-center">
                                        <button type="button" kode_helper="{{ $dh->kode_driver_helper }}"
                                            class="delete w-5 h-5 rounded bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Jenis Perhitungan Helper -->
                <div class="field-group">
                    <label for="jenis_perhitungan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Jenis Perhitungan Helper
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calculator" class="w-4 h-4"></i>
                        </div>
                        <select name="jenis_perhitungan" id="jenis_perhitungan"
                            class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Jenis Perhitungan</option>
                            <option value="P" {{ $dpb->jenis_perhitungan == 'P' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="Q" {{ $dpb->jenis_perhitungan == 'Q' ? 'selected' : '' }}>Quantity (Manual)</option>
                            <option value="R" {{ $dpb->jenis_perhitungan == 'R' ? 'selected' : '' }}>Dibagi Rata (Otomatis)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[380px]">
            <table class="w-full min-w-full text-xs text-left border-collapse whitespace-nowrap" id="tabledpb">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Category Groupings -->
                    <tr class="bg-[#284c9a] text-white">
                        <th rowspan="2" class="py-2 px-2 text-center w-10 font-bold uppercase tracking-wider text-white border-r border-blue-800/60">NO</th>
                        <th rowspan="2" class="py-2 px-2 text-center w-20 font-bold uppercase tracking-wider text-white border-r border-blue-800/60">KODE</th>
                        <th rowspan="2" class="py-2 px-3 font-bold uppercase tracking-wider text-white border-r border-blue-800/60 min-w-[200px]">NAMA PRODUK</th>
                        <th colspan="3" class="py-2 px-2 text-center font-bold uppercase tracking-wider text-white border-r border-blue-800/60 bg-[#284c9a]">
                            PENGAMBILAN
                        </th>
                        <th colspan="3" class="py-2 px-2 text-center font-bold uppercase tracking-wider text-white border-r border-blue-800/60 bg-emerald-800">
                            PENGEMBALIAN
                        </th>
                        <th colspan="4" class="py-2 px-2 text-center font-bold uppercase tracking-wider text-white bg-rose-900">
                            BARANG KELUAR
                        </th>
                    </tr>
                    <!-- Row 2: Sub-column Dates & Headers -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <!-- Pengambilan Tanggal -->
                        <th colspan="3" class="py-1 px-1.5 border-r border-blue-800/60 bg-[#1e3a78] text-center">
                            <input type="text" name="tanggal_ambil" id="tanggal_ambil" value="{{ $dpb->tanggal_ambil }}" placeholder="Tgl Ambil" readonly
                                class="flatpickr-date w-full h-[26px] px-2 text-center text-xs font-bold rounded bg-blue-950/80 text-white border border-blue-700/60 focus:outline-hidden">
                        </th>
                        <!-- Pengembalian Tanggal -->
                        <th colspan="3" class="py-1 px-1.5 border-r border-blue-800/60 bg-emerald-900 text-center">
                            <input type="text" name="tanggal_kembali" id="tanggal_kembali" value="{{ !empty($dpb->tanggal_kembali) ? $dpb->tanggal_kembali : '' }}" placeholder="Tgl Kembali" readonly
                                class="flatpickr-date w-full h-[26px] px-2 text-center text-xs font-bold rounded bg-emerald-950 text-emerald-200 border border-emerald-700/60 focus:outline-hidden">
                        </th>
                        <!-- Barang Keluar Header Placeholder -->
                        <th colspan="4" class="py-1 px-1.5 bg-rose-950 text-center text-[10.5px] font-bold text-rose-200">
                            PENJUALAN & DISTRIBUSI
                        </th>
                    </tr>
                    <!-- Row 3: Unit Titles (Dus, Pack, Pcs, Desimal) -->
                    <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-[10.5px] border-y border-slate-200 dark:border-slate-700 font-bold">
                        <th class="py-1 px-2 text-center" colspan="3">&nbsp;</th>
                        <th class="py-1 px-1 text-right w-20">DUS</th>
                        <th class="py-1 px-1 text-right w-20">PACK</th>
                        <th class="py-1 px-1 text-right w-20 border-r border-slate-200 dark:border-slate-700">PCS</th>
                        <th class="py-1 px-1 text-right w-20 bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400">DUS</th>
                        <th class="py-1 px-1 text-right w-20 bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400">PACK</th>
                        <th class="py-1 px-1 text-right w-20 bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-r border-slate-200 dark:border-slate-700">PCS</th>
                        <th class="py-1 px-1 text-right w-20 bg-rose-50/50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400">DUS</th>
                        <th class="py-1 px-1 text-right w-20 bg-rose-50/50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400">PACK</th>
                        <th class="py-1 px-1 text-right w-20 bg-rose-50/50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400">PCS</th>
                        <th class="py-1 px-2 text-right w-24 bg-rose-50/50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 font-mono-num">DESIMAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php $totalbarangkeluar = 0; @endphp
                    @foreach ($produk as $d)
                        @php
                            $ambil = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_ambil));
                            $ambil_dus = $ambil[0];
                            $ambil_pack = $ambil[1];
                            $ambil_pcs = $ambil[2];

                            $kembali = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_kembali));
                            $kembali_dus = $kembali[0];
                            $kembali_pack = $kembali[1];
                            $kembali_pcs = $kembali[2];

                            $keluar = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_penjualan));
                            $keluar_dus = $keluar[0];
                            $keluar_pack = $keluar[1];
                            $keluar_pcs = $keluar[2];

                            $jumlah_keluar = $keluar_dus * $d->isi_pcs_dus + $keluar_pack * $d->isi_pcs_pack + $keluar_pcs;
                            $barangkeluar_dus = ROUND($jumlah_keluar / $d->isi_pcs_dus, 3);
                            $totalbarangkeluar += $barangkeluar_dus;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1 px-2 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1 px-2 text-center">
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
                                <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-1 px-3 font-medium text-slate-800 dark:text-slate-200 min-w-[200px]">
                                {{ $d->nama_produk }}
                            </td>
                            
                            <!-- Pengambilan -->
                            <td class="py-1 px-1 text-right w-20">
                                <input type="text" name="jml_ambil_dus[]" value="{{ formatAngka($ambil_dus) }}"
                                    class="money w-full h-[28px] text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500">
                            </td>
                            <td class="py-1 px-1 text-right w-20">
                                <input type="text" name="jml_ambil_pack[]" value="{{ formatAngka($ambil_pack) }}" {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}
                                    class="money w-full h-[28px] text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 {{ empty($d->isi_pcs_pack) ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-400 cursor-not-allowed' : 'bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500' }}">
                            </td>
                            <td class="py-1 px-1 text-right w-20 border-r border-slate-200 dark:border-slate-700">
                                <input type="text" name="jml_ambil_pcs[]" value="{{ formatAngka($ambil_pcs) }}"
                                    class="money w-full h-[28px] text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500">
                            </td>

                            <!-- Pengembalian -->
                            <td class="py-1 px-1 text-right w-20 bg-emerald-50/20 dark:bg-emerald-950/10">
                                <input type="text" name="jml_kembali_dus[]" value="{{ formatAngka($kembali_dus) }}"
                                    class="money w-full h-[28px] text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-emerald-200 dark:border-emerald-800 bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 focus:outline-hidden focus:border-emerald-500">
                            </td>
                            <td class="py-1 px-1 text-right w-20 bg-emerald-50/20 dark:bg-emerald-950/10">
                                <input type="text" name="jml_kembali_pack[]" value="{{ formatAngka($kembali_pack) }}" {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}
                                    class="money w-full h-[28px] text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-emerald-200 dark:border-emerald-800 {{ empty($d->isi_pcs_pack) ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-400 cursor-not-allowed' : 'bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 focus:outline-hidden focus:border-emerald-500' }}">
                            </td>
                            <td class="py-1 px-1 text-right w-20 bg-emerald-50/20 dark:bg-emerald-950/10 border-r border-slate-200 dark:border-slate-700">
                                <input type="text" name="jml_kembali_pcs[]" value="{{ formatAngka($kembali_pcs) }}"
                                    class="money w-full h-[28px] text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-emerald-200 dark:border-emerald-800 bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 focus:outline-hidden focus:border-emerald-500">
                            </td>

                            <!-- Barang Keluar -->
                            <td class="py-1 px-1 text-right w-20 bg-rose-50/20 dark:bg-rose-950/10">
                                <input type="text" name="jml_keluar_dus[]" id="jml_keluar_dus" value="{{ formatAngka($keluar_dus) }}"
                                    class="money jml_keluar_dus w-full h-[28px] text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-rose-200 dark:border-rose-800 bg-white dark:bg-slate-900 text-rose-700 dark:text-rose-400 focus:outline-hidden focus:border-rose-500">
                            </td>
                            <td class="py-1 px-1 text-right w-20 bg-rose-50/20 dark:bg-rose-950/10">
                                <input type="text" name="jml_keluar_pack[]" id="jml_keluar_pack" value="{{ formatAngka($keluar_pack) }}" {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}
                                    class="money jml_keluar_pack w-full h-[28px] text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-rose-200 dark:border-rose-800 {{ empty($d->isi_pcs_pack) ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-400 cursor-not-allowed' : 'bg-white dark:bg-slate-900 text-rose-700 dark:text-rose-400 focus:outline-hidden focus:border-rose-500' }}">
                            </td>
                            <td class="py-1 px-1 text-right w-20 bg-rose-50/20 dark:bg-rose-950/10">
                                <input type="text" name="jml_keluar_pcs[]" id="jml_keluar_pcs" value="{{ formatAngka($keluar_pcs) }}"
                                    class="money jml_keluar_pcs w-full h-[28px] text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-rose-200 dark:border-rose-800 bg-white dark:bg-slate-900 text-rose-700 dark:text-rose-400 focus:outline-hidden focus:border-rose-500">
                            </td>
                            <td class="py-1 px-2 text-right w-24 bg-rose-50/20 dark:bg-rose-950/10">
                                <input type="hidden" class="subtotal" value="{{ $barangkeluar_dus }}">
                                <span class="showDesimalbarangkeluar font-mono-num font-bold text-rose-700 dark:text-rose-400">
                                    {{ formatAngkaDesimal3($barangkeluar_dus) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 font-bold text-xs sticky bottom-0 z-10">
                    <tr>
                        <td colspan="12" class="py-2.5 px-3 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            Total Barang Keluar (Dus):
                        </td>
                        <td class="py-2.5 px-3 text-right">
                            <input type="hidden" class="grandtotal" id="grandtotal" name="grandtotal" value="{{ $totalbarangkeluar }}">
                            <span id="showTotalbarangkeluar" class="font-mono-num text-base font-black text-rose-600 dark:text-rose-400">
                                {{ formatAngkaDesimal3($totalbarangkeluar) }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-1">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanDpbEdit"
            class="inline-flex items-center justify-center gap-2 h-[34px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Perubahan DPB</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $('#formDPBEdit');
        const $btnSimpan = $('#btnSimpanDpbEdit');

        // Masking
        if (typeof $.fn.maskMoney !== 'undefined') {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        // Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }]
        });

        // Initialize Select2 dropdowns
        const selectConfigs = [
            { el: '.select2Kodecabang', placeholder: 'Pilih Cabang' },
            { el: '.select2Kodesalesman', placeholder: 'Pilih Salesman' },
            { el: '.select2Kodekendaraan', placeholder: 'Pilih Kendaraan' },
            { el: '.select2Kodedriver', placeholder: 'Pilih Driver' },
            { el: '.select2Kodehelper', placeholder: 'Pilih Helper' }
        ];

        selectConfigs.forEach(cfg => {
            const $target = $form.find(cfg.el);
            if ($target.length) {
                $target.select2({
                    placeholder: cfg.placeholder,
                    allowClear: true,
                    width: '100%'
                });
            }
        });

        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? '.' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? ',' : dec_point,
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

        // Row calculations for Barang Keluar
        const $tblrows = $("#tabledpb tbody tr");
        $tblrows.each(function() {
            const $tblrow = $(this);
            $tblrow.find('.jml_keluar_dus, .jml_keluar_pack, .jml_keluar_pcs').on('keyup keydown change', function() {
                const jenis_perhitungan = $form.find("#jenis_perhitungan").val();
                let jml_keluar_dus = $tblrow.find("[id=jml_keluar_dus]").val() || '0';
                let jml_keluar_pack = $tblrow.find("[id=jml_keluar_pack]").val() || '0';
                let jml_keluar_pcs = $tblrow.find("[id=jml_keluar_pcs]").val() || '0';
                let isi_pcs_dus = $tblrow.find("[id=isi_pcs_dus]").val() || '0';
                let isi_pcs_pack = $tblrow.find("[id=isi_pcs_pack]").val() || '0';

                jml_keluar_dus = parseInt(jml_keluar_dus.replace(/\./g, '')) || 0;
                jml_keluar_pack = parseInt(jml_keluar_pack.replace(/\./g, '')) || 0;
                jml_keluar_pcs = parseInt(jml_keluar_pcs.replace(/\./g, '')) || 0;
                isi_pcs_dus = parseInt(isi_pcs_dus.replace(/\./g, '')) || 1;
                isi_pcs_pack = parseInt(isi_pcs_pack.replace(/\./g, '')) || 0;

                const subTotal = ((jml_keluar_dus * isi_pcs_dus) + (jml_keluar_pack * isi_pcs_pack) + jml_keluar_pcs) / isi_pcs_dus;
                if (!isNaN(subTotal)) {
                    $tblrow.find('.subtotal').val(subTotal.toFixed(3));
                    $tblrow.find('.showDesimalbarangkeluar').text(numberFormat(subTotal, 3, ',', '.'));

                    let grandTotal = 0;
                    $form.find(".subtotal").each(function() {
                        const stval = parseFloat($(this).val());
                        grandTotal += isNaN(stval) ? 0 : stval;
                    });
                    $form.find('.grandtotal').val(grandTotal.toFixed(3));
                    $form.find('#showTotalbarangkeluar').text(numberFormat(grandTotal, 3, ',', '.'));
                }

                if (jenis_perhitungan === "R") {
                    generateqtyhelper();
                }
            });
        });

        function generateqtyhelper() {
            const totalbarangkeluar = $form.find("#grandtotal").val();
            const $qtyhelper = $form.find(".qtyhelper");
            const jenis_perhitungan = $form.find("#jenis_perhitungan").val();

            if (jenis_perhitungan === "P") {
                $qtyhelper.each(function() {
                    let val = $(this).val() == "" ? "0" : $(this).val();
                    let value = val.replaceAll('.', '').replaceAll(',', '.');
                    let persentase = totalbarangkeluar > 0 ? parseFloat(value) / parseFloat(totalbarangkeluar) * 100 : 0;
                    $(this).val(Math.round(persentase));
                });
                $qtyhelper.prop('readonly', false);
            } else if (jenis_perhitungan === "R") {
                let jmlhelper = $qtyhelper.length;
                if (jmlhelper > 0) {
                    const splitQty = parseFloat(totalbarangkeluar) / parseFloat(jmlhelper);
                    $qtyhelper.each(function() {
                        $(this).val(numberFormat(splitQty, 3, ',', '.'));
                    });
                }
                $qtyhelper.prop('readonly', true);
            } else if (jenis_perhitungan === "Q") {
                $qtyhelper.prop('readonly', false);
            }
        }

        $form.find("#jenis_perhitungan").on('change', function() {
            if ($(this).val() === "Q") {
                $form.find(".qtyhelper").val(0);
            }
            generateqtyhelper();
        });

        // Load Salesman & Kendaraan & Driver
        function getsalesmanbyCabang() {
            const kodeCabang = $form.find("#kode_cabang").val();
            const kodeSalesman = "{{ $dpb->kode_salesman }}";
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: kodeCabang, kode_salesman: kodeSalesman },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_salesman").html(respond).trigger('change.select2');
                }
            });
        }

        function getkendaraanbyCabang() {
            const kodeCabang = $form.find("#kode_cabang").val();
            const kodeKendaraan = "{{ $dpb->kode_kendaraan }}";
            $.ajax({
                type: 'POST',
                url: '/kendaraan/getkendaraanbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: kodeCabang, kode_kendaraan: kodeKendaraan },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_kendaraan").html(respond).trigger('change.select2');
                }
            });
        }

        function getdriverhelperbyCabang() {
            const kodeCabang = $form.find("#kode_cabang").val();
            $.ajax({
                type: 'POST',
                url: '/driverhelper/getdriverhelperbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: kodeCabang },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_helper").html(respond).trigger('change.select2');
                }
            });
        }

        function getdriverbyCabang() {
            const kodeCabang = $form.find("#kode_cabang").val();
            const kodeDriver = "{{ $driver != null ? $driver->kode_driver_helper : '' }}";
            $.ajax({
                type: 'POST',
                url: '/driverhelper/getdriverhelperbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: kodeCabang, kode_driver_helper: kodeDriver },
                cache: false,
                success: function(respond) {
                    $form.find("#kode_driver").html(respond).trigger('change.select2');
                }
            });
        }

        getsalesmanbyCabang();
        getkendaraanbyCabang();
        getdriverhelperbyCabang();
        getdriverbyCabang();

        $form.find("#kode_cabang").on('change', function() {
            getsalesmanbyCabang();
            getkendaraanbyCabang();
            getdriverhelperbyCabang();
            getdriverbyCabang();
        });

        // Add Helper
        function addHelper() {
            const $selectedOption = $form.find("#kode_helper option:selected");
            const kode_helper = $selectedOption.val();
            const nama_helper = $selectedOption.text();

            let helperRow = `
                <tr id="index_${kode_helper}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                    <td class="py-1 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                        <input type="hidden" name="kodehelper[]" value="${kode_helper}"/>
                        ${kode_helper}
                    </td>
                    <td class="py-1 px-3 font-medium text-slate-800 dark:text-slate-200">${nama_helper}</td>
                    <td class="py-1 px-3 text-right">
                        <input type="text" name="qtyhelper[]" value="0" class="qtyhelper w-20 text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500"/>
                    </td>
                    <td class="py-1 px-2 text-center">
                        <button type="button" kode_helper="${kode_helper}" class="delete w-5 h-5 rounded bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            $("#loaddetailhelper").append(helperRow);
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        $form.find("#tambahhelper").on('click', function(e) {
            e.preventDefault();
            const kode_helper = $form.find("#kode_helper").val();
            const cekdetail = $form.find('#tabledetailhelper').find('#index_' + kode_helper).length;
            const jenis_perhitungan = $form.find("#jenis_perhitungan").val();

            if (!kode_helper) {
                Swal.fire({
                    title: 'Pilih Helper',
                    text: 'Silakan pilih helper terlebih dahulu!',
                    icon: 'warning',
                    confirmButtonColor: '#284c9a'
                });
                return false;
            } else if (cekdetail > 0) {
                Swal.fire({
                    title: 'Helper Sudah Ada',
                    text: 'Helper ini sudah terdaftar dalam tabel!',
                    icon: 'warning',
                    confirmButtonColor: '#284c9a'
                });
                return false;
            } else {
                addHelper();
                if (jenis_perhitungan === "R") {
                    generateqtyhelper();
                }
            }
        });

        // Delete Helper Row
        $form.on('click', '.delete', function(e) {
            e.preventDefault();
            const kode_helper = $(this).attr("kode_helper");
            $form.find(`#index_${kode_helper}`).remove();
            if ($form.find("#jenis_perhitungan").val() === "R") {
                generateqtyhelper();
            }
        });

        // Form Submit
        $form.on('submit', function(e) {
            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Perubahan...</span>
            `);
        });
    });
</script>
