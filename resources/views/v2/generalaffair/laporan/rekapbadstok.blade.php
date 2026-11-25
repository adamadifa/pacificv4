@php
    $bulan_pilihan = collect($list_bulan)->map(function ($item) {
        return (object) $item;
    });

    $tahun_pilihan = [];
    for ($t = $start_year; $t <= date('Y'); $t++) {
        $tahun_pilihan[] = (object) ['tahun' => $t];
    }
@endphp

<form action="{{ route('laporanga.cetakrekapbadstok') }}" id="formLapRekapbadstok" method="POST" target="_blank" class="space-y-3">
    @csrf

    <!-- Asal Bad Stok -->
    <div>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="building" class="w-3.5 h-3.5"></i>
            </div>
            <select name="kode_asal_bs" id="kode_asal_bs_rekap" class="select2Kodecabang w-full">
                <option value="">Semua Asal Bad Stok</option>
                <option value="GDG">GUDANG</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Format Laporan -->
    <div>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="layout-list" class="w-3.5 h-3.5"></i>
            </div>
            <select name="formatlaporan" id="formatlaporan"
                class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="1">Per Bulan</option>
                <option value="2">Per Tahun</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3 h-3"></i>
            </div>
        </div>
    </div>

    <!-- Bulan & Tahun -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div id="bulan_container">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                </div>
                <select name="bulan" id="bulan_rekap"
                    class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Bulan *</option>
                    @foreach ($bulan_pilihan as $b)
                        <option value="{{ $b->kode_bulan }}">{{ $b->nama_bulan }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>
        </div>

        <div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <select name="tahun" id="tahun_rekap"
                    class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Tahun *</option>
                    @foreach ($tahun_pilihan as $th)
                        <option value="{{ $th->tahun }}" {{ $th->tahun == date('Y') ? 'selected' : '' }}>{{ $th->tahun }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="pt-2 flex items-center gap-2 border-t border-slate-100 dark:border-slate-800">
        <button type="submit" name="submitButton"
            class="btn-cetak flex-1 inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152854] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak PDF Laporan</span>
        </button>
        <button type="submit" name="exportButton" title="Export Excel"
            class="inline-flex items-center justify-center gap-2 h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            <span>Excel</span>
        </button>
    </div>

</form>
