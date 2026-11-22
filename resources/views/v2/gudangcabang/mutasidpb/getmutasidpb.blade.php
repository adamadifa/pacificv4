@forelse ($mutasi as $d)
    @php
        $mutasi_in = ['RETUR', 'HUTANG KIRIM'];
        $is_in = in_array($d->jenis_mutasi, $mutasi_in);
    @endphp
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition {{ $is_in ? 'bg-emerald-50/25 dark:bg-emerald-950/15' : 'bg-rose-50/25 dark:bg-rose-950/15' }}">
        <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
            {{ $d->no_mutasi }}
        </td>
        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300 font-mono-num">
            {{ DateToIndo($d->tanggal) }}
        </td>
        <td class="py-1.5 px-3 font-semibold">
            @if ($is_in)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100/80 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ $d->jenis_mutasi }}
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100/80 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    {{ $d->jenis_mutasi }}
                </span>
            @endif
        </td>
        <td class="py-1.5 px-3 text-center">
            <div class="flex items-center justify-center gap-1">
                @can('mutasidpb.edit')
                    <button type="button"
                        class="btnEditmutasidpb w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                        no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}"
                        title="Edit Mutasi">
                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                    </button>
                @endcan
                @can('mutasidpb.show')
                    <button type="button"
                        class="btnShowmutasidpb w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                        no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}"
                        title="Detail Mutasi">
                        <i data-lucide="eye" class="w-3 h-3"></i>
                    </button>
                @endcan
                @can('mutasidpb.delete')
                    <button type="button"
                        class="btnDeletemutasidpb w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                        no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}"
                        title="Hapus Mutasi">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="py-6 text-center text-slate-400">Belum ada mutasi untuk kriteria ini</td>
    </tr>
@endforelse

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
