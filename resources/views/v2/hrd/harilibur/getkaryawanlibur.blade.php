@forelse ($detailharilibur as $d)
    <tr class="row-karyawan hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
        <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>
        <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-700 dark:text-slate-300">{{ $d->nik }}</td>
        <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ formatName2($d->nama_karyawan) }}</td>
        <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $d->kode_dept }}</td>
        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">{{ $d->nama_group }}</td>
        <td class="py-1.5 px-3 text-center">
            @can('harilibur.setharilibur')
                <button type="button"
                    class="delete w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer"
                    nik="{{ $d->nik }}" nama="{{ formatName2($d->nama_karyawan) }}" title="Hapus">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
            @endcan
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="py-8 px-3 text-center text-xs text-slate-400">
            Belum ada karyawan yang ditambahkan ke hari libur ini.
        </td>
    </tr>
@endforelse
