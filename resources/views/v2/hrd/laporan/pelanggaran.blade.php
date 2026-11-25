<form method="POST" action="{{ route('laporanhrd.cetakpelanggaran') }}" id="frmLaporanPelanggaran" target="_blank" class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 gap-3 items-start">
        <div class="field-group">
            <label for="tahun_pelanggaran" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                </div>
                <select name="tahun" id="tahun_pelanggaran"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
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
