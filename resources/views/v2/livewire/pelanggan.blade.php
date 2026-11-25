<div class="space-y-3">
    <!-- FILTER & REGISTER BAR (NO-LABEL COMPACT V2) -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input type="text" id="namapelanggan_search" name="namapelanggan_search"
                placeholder="Cari kode atau nama pelanggan..." autocomplete="off" aria-autocomplete="none"
                wire:model.live.debounce.300ms="namapelanggan_search"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>

        <a href="{{ route('sfa.createpelanggan') }}"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 shrink-0">
            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
            <span>Register New Outlet</span>
        </a>
    </div>

    <!-- PELANGGAN CARDS GRID -->
    @if ($datapelanggan->isEmpty())
        <div class="flex flex-col items-center justify-center py-10 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-2">
                <i data-lucide="store" class="w-5 h-5"></i>
            </div>
            <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Pelanggan tidak ditemukan</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Coba ubah kata kunci pencarian atau daftarkan outlet baru.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($datapelanggan as $d)
                @php
                    $isInactive = $d->status_aktif_pelanggan == 0;
                @endphp
                <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($d->kode_pelanggan)) }}"
                    class="group block rounded-xl border p-3 transition-all duration-150 shadow-2xs hover:shadow-md {{ $isInactive ? 'border-rose-200 dark:border-rose-900/70 bg-rose-50/70 dark:bg-rose-950/30 hover:border-rose-300' : 'border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-[#284c9a]/40' }}">
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('assets/img/elements/9.jpg') }}" alt="{{ $d->nama_pelanggan }}"
                            class="w-12 h-12 rounded-lg object-cover shrink-0 border border-slate-200/80 dark:border-slate-700">

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-1.5">
                                <span class="text-[11px] font-bold font-mono-num {{ $isInactive ? 'text-rose-600 dark:text-rose-400' : 'text-[#284c9a] dark:text-blue-400' }}">
                                    {{ $d->kode_pelanggan }}
                                </span>
                                @if ($isInactive)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 shrink-0">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>

                            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate mt-0.5 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 transition-colors">
                                {{ textUpperCase($d->nama_pelanggan) }}
                            </h6>

                            <div class="mt-1.5 flex items-center gap-1.5">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-semibold truncate">
                                    <i data-lucide="map-pin" class="w-2.5 h-2.5 shrink-0 text-[#284c9a] dark:text-blue-400"></i>
                                    <span class="truncate">{{ $d->nama_wilayah ?: '-' }}</span>
                                </span>
                            </div>
                        </div>

                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-[#284c9a] shrink-0 self-center transition-transform group-hover:translate-x-0.5"></i>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
