<!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
    <form action="{{ request()->routeIs('fsthp.index') ? route('fsthp.index') : route('fsthpgudang.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
        
        <!-- Tanggal Mutasi -->
        <div class="relative flex-1 w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="tanggal_mutasi_search" id="tanggal_mutasi_search"
                value="{{ request('tanggal_mutasi_search') }}"
                class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Pilih Tanggal Mutasi" readonly>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <button type="submit"
                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Cari</span>
            </button>

            @if (request('tanggal_mutasi_search'))
                <a href="{{ request()->routeIs('fsthp.index') ? route('fsthp.index') : route('fsthpgudang.index') }}"
                    class="inline-flex items-center justify-center gap-1 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    <span>Reset</span>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- MAIN DATA TABLE CARD WITH PACIFIC NAVY HEADER -->
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="w-full text-xs text-left border-collapse font-sans whitespace-nowrap">
            <thead>
                <!-- Row 1: Title Strip (colspan=6) -->
                <tr class="bg-[#284c9a] text-white">
                    <th colspan="6" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i data-lucide="arrow-right-left" class="w-4 h-4 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Dokumen FSTHP</span>
                            </div>
                            <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                Total {{ method_exists($fsthp, 'total') ? $fsthp->total() : $fsthp->count() }} Data
                            </span>
                        </div>
                    </th>
                </tr>
                <!-- Row 2: Column Titles -->
                <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                    <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                    <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NO. FSTHP</th>
                    <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none">TANGGAL</th>
                    <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none">UNIT</th>
                    <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none">STATUS</th>
                    <th class="py-2.5 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                @forelse ($fsthp as $d)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
                        <td class="py-1.5 px-3 text-center font-mono-num text-slate-500 dark:text-slate-400">
                            {{ $loop->iteration + ($fsthp->currentPage() - 1) * $fsthp->perPage() }}
                        </td>
                        <td class="py-1.5 px-4 font-bold text-brand-600 dark:text-brand-400 font-mono-num">
                            {{ $d->no_mutasi }}
                        </td>
                        <td class="py-1.5 px-4 text-center text-slate-600 dark:text-slate-300 font-mono-num">
                            {{ date('d-m-Y', strtotime($d->tanggal_mutasi)) }}
                        </td>
                        <td class="py-1.5 px-4 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                Unit {{ $d->unit }}
                            </span>
                        </td>
                        <td class="py-1.5 px-4 text-center">
                            @if ($d->status === '1' || $d->status == 1)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Diterima Gudang
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Belum Diterima
                                </span>
                            @endif
                        </td>
                        <td class="py-1.5 px-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Detail -->
                                @can('fsthp.show')
                                    <button type="button" class="showFsthp w-6 h-6 rounded-md bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                        no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}"
                                        title="Lihat Detail FSTHP">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                    </button>
                                @endcan

                                <!-- Delete -->
                                @can('fsthp.delete')
                                    @if ($d->status !== '1' && $d->status != 1)
                                        <form method="POST" action="{{ route('fsthp.delete', Crypt::encrypt($d->no_mutasi)) }}"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                                title="Hapus Dokumen FSTHP">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                                <!-- Approve / Cancel Approve -->
                                @can('fsthp.approve')
                                    @if ($d->status !== '1' && $d->status != 1)
                                        <a href="{{ route('fsthp.approve', Crypt::encrypt($d->no_mutasi)) }}"
                                            class="w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition flex items-center justify-center shadow-2xs"
                                            title="Konfirmasi Terima Barang di Gudang">
                                            <i data-lucide="check" class="w-3 h-3"></i>
                                        </a>
                                    @else
                                        <form method="POST" action="{{ route('fsthp.cancel', Crypt::encrypt($d->no_mutasi)) }}"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="cancel-confirm w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition flex items-center justify-center shadow-2xs cursor-pointer"
                                                title="Batalkan Konfirmasi Penerimaan">
                                                <i data-lucide="x" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="inbox" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-semibold">Tidak ada data FSTHP yang ditemukan</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($fsthp->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $fsthp->links('v2.components.pagination') }}
        </div>
    @endif
</div>
