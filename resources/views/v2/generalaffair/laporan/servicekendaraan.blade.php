<form action="{{ route('laporanga.cetakservicekendaraan') }}" method="POST" id="formLapServicekendaraan" target="_blank" class="space-y-3">
    @csrf

    <!-- Kendaraan -->
    <div>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
            </div>
            <select name="kode_kendaraan" id="kode_kendaraan_service" class="select2Kendaraan w-full">
                <option value="">Semua Kendaraan</option>
                @foreach ($kendaraan as $d)
                    <option value="{{ $d->kode_kendaraan }}">
                        {{ $d->no_polisi }} — {{ $d->merek }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Periode Tanggal -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" id="dari_service" placeholder="Dari Tanggal *" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>

        <div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" id="sampai_service" placeholder="Sampai Tanggal *" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="pt-2 flex items-center gap-2 border-t border-slate-100 dark:border-slate-800">
        <button type="submit" name="submitButton" id="submitButtonService"
            class="btn-cetak flex-1 inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152854] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak PDF Laporan</span>
        </button>
        <button type="submit" name="exportButton" id="exportButtonService" title="Export Excel"
            class="inline-flex items-center justify-center gap-2 h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            <span>Excel</span>
        </button>
    </div>

</form>
