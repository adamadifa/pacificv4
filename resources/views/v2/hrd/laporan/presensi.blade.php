<form method="POST" action="{{ route('laporanhrd.cetakpresensi') }}" id="frmLaporanPresensi" target="_blank" class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        @hasanyrole($roles_access_all_karyawan)
            <div class="field-group sm:col-span-2">
                <label for="kode_cabang_presensi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Cabang
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_presensi"
                        class="select2Kodecabangpresensi w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endhasanyrole

        <div class="field-group">
            <label for="kode_dept_presensi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Departemen
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                </div>
                <select name="kode_dept" id="kode_dept_presensi"
                    class="select2Kodedeptpresensi w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Departemen</option>
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="kode_group_presensi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Group
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
                <select name="kode_group" id="kode_group_presensi"
                    class="select2Kodegrouppresensi w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Group</option>
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="periode_laporan_presensi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Periode Laporan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-range" class="w-4 h-4"></i>
                </div>
                <select name="periode_laporan" id="periode_laporan_presensi"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Periode Laporan</option>
                    <option value="1" selected>Periode Gaji</option>
                    <option value="2">Bulan Berjalan</option>
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="format_laporan_presensi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Format Laporan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                </div>
                <select name="format_laporan" id="format_laporan_presensi"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Format Laporan</option>
                    <option value="1" selected>Standar</option>
                    <option value="2">P/S/M</option>
                </select>
            </div>
        </div>

        <div class="field-group sm:col-span-2">
            <label for="kategori_laporan_presensi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kategori Karyawan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
                <select name="kategori_laporan" id="kategori_laporan_presensi"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Karyawan</option>
                    <option value="MJ">Manajemen</option>
                    <option value="NM">Non Manajemen</option>
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="bulan_presensi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Bulan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <select name="bulan" id="bulan_presensi"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="tahun_presensi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                </div>
                <select name="tahun" id="tahun_presensi"
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
