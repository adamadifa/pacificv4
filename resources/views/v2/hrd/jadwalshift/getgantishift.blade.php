@forelse ($gantishift as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
        <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-300">{{ formatIndo($d->tanggal) }}</td>
        <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $d->nik }}</td>
        <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ $d->nama_karyawan }}</td>
        <td class="py-1.5 px-3">
            <span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-300 text-[11px] font-bold">{{ $d->nama_jadwal }}</span>
        </td>
        <td class="py-1.5 px-3 text-center">
            <button type="button" class="deletegs w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer"
                kode_gs="{{ $d->kode_gs }}" title="Hapus">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Belum ada data ganti shift.</td>
    </tr>
@endforelse
