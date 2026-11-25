<form method="POST" action="{{ route('laporanhrd.cetakketerlambatan') }}" id="frmLaporanKeterlambatan" target="_blank" class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        @hasanyrole($roles_access_all_karyawan)
            <div class="field-group sm:col-span-2">
                <label for="kode_cabang_keterlambatan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Cabang
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_keterlambatan"
                        class="select2Kodecabangketerlambatan w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endhasanyrole

        <div class="field-group">
            <label for="kode_dept_keterlambatan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Departemen
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                </div>
                <select name="kode_dept" id="kode_dept_keterlambatan"
                    class="select2Kodedeptketerlambatan w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Departemen</option>
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="kode_group_keterlambatan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Group
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
                <select name="kode_group" id="kode_group_keterlambatan"
                    class="select2Kodegroupketerlambatan w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Group</option>
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="dari_tanggal_keterlambatan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari_tanggal" id="dari_tanggal_keterlambatan" readonly
                    placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_tanggal_keterlambatan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai_tanggal" id="sampai_tanggal_keterlambatan" readonly
                    placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
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
