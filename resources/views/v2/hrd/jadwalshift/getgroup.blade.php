@forelse ($karyawan as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
        <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>
        <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $d->nik }}</td>
        <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ $d->nama_karyawan }}</td>
        <td class="py-1.5 px-3 statusjadwal text-center">
            @if (empty($d->kode_jadwal))
                <span class="px-2 py-0.5 rounded bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-300 text-[10.5px] font-bold">Belum</span>
            @else
                <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-bold">{{ $d->nama_jadwal }}</span>
            @endif
        </td>
        <td class="py-1.5 px-3 text-center">
            <button type="button" nik="{{ $d->nik }}"
                class="updateJadwal w-6 h-6 rounded-md {{ empty($d->kode_jadwal) ? 'bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:text-blue-400' : 'bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400' }} inline-flex items-center justify-center transition cursor-pointer"
                title="{{ empty($d->kode_jadwal) ? 'Tambahkan' : 'Ganti Shift' }}">
                @if (empty($d->kode_jadwal))
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                @else
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                @endif
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Tidak ada karyawan pada grup ini.</td>
    </tr>
@endforelse
