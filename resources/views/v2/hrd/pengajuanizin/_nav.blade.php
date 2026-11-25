@if (auth()->user()->hasAnyPermission(['izinabsen.index', 'izinkeluar.index', 'izinpulang.index', 'izinterlambat.index', 'izinsakit.index', 'izincuti.index', 'izindinas.index', 'izinkoreksi.index']))
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-2 shadow-2xs overflow-x-auto">
        <div class="inline-flex items-center gap-1.5 whitespace-nowrap min-w-full">
            @can('izinabsen.index')
                @php $active = request()->is(['izinabsen', 'izinabsen/*']); @endphp
                <a href="{{ route('izinabsen.index') }}"
                    class="h-[34px] px-3 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition {{ $active ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    <span>Izin Absen</span>
                    @if (!empty($notifikasi_izinabsen))
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $active ? 'bg-white text-[#284c9a]' : 'bg-rose-500 text-white' }}">{{ $notifikasi_izinabsen }}</span>
                    @endif
                </a>
            @endcan

            @can('izinkeluar.index')
                @php $active = request()->is(['izinkeluar', 'izinkeluar/*']); @endphp
                <a href="{{ route('izinkeluar.index') }}"
                    class="h-[34px] px-3 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition {{ $active ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span>Izin Keluar</span>
                    @if (!empty($notifikasi_izinkeluar))
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $active ? 'bg-white text-[#284c9a]' : 'bg-rose-500 text-white' }}">{{ $notifikasi_izinkeluar }}</span>
                    @endif
                </a>
            @endcan

            @can('izinpulang.index')
                @php $active = request()->is(['izinpulang', 'izinpulang/*']); @endphp
                <a href="{{ route('izinpulang.index') }}"
                    class="h-[34px] px-3 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition {{ $active ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i data-lucide="home" class="w-3.5 h-3.5"></i>
                    <span>Izin Pulang</span>
                    @if (!empty($notifikasi_izinpulang))
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $active ? 'bg-white text-[#284c9a]' : 'bg-rose-500 text-white' }}">{{ $notifikasi_izinpulang }}</span>
                    @endif
                </a>
            @endcan

            @can('izinterlambat.index')
                @php $active = request()->is(['izinterlambat', 'izinterlambat/*']); @endphp
                <a href="{{ route('izinterlambat.index') }}"
                    class="h-[34px] px-3 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition {{ $active ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    <span>Izin Terlambat</span>
                    @if (!empty($notifikasi_izinterlambat))
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $active ? 'bg-white text-[#284c9a]' : 'bg-rose-500 text-white' }}">{{ $notifikasi_izinterlambat }}</span>
                    @endif
                </a>
            @endcan

            @can('izinsakit.index')
                @php $active = request()->is(['izinsakit', 'izinsakit/*']); @endphp
                <a href="{{ route('izinsakit.index') }}"
                    class="h-[34px] px-3 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition {{ $active ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i data-lucide="heart-pulse" class="w-3.5 h-3.5"></i>
                    <span>Izin Sakit</span>
                    @if (!empty($notifikasi_izinsakit))
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $active ? 'bg-white text-[#284c9a]' : 'bg-rose-500 text-white' }}">{{ $notifikasi_izinsakit }}</span>
                    @endif
                </a>
            @endcan

            @can('izincuti.index')
                @php $active = request()->is(['izincuti', 'izincuti/*']); @endphp
                <a href="{{ route('izincuti.index') }}"
                    class="h-[34px] px-3 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition {{ $active ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                    <span>Izin Cuti</span>
                    @if (!empty($notifikasi_izincuti))
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $active ? 'bg-white text-[#284c9a]' : 'bg-rose-500 text-white' }}">{{ $notifikasi_izincuti }}</span>
                    @endif
                </a>
            @endcan

            @can('izindinas.index')
                @php $active = request()->is(['izindinas', 'izindinas/*']); @endphp
                <a href="{{ route('izindinas.index') }}"
                    class="h-[34px] px-3 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition {{ $active ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    <span>Perjalanan Dinas</span>
                    @if (!empty($notifikasi_izindinas))
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $active ? 'bg-white text-[#284c9a]' : 'bg-rose-500 text-white' }}">{{ $notifikasi_izindinas }}</span>
                    @endif
                </a>
            @endcan

            @can('izinkoreksi.index')
                @php $active = request()->is(['izinkoreksi', 'izinkoreksi/*']); @endphp
                <a href="{{ route('izinkoreksi.index') }}"
                    class="h-[34px] px-3 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 transition {{ $active ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                    <span>Izin Koreksi</span>
                    @if (!empty($notifikasi_izinkoreksi))
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $active ? 'bg-white text-[#284c9a]' : 'bg-rose-500 text-white' }}">{{ $notifikasi_izinkoreksi }}</span>
                    @endif
                </a>
            @endcan
        </div>
    </div>
@endif
