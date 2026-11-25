@forelse ($detail as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
        <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-700 dark:text-slate-300">{{ $d->nik }}</td>
        <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ formatName2($d->nama_karyawan) }}</td>
        <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $d->kode_dept }}</td>
        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">{{ $d->nama_group }}</td>
        <td class="py-1.5 px-3 text-center">
            @if ($d->status === '0')
                <button type="button"
                    class="delete w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer"
                    nik="{{ $d->nik }}" title="Hapus">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                </button>
            @else
                <span class="text-xs text-slate-300">-</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-8 px-3 text-center text-xs text-slate-400">Belum ada karyawan yang ditambahkan ke lembur ini.</td>
    </tr>
@endforelse
