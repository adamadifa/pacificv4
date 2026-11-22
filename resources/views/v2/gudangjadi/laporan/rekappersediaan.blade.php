<form method="POST" action="{{ route('laporangudangjadi.cetakrekappersediaan') }}" id="frmRekappersediaan" target="_blank" autocomplete="off" class="space-y-3">
    @csrf

    <!-- Periode Tanggal -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div class="field-group">
            <label for="dari_rekappersediaan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari_rekappersediaan" placeholder="Pilih Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_rekappersediaan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai_rekappersediaan" placeholder="Pilih Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Cetak & Export -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 pt-2 items-start">
        <div class="sm:col-span-10">
            <button type="submit" name="submitButton" id="btnCetakRekappersediaan"
                class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Cetak Rekap</span>
            </button>
        </div>
        <div class="sm:col-span-2">
            <button type="submit" name="exportButton" id="btnExportRekappersediaan" title="Export Excel"
                class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                <span class="sm:hidden">Export Excel</span>
            </button>
        </div>
    </div>
</form>
