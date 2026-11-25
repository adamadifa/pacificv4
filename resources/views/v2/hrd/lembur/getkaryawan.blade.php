@forelse ($karyawan as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
        <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>
        <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-700 dark:text-slate-300">{{ $d->nik }}</td>
        <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ formatName2($d->nama_karyawan) }}</td>
        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">{{ $d->nama_group }}</td>
        <td class="py-1.5 px-3 text-center">
            <button type="button" nik="{{ $d->nik }}"
                class="updateLembur w-6 h-6 rounded-md {{ empty($d->ceklembur) ? 'bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:text-blue-400' : 'bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400' }} inline-flex items-center justify-center transition cursor-pointer"
                title="{{ empty($d->ceklembur) ? 'Tambahkan' : 'Batalkan' }}">
                @if (empty($d->ceklembur))
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                @else
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                @endif
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Tidak ada data karyawan ditemukan.</td>
    </tr>
@endforelse
