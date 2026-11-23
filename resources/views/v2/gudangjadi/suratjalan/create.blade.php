<form action="{{ route('suratjalan.store', Crypt::encrypt($pk->no_permintaan)) }}" method="POST" id="formcreateSuratjalan" autocomplete="off" class="space-y-3">
    @csrf
    <input type="hidden" id="cektutuplaporan" value="0">

    <!-- RINGKASAN DATA PERMINTAAN KIRIMAN (PK) - EXECUTIVE SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3 sm:p-3.5">
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-[#284c9a] dark:text-blue-400">Referensi Permintaan Kiriman</span>
                </div>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 border border-blue-200/60 dark:border-blue-900/50">
                <i data-lucide="info" class="w-3 h-3"></i>
                <span>Gudang Jadi</span>
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <!-- No. Permintaan -->
            <div class="flex flex-col justify-center">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">No. Permintaan</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-xs sm:text-sm tracking-tight truncate" title="{{ $pk->no_permintaan }}">
                    {{ $pk->no_permintaan }}
                </span>
            </div>

            <!-- Tanggal Permintaan -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Tanggal PK</span>
                <div class="flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                    <span class="font-bold text-slate-800 dark:text-slate-100 text-xs">
                        {{ DateToIndo($pk->tanggal) }}
                    </span>
                </div>
            </div>

            <!-- Cabang / Salesman -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Cabang / Salesman</span>
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-bold text-[11px] border border-blue-200/60 dark:border-blue-900/50">
                        <i data-lucide="building-2" class="w-3 h-3"></i>
                        <span>{{ textUpperCase($pk->nama_cabang) }}</span>
                    </span>
                    @if (!empty($pk->kode_salesman))
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-200/70 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[10.5px]">
                            <i data-lucide="user" class="w-3 h-3 text-slate-400"></i>
                            <span>{{ $pk->nama_salesman }}</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Keterangan PK -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Keterangan PK</span>
                <span class="text-slate-600 dark:text-slate-400 text-xs truncate" title="{{ $pk->keterangan }}">
                    {{ $pk->keterangan ?: '-' }}
                </span>
            </div>
        </div>
    </div>

    <!-- INFORMASI DOKUMEN & EKSPEDISI SURAT JALAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 space-y-2.5">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="truck" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Informasi Dokumen &amp; Ekspedisi</span>
        </div>

        <!-- Row 1: Dokumen & Tanggal -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            <!-- No. Surat Jalan (Col-span 4) -->
            <div class="sm:col-span-4 field-group">
                <label for="no_mutasi" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    No. Surat Jalan
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_mutasi" id="no_mutasi" readonly placeholder="(Auto Generated)"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#284c9a] dark:text-blue-400 cursor-not-allowed">
                </div>
            </div>

            <!-- No. Dokumen (Col-span 4) -->
            <div class="sm:col-span-4 field-group">
                <label for="no_dok" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    No. Dokumen <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_dok" id="no_dok" placeholder="Contoh: 123/SJ/2026"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tanggal Surat Jalan (Col-span 4) -->
            <div class="sm:col-span-4 field-group">
                <label for="tanggal" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5 whitespace-nowrap">
                    Tanggal Surat Jalan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
            </div>
        </div>

        <!-- Row 2: Ekspedisi & Kendaraan -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            <!-- Tujuan (Col-span 4) -->
            <div class="sm:col-span-4 field-group">
                <label for="kode_tujuan" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Tujuan Pengiriman <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_tujuan" id="kode_tujuan" class="Select2Kodetujuan select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Tujuan</option>
                        @foreach ($tujuan_angkutan as $d)
                            <option value="{{ $d->kode_tujuan }}" data-tarif="{{ formatAngka($d->tarif) }}">
                                {{ textUpperCase($d->tujuan) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Armada Angkutan (Col-span 4) -->
            <div class="sm:col-span-4 field-group">
                <label for="kode_angkutan" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Armada Angkutan
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_angkutan" id="kode_angkutan" class="select2Kodeangkutan select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Angkutan</option>
                        @foreach ($angkutan as $d)
                            <option value="{{ $d->kode_angkutan }}">{{ textUpperCase($d->nama_angkutan) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- No. Polisi (Col-span 4) -->
            <div class="sm:col-span-4 field-group">
                <label for="no_polisi" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    No. Polisi
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="car" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_polisi" id="no_polisi" placeholder="Contoh: D 1234 ABC"
                        class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>
        </div>

        <!-- Row 3: Biaya Ekspedisi -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            <!-- Tarif Angkutan (Col-span 4) -->
            <div class="sm:col-span-4 field-group">
                <label for="tarif" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Tarif Angkutan
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tarif" id="tarif" placeholder="0"
                        class="money w-full h-[34px] pl-8 pr-2.5 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tepung (Col-span 4) -->
            <div class="sm:col-span-4 field-group">
                <label for="tepung" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Tepung
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tepung" id="tepung" placeholder="0"
                        class="money w-full h-[34px] pl-8 pr-2.5 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Biaya Lain (BS) (Col-span 4) -->
            <div class="sm:col-span-4 field-group">
                <label for="bs" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Biaya Lain (BS)
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="bs" id="bs" placeholder="0"
                        class="money w-full h-[34px] pl-8 pr-2.5 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>
        </div>

        <!-- Row 4: Keterangan Tambahan (Col-span 12) -->
        <div class="field-group">
            <label for="keterangan" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                Keterangan Tambahan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan pengiriman surat jalan (opsional)"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>
    </div>

    <!-- INPUT PRODUK & JUMLAH (ANTI-LAYOUT SHIFT: ITEMS-START + SPACER LABEL) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Tambah Produk ke Surat Jalan</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-start">
            <!-- Pilih Produk (Col-span 7) -->
            <div class="sm:col-span-7 field-group">
                <label for="kode_produk" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Pilih Produk
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="box" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_produk" id="kode_produk" class="select2Kodeproduk select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Produk</option>
                        @foreach ($produk as $d)
                            <option value="{{ $d->kode_produk }}">{{ $d->kode_produk }} - {{ $d->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jumlah (Col-span 3) -->
            <div class="sm:col-span-3 field-group">
                <label for="jumlah" class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                    Jumlah
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="jumlah" id="jumlah" placeholder="0"
                        class="money w-full h-[34px] pl-8 pr-2.5 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tombol Tambah Produk (Col-span 2) with Anti-Layout Shift Spacer -->
            <div class="sm:col-span-2">
                <label class="hidden sm:block text-[11px] font-bold text-transparent mb-0.5 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="tambahproduk"
                    class="w-full h-[34px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah</span>
                </button>
            </div>
        </div>
    </div>

    <!-- TABEL DETAIL PRODUK V2 (~36px KOMPAK) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[260px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabledetailProduk">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Strip Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Daftar Produk Kiriman</span>
                                </div>
                                <span id="itemCountBadgeSJ" class="text-[10.5px] text-white/70 font-mono-num">{{ count($detail) }} item</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right w-36 font-bold uppercase tracking-wider text-white/90 border-none">JUMLAH</th>
                        <th class="py-2 px-3 text-center w-14 font-bold uppercase tracking-wider text-white/90 border-none">#</th>
                    </tr>
                </thead>
                <tbody id="loaddetail" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        <tr id="index_{{ $d->kode_produk }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-2 px-3">
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-2 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-2 px-3 text-right">
                                <input type="text" name="jml[]" value="{{ formatAngka($d->jumlah) }}"
                                    class="money jml w-28 h-[30px] px-2 text-right font-mono-num font-bold text-xs rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <button type="button" kode_produk="{{ $d->kode_produk }}"
                                    class="delete w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                    title="Hapus Produk">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRowSJ">
                            <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Belum ada rincian produk kiriman</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ERROR CONTAINER SUMMARY JIKA DETAIL KOSONG -->
    <div id="errorDetailEmptySJ" class="hidden rounded-lg p-2.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-rose-700 dark:text-rose-400 text-xs font-semibold flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-500"></i>
        <span>Detail produk masih kosong! Harap tambahkan minimal 1 produk sebelum menyimpan surat jalan.</span>
    </div>

    <!-- FOOTER ACTIONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3">
        <div class="field-group">
            <div class="flex items-center gap-2">
                <input type="checkbox" name="aggrement" id="defaultCheck3" value="aggrement"
                    class="agreement w-4 h-4 rounded-sm border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 cursor-pointer">
                <label for="defaultCheck3" class="text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                    Konfirmasi data surat jalan sudah benar
                </label>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpan"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span id="btnSimpanText">Simpan Surat Jalan</span>
            </button>
        </div>
    </div>
</form>

<script>
$(function() {
    const $form = $("#formcreateSuratjalan");

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Initialize Flatpickr
    $form.find(".flatpickr-date").flatpickr({
        dateFormat: "Y-m-d",
        @if(!empty($start_periode) && !empty($end_periode))
        enable: [{
            from: "{{ $start_periode }}",
            to: "{{ $end_periode }}"
        }]
        @endif
    });

    // Initialize Money Mask
    function initMaskMoney() {
        if (typeof $.fn.maskMoney !== 'undefined') {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 2,
                allowZero: true
            });
        }
    }
    initMaskMoney();

    // Select2 Styling V2 Standard (Floating Mount to document.body)
    $form.find('.Select2Kodetujuan').select2({
        placeholder: 'Pilih Tujuan',
        allowClear: true,
        width: '100%'
    });

    $form.find('.select2Kodeangkutan').select2({
        placeholder: 'Pilih Angkutan',
        allowClear: true,
        width: '100%'
    });

    $form.find('.select2Kodeproduk').select2({
        placeholder: 'Pilih Produk',
        allowClear: true,
        width: '100%'
    });

    // Auto update tarif saat kode tujuan dipilih
    $form.find("#kode_tujuan").on('change select2:select', function() {
        const tarif = $(this).find(':selected').data('tarif');
        if (tarif) {
            $form.find("#tarif").val(tarif);
            clearFieldError($form.find("#tarif"));
        }
    });

    // Cek Tutup Laporan
    function cektutuplaporan(tanggal, jenis_laporan) {
        $.ajax({
            type: "POST",
            url: "/tutuplaporan/cektutuplaporan",
            data: {
                _token: "{{ csrf_token() }}",
                tanggal: tanggal,
                jenis_laporan: jenis_laporan
            },
            cache: false,
            success: function(respond) {
                $form.find("#cektutuplaporan").val(respond);
            }
        });
    }

    $form.find("#tanggal").on('change', function() {
        cektutuplaporan($(this).val(), "gudangjadi");
    });

    // Validasi Interaktif Helpers V2
    function setFieldError($field, message) {
        $field.addClass('!border-rose-500 !ring-1 !ring-rose-500');
        const $group = $field.closest('.field-group');
        $group.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.input-prefix-icon').addClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
        $group.append(`
            <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>${message}</span>
            </p>
        `);
    }

    function clearFieldError($field) {
        $field.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
        const $group = $field.closest('.field-group');
        $group.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.input-prefix-icon').removeClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
    }

    // Live Cleanup
    $form.on('input change', 'input, select', function() {
        clearFieldError($(this));
        $('#errorDetailEmptySJ').addClass('hidden');
    });

    $form.find('.Select2Kodetujuan, .select2Kodeangkutan, .select2Kodeproduk').on('select2:select select2:clear', function() {
        clearFieldError($(this));
    });

    $form.find('#defaultCheck3').on('change', function() {
        if ($(this).is(':checked')) {
            $(this).removeClass('!ring-2 !ring-rose-500');
            $(this).closest('.field-group').find('.field-error-msg').remove();
        }
    });

    // Badge Count
    function updateBadgeCount() {
        const count = $form.find('#loaddetail tr[id^="index_"]').length;
        $form.find('#itemCountBadgeSJ').text(`${count} item`);
        if (count > 0) {
            $('#errorDetailEmptySJ').addClass('hidden');
            $form.find('#emptyRowSJ').remove();
        } else {
            if ($form.find('#emptyRowSJ').length === 0) {
                $form.find('#loaddetail').html(`
                    <tr id="emptyRowSJ">
                        <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-medium">Belum ada rincian produk kiriman</span>
                            </div>
                        </td>
                    </tr>
                `);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }
    }

    // Tambah Produk ke Tabel
    function addProduk() {
        const $dataProduk = $form.find("#kode_produk :selected");
        const kode_produk = $dataProduk.val();
        const nama_produk = $dataProduk.text().split(' - ').slice(1).join(' - ') || $dataProduk.text();
        const jumlah = $form.find("#jumlah").val();

        $form.find('#emptyRowSJ').remove();

        const tr = `
            <tr id="index_${kode_produk}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                <td class="py-2 px-3">
                    <input type="hidden" name="kode_produk[]" value="${kode_produk}"/>
                    <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">${kode_produk}</span>
                </td>
                <td class="py-2 px-3 font-semibold text-slate-800 dark:text-slate-100">${nama_produk}</td>
                <td class="py-2 px-3 text-right">
                    <input type="text" name="jml[]" value="${jumlah}"
                        class="money jml w-28 h-[30px] px-2 text-right font-mono-num font-bold text-xs rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition" />
                </td>
                <td class="py-2 px-3 text-center">
                    <button type="button" kode_produk="${kode_produk}"
                        class="delete w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                        title="Hapus Produk">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#loaddetail').prepend(tr);
        initMaskMoney();
        $form.find('.select2Kodeproduk').val('').trigger("change");
        $form.find("#jumlah").val("");
        clearFieldError($form.find("#kode_produk"));
        clearFieldError($form.find("#jumlah"));
        updateBadgeCount();
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    $form.find("#tambahproduk").on('click', function(e) {
        e.preventDefault();
        const $kodeProduk = $form.find("#kode_produk");
        const $jumlah = $form.find("#jumlah");
        const kode = $kodeProduk.val();
        const jumlah = $jumlah.val();

        let hasError = false;
        let $firstError = null;

        if (!kode) {
            setFieldError($kodeProduk, "Silakan pilih produk terlebih dahulu");
            hasError = true;
            $firstError = $kodeProduk;
        }

        if (!jumlah || jumlah === "0" || jumlah === "0,00") {
            setFieldError($jumlah, "Jumlah harus lebih dari 0");
            hasError = true;
            if (!$firstError) $firstError = $jumlah;
        }

        if ($form.find('#tabledetailProduk').find('#index_' + kode).length > 0) {
            setFieldError($kodeProduk, "Produk ini sudah ada di daftar");
            hasError = true;
            if (!$firstError) $firstError = $kodeProduk;
        }

        if (hasError) {
            if ($firstError) {
                if ($firstError.hasClass('select2Kodeproduk')) {
                    $firstError.select2('open');
                } else {
                    $firstError.focus();
                }
            }
            return;
        }

        addProduk();
    });

    // Hapus Produk dari Tabel
    $form.on('click', '.delete', function(e) {
        e.preventDefault();
        const kode_produk = $(this).attr("kode_produk");
        $(`#index_${kode_produk}`).remove();
        updateBadgeCount();
    });

    // Form Submit & Client-side Validation
    $form.on('submit', function(e) {
        let hasError = false;
        let $firstError = null;

        const $noDok = $form.find("#no_dok");
        const $tanggal = $form.find("#tanggal");
        const $kodeTujuan = $form.find("#kode_tujuan");
        const $kodeAngkutan = $form.find("#kode_angkutan");
        const $noPolisi = $form.find("#no_polisi");
        const $tarif = $form.find("#tarif");
        const cektutup = $form.find("#cektutuplaporan").val();
        const detailCount = $form.find('#loaddetail tr[id^="index_"]').length;
        const $agreement = $form.find('#defaultCheck3');

        if (!$noDok.val().trim()) {
            setFieldError($noDok, "No. Dokumen wajib diisi");
            hasError = true;
            if (!$firstError) $firstError = $noDok;
        }

        if (!$tanggal.val().trim()) {
            setFieldError($tanggal, "Tanggal surat jalan wajib dipilih");
            hasError = true;
            if (!$firstError) $firstError = $tanggal;
        }

        if (!$kodeTujuan.val()) {
            setFieldError($kodeTujuan, "Tujuan pengiriman wajib dipilih");
            hasError = true;
            if (!$firstError) $firstError = $kodeTujuan;
        }

        if ($kodeAngkutan.val() && !$noPolisi.val().trim()) {
            setFieldError($noPolisi, "No. Polisi wajib diisi jika armada dipilih");
            hasError = true;
            if (!$firstError) $firstError = $noPolisi;
        }

        if ($kodeAngkutan.val() && (!$tarif.val() || $tarif.val() === "0" || $tarif.val() === "0,00")) {
            setFieldError($tarif, "Tarif angkutan wajib diisi jika armada dipilih");
            hasError = true;
            if (!$firstError) $firstError = $tarif;
        }

        if (cektutup === '1') {
            Swal.fire({
                title: "Peringatan",
                text: "Laporan untuk periode tanggal ini sudah ditutup!",
                icon: "warning",
                confirmButtonText: "OK"
            });
            return false;
        }

        if (detailCount === 0) {
            $('#errorDetailEmptySJ').removeClass('hidden');
            hasError = true;
            if (!$firstError) $firstError = $form.find("#kode_produk");
        } else {
            $('#errorDetailEmptySJ').addClass('hidden');
        }

        if (!$agreement.is(':checked')) {
            $agreement.addClass('!ring-2 !ring-rose-500');
            const $agGroup = $agreement.closest('.field-group');
            $agGroup.find('.field-error-msg').remove();
            $agGroup.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Centang konfirmasi terlebih dahulu</span>
                </p>
            `);
            hasError = true;
            if (!$firstError) $firstError = $agreement;
        } else {
            $agreement.removeClass('!ring-2 !ring-rose-500');
            $agreement.closest('.field-group').find('.field-error-msg').remove();
        }

        if (hasError) {
            e.preventDefault();
            if ($firstError) {
                if ($firstError.hasClass('select2-hidden-accessible')) {
                    $firstError.select2('open');
                } else {
                    $firstError.focus();
                }
            }
            return false;
        }

        // Anti double-submit & loading spinner
        const $btn = $form.find("#btnSimpan");
        $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
        $btn.find('i').remove();
        $form.find("#btnSimpanText").html(`
            <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `);
    });

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
