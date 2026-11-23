<form action="{{ route('laporanmarketing.cetakrekappenjualan') }}" method="POST" target="_blank" id="formRekappenjualan" class="space-y-3 w-full">
    @csrf

    <div class="field-group">
        <label for="jenis_laporan_rekappenjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Jenis Laporan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
            </div>
            <select name="jenis_laporan" id="jenis_laporan_rekappenjualan"
                class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                <option value="">Pilih Jenis Laporan</option>
                <option value="1">Rekap Penjualan</option>
                <option value="2">Rekap Retur</option>
                <option value="3">Rekap Penjualan Qty</option>
                <option value="4">Rekap Penjualan Produk</option>
                <option value="5">Collect Aup</option>
                @hasanyrole('super admin|gm administrasi')
                    <option value="6">Rekap Penjualan Qty per Salesman (Multi Tahun)</option>
                    <option value="7">Rekap Penjualan Qty per Cabang (Multi Tahun)</option>
                    <option value="8">Rekap Penjualan Qty per Regional (Multi Tahun)</option>
                    <option value="9">Rekap Penjualan Omset per Salesman (Multi Tahun)</option>
                    <option value="10">Rekap Penjualan Omset per Cabang (Multi Tahun)</option>
                    <option value="11">Rekap Penjualan Omset per Regional (Multi Tahun)</option>
                    <option value="12">Rekap Retur Netto per Salesman (Multi Tahun)</option>
                    <option value="13">Rekap Retur Netto per Cabang (Multi Tahun)</option>
                    <option value="14">Rekap Retur Netto per Regional (Multi Tahun)</option>
                    <option value="15">Rekap Piutang per Salesman (Multi Tahun)</option>
                    <option value="16">Rekap Piutang per Cabang (Multi Tahun)</option>
                    <option value="17">Rekap Piutang per Regional (Multi Tahun)</option>
                @endhasanyrole
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
            </div>
        </div>
    </div>

    @hasanyrole($roles_show_cabang)
        <div class="field-group" id="divKodecabangRekappenjualan">
            <label for="kode_cabang_rekappenjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Cabang
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_rekappenjualan" class="select2Kodecabangrekappenjualan w-full">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endrole

    <div class="field-group" id="divKodesalesmanRekappenjualan">
        <label for="kode_salesman_rekappenjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
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
                <select name="kode_salesman" id="kode_salesman_rekappenjualan" class="select2Kodesalesmanrekappenjualan w-full">
                    <option value="">Semua Salesman</option>
                </select>
            @endhasanyrole
        </div>
    </div>

    <!-- Tanggal AUP (Single Date) -->
    <div class="field-group" id="tanggalaup" style="display: none;">
        <label for="tanggal_rekappenjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Lihat Per Tanggal <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tanggal" id="tanggal_rekappenjualan"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer"
                placeholder="Pilih Tanggal" readonly>
        </div>
    </div>

    <!-- Tanggal Penjualan (Date Range) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5" id="tanggalpenjualan">
        <div class="field-group">
            <label for="dari_rekappenjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_rekappenjualan"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer"
                    placeholder="Pilih Tanggal Mulai" readonly>
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_rekappenjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_rekappenjualan"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer"
                    placeholder="Pilih Tanggal Akhir" readonly>
            </div>
        </div>
    </div>

    <!-- Tahun Multi Tahun (Multiple Select2) -->
    <div class="field-group" id="tahunmultitahun" style="display: none;">
        <label for="tahun_rekappenjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Pilih Tahun (Multi Tahun) <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="calendar-range" class="w-4 h-4"></i>
            </div>
            <select name="tahun[]" id="tahun_rekappenjualan" class="select2Tahunrekappenjualan w-full" multiple="multiple" data-placeholder="Pilih Tahun">
                @for ($y = date('Y'); $y >= $start_year; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    <div class="pt-2 flex items-center gap-2 border-t border-slate-100 dark:border-slate-800">
        <button type="submit" name="submitButton" id="submitButton_rekappenjualan"
            class="flex-1 inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152854] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak PDF Laporan</span>
        </button>
        <button type="submit" name="exportButton" id="exportButton_rekappenjualan"
            class="inline-flex items-center justify-center gap-2 h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer"
            title="Export Excel">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            <span>Excel</span>
        </button>
    </div>
</form>
