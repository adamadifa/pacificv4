<form action="{{ route('laporanaccounting.cetakbukubesar') }}" id="formLedger" target="_blank" method="POST" class="space-y-3">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <div class="field-group">
            <label for="formatlaporan_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Format Laporan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                </div>
                <select name="formatlaporan" id="formatlaporan_ledger"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Format Laporan</option>
                    <option value="1">Buku Besar</option>
                    @if (auth()->user()->hasRole(['super admin', 'direktur', 'gm administrasi']))
                        <option value="2">Neraca</option>
                        <option value="3">Laba Rugi</option>
                        <option value="4">Neraca Perbandingan</option>
                        <option value="5">Laba Rugi Perbandingan</option>
                        <option value="6">Analisa Rasio Keuangan</option>
                    @endif
                </select>
            </div>
        </div>

        <!-- Jenis Laporan Buku Besar (Detail / Rekap) -->
        <div class="field-group" id="jenislaporan_container" style="display: none;">
            <label for="jenis_laporan_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jenis Laporan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="list-filter" class="w-4 h-4"></i>
                </div>
                <select name="jenis_laporan" id="jenis_laporan_ledger"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="1">Detail</option>
                    <option value="2">Rekap</option>
                </select>
            </div>
        </div>

        <!-- Format Cetak Neraca / Laba Rugi -->
        <div class="field-group" id="formatcetak_container" style="display: none;">
            <label for="formatcetak_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Format Cetak
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="layout-template" class="w-4 h-4"></i>
                </div>
                <select name="formatcetak" id="formatcetak_ledger"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="1">Format Kolom Terpisah</option>
                    <option value="2">Format Laporan Keuangan</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Rentang Akun CoA (untuk Buku Besar) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start" id="coa" style="display: none;">
        <div class="field-group">
            <label for="kode_akun_dari_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Akun
            </label>
            <div class="relative select2-container-v2">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <select name="kode_akun_dari" id="kode_akun_dari_ledger" class="select2Kodeakundari w-full">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="kode_akun_sampai_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Akun
            </label>
            <div class="relative select2-container-v2">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <select name="kode_akun_sampai" id="kode_akun_sampai_ledger" class="select2Kodeakunsampai w-full">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Periode Tanggal -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start" id="row_periode">
        <div class="field-group">
            <label for="dari_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_ledger" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_ledger" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- Tahun & Sampai Bulan (untuk Perbandingan / Analisa Rasio) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <div class="field-group" id="row_tahun" style="display: none;">
            <label for="tahun_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                </div>
                <select name="tahun" id="tahun_ledger"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Tahun</option>
                    @php
                        $tahun_mulai = 2024;
                        $tahun_skrg = date('Y');
                    @endphp
                    @for ($t = $tahun_mulai; $t <= $tahun_skrg + 1; $t++)
                        <option value="{{ $t }}" @selected($t == $tahun_skrg)>{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="field-group" id="row_sampai_bulan" style="display: none;">
            <label for="sampai_bulan_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Bulan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <select name="sampai_bulan" id="sampai_bulan_ledger"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Sampai Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ config('global.nama_bulan')[$m] }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-end gap-2">
        <button type="submit" name="submitButton"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
            <span>Cetak</span>
        </button>
        <button type="submit" name="exportButton" value="1"
            class="h-[36px] px-3.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="download" class="w-3.5 h-3.5"></i>
            <span>Export Excel</span>
        </button>
    </div>
</form>
