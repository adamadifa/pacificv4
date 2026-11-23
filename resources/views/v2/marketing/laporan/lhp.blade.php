@php
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
    $isMobile = $agent->isMobile();
@endphp

<form action="{{ route('laporanmarketing.cetaklhp') }}" method="POST" id="formlhp" target="{{ $isMobile ? '_self' : '_blank' }}" class="space-y-3 w-full">
    @csrf

    @hasanyrole($roles_show_cabang)
        <div class="field-group">
            <label for="kode_cabang_lhp" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Cabang
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_lhp" class="select2Kodecabanglhp w-full">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endrole

    <div class="field-group">
        <label for="kode_salesman_lhp" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
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
                <select name="kode_salesman" id="kode_salesman_lhp" class="select2Kodesalemanlhp w-full">
                    <option value="">Semua Salesman</option>
                </select>
            @endhasanyrole
        </div>
    </div>

    <div class="field-group">
        <label for="tanggal_lhp" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tanggal <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tanggal" id="tanggal_lhp"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer"
                placeholder="Pilih Tanggal" readonly>
        </div>
    </div>

    <div class="pt-2 flex items-center gap-2 border-t border-slate-100 dark:border-slate-800">
        <button type="submit" name="submitButton" id="submitButton_lhp"
            class="flex-1 inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152854] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak LHP</span>
        </button>
        <button type="submit" name="exportButton" id="exportButton_lhp"
            class="inline-flex items-center justify-center gap-2 h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer"
            title="Export Excel">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            <span>Excel</span>
        </button>
    </div>
</form>
