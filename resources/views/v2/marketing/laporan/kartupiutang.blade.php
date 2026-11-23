<form action="{{ route('laporanmarketing.cetakkartupiutang') }}" method="POST" target="_blank" id="formKartupiutang" class="space-y-3 w-full">
    @csrf

    @hasanyrole($roles_show_cabang)
        <div class="field-group">
            <label for="kode_cabang_kartupiutang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Cabang
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_kartupiutang" class="select2Kodecabangkartupiutang w-full">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endrole

    <div class="field-group">
        <label for="kode_salesman_kartupiutang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Salesman
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="user-check" class="w-4 h-4"></i>
            </div>
            @hasanyrole('salesman')
                <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
                <input type="text" value="{{ auth()->user()->name }}" readonly
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 cursor-not-allowed">
            @else
                <select name="kode_salesman" id="kode_salesman_kartupiutang" class="select2Kodesalesmankartupiutang w-full">
                    <option value="">Semua Salesman</option>
                </select>
            @endhasanyrole
        </div>
    </div>

    <div class="field-group">
        <label for="kode_pelanggan_kartupiutang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Pelanggan
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <select name="kode_pelanggan" id="kode_pelanggan_kartupiutang" class="select2Kodepelanggankartupiutang w-full">
                <option value="">Semua Pelanggan</option>
            </select>
        </div>
    </div>

    <div class="field-group">
        <label for="formatlaporan_kartupiutang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Format Laporan
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <select name="formatlaporan" id="formatlaporan_kartupiutang"
                class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                <option value="">Format Laporan</option>
                <option value="1">Sudah Jatuh Tempo ( &gt; 30 Hari )</option>
                <option value="2">Belum Jatuh Tempo</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="field-group">
            <label for="dari_kartupiutang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_kartupiutang"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer"
                    placeholder="Pilih Tanggal Mulai" readonly>
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_kartupiutang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_kartupiutang"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer"
                    placeholder="Pilih Tanggal Akhir" readonly>
            </div>
        </div>
    </div>

    <div class="pt-2 flex items-center gap-2 border-t border-slate-100 dark:border-slate-800">
        <button type="submit" name="submitButton" id="submitButton_kartupiutang"
            class="flex-1 inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152854] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak PDF Laporan</span>
        </button>
        <button type="submit" name="exportButton" id="exportButton_kartupiutang"
            class="inline-flex items-center justify-center gap-2 h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer"
            title="Export Excel">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            <span>Excel</span>
        </button>
    </div>
</form>
