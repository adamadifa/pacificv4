<div class="space-y-3">
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-3.5 divide-y divide-dashed divide-slate-200 dark:divide-slate-700 text-xs">
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Kode</span>
            <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $izinkeluar->kode_izin_keluar }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Tanggal</span>
            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ DateToIndo($izinkeluar->tanggal) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">NIK</span>
            <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $izinkeluar->nik }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Nama Karyawan</span>
            <span class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ $izinkeluar->nama_karyawan }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Jabatan</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $izinkeluar->nama_jabatan }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Departemen</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $izinkeluar->nama_dept }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Cabang</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ textUpperCase($izinkeluar->nama_cabang) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Jam Keluar</span>
            <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ date('H:i', strtotime($izinkeluar->jam_keluar)) }}</span>
        </div>
        <div class="py-1.5 flex items-center justify-between gap-2">
            <span class="text-slate-400">Keperluan</span>
            <span>
                @if ($izinkeluar->keperluan == 'K')
                    <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-bold">Kantor</span>
                @else
                    <span class="px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10.5px] font-bold">Pribadi</span>
                @endif
            </span>
        </div>
        <div class="py-1.5 flex items-start justify-between gap-2">
            <span class="text-slate-400 shrink-0">Keterangan</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $izinkeluar->keterangan }}</span>
        </div>
    </div>

    <div class="flex items-center justify-end pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalIzinKeluar && window.closeModalIzinKeluar()"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition cursor-pointer">
            Tutup
        </button>
    </div>
</div>
