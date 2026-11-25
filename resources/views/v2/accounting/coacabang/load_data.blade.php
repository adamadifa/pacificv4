<div class="overflow-x-auto">
    <table class="w-full text-left text-xs whitespace-nowrap">
        <thead class="bg-[#1e3a78] text-white/90">
            <tr>
                <th class="py-2 px-3.5 text-[11px] font-bold uppercase tracking-wider">KODE & NAMA AKUN</th>
                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-20">AKSI</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
            @forelse ($coaCabang as $d)
                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                    <td class="py-1.5 px-3.5 font-semibold text-slate-800 dark:text-slate-100">
                        <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_akun }}</span>
                        <span class="text-slate-400 mx-1">-</span>
                        <span>{{ $d->nama_akun }}</span>
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            @can('coacabang.delete')
                                <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                    action="{{ route('coacabang.delete', Crypt::encrypt($d->id)) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btnDeleteCoaCabang w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="py-8 text-center text-slate-400 dark:text-slate-500">
                        <div class="flex flex-col items-center justify-center gap-1.5">
                            <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                            <span class="text-xs font-semibold">Tidak ada data ditemukan</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
