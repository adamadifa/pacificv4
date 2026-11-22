<form method="POST" action="{{ route('laporangudangjadi.cetakrealisasioman') }}" id="frmRealisasioman" target="_blank" autocomplete="off" class="space-y-3">
    @csrf

    <!-- Periode Bulan & Tahun -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div class="field-group">
            <label for="bulan_realisasioman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Pilih Bulan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <select name="bulan" id="bulan_realisasioman"
                    class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>

        <div class="field-group">
            <label for="tahun_realisasioman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Pilih Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                </div>
                <select name="tahun" id="tahun_realisasioman"
                    class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Cetak & Export -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 pt-2 items-start">
        <div class="sm:col-span-10">
            <button type="submit" name="submitButton" id="btnCetakRealisasioman"
                class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Cetak Realisasi OMAN</span>
            </button>
        </div>
        <div class="sm:col-span-2">
            <button type="submit" name="exportButton" id="btnExportRealisasioman" title="Export Excel"
                class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                <span class="sm:hidden">Export Excel</span>
            </button>
        </div>
    </div>
</form>
