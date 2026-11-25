<form action="{{ route('laporanaccounting.cetakrekapcostratio') }}" method="POST" target="_blank" id="formRekapcostratio" class="space-y-3">
    @csrf

    @hasanyrole($roles_show_cabang)
        <div class="field-group">
            <label for="kode_cabang_rekapcostratio" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Cabang
            </label>
            <div class="relative select2-container-v2">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_rekapcostratio" class="select2Kodecabangrekapcostratio w-full">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endhasanyrole

    <div class="field-group">
        <label for="tahun_rekapcostratio" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tahun (Multi Tahun) <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="calendar-days" class="w-4 h-4"></i>
            </div>
            <select name="tahun[]" id="tahun_rekapcostratio" class="select2Tahunrekapcostratio w-full" multiple="multiple" data-placeholder="Pilih Tahun">
                @for ($t = date('Y'); $t >= $start_year; $t--)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endfor
            </select>
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
