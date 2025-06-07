@if (auth()->user()->hasAnyPermission(['izinabsen.index', 'izinkeluar.index', 'izinpulang.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('izinabsen.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinabsen.index') }}" class="nav-link {{ request()->is(['izinabsen']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Absen
                    @if (!empty($notifikasi_izinabsen))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinabsen }}</span>
                    @endif
                    {{-- @if (!empty($notifikasi_izinabsen_presensi) && $level_user == 'spv presensi')
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinabsen_presensi }}</span>
                    @endif --}}
                </a>
            </li>
        @endcan

        @can('izinkeluar.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinkeluar.index') }}" class="nav-link {{ request()->is(['izinkeluar']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Keluar
                    @if (!empty($notifikasi_izinkeluar))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinkeluar }}</span>
                    @endif
                    {{-- @if (!empty($notifikasi_izinkeluar_presensi) && $level_user == 'spv presensi')
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinkeluar_presensi }}</span>
                    @endif --}}
                </a>
            </li>
        @endcan
        @can('izinpulang.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinpulang.index') }}" class="nav-link {{ request()->is(['izinpulang']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Pulang
                    @if (!empty($notifikasi_izinpulang))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinpulang }}</span>
                    @endif
                    {{-- @if (!empty($notifikasi_izinpulang_presensi) && $level_user == 'spv presensi')
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinpulang_presensi }}</span>
                    @endif --}}
                </a>
            </li>
        @endcan

        @can('izinterlambat.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinterlambat.index') }}" class="nav-link {{ request()->is(['izinterlambat']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Terlambat
                    @if (!empty($notifikasi_izinterlambat))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinterlambat }}</span>
                    @endif
                    {{-- @if (!empty($notifikasi_izinterlambat_presensi) && $level_user == 'spv presensi')
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinterlambat_presensi }}</span>
                    @endif --}}
                </a>
            </li>
        @endcan

        @can('izinsakit.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinsakit.index') }}" class="nav-link {{ request()->is(['izinsakit']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Sakit
                    @if (!empty($notifikasi_izinsakit))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinsakit }}</span>
                    @endif

                    {{-- @if (!empty($notifikasi_izinsakit_presensi) && $level_user == 'spv presensi')
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinsakit_presensi }}</span>
                    @endif --}}
                </a>
            </li>
        @endcan

        @can('izincuti.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izincuti.index') }}" class="nav-link {{ request()->is(['izincuti']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Cuti
                    @if (!empty($notifikasi_izincuti))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izincuti }}</span>
                    @endif
                    {{-- @if (!empty($notifikasi_izincuti_presensi) && $level_user == 'spv presensi')
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izincuti_presensi }}</span>
                    @endif --}}
                </a>
            </li>
        @endcan

        @can('izindinas.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izindinas.index') }}" class="nav-link {{ request()->is(['izindinas']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Perjalanan Dinas
                    @if (!empty($notifikasi_izindinas))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izindinas }}</span>
                    @endif
                    {{-- @if (!empty($notifikasi_izindinas_presensi) && $level_user == 'spv presensi')
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izindinas_presensi }}</span>
                    @endif --}}
                </a>
            </li>
        @endcan

        @can('izinkoreksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinkoreksi.index') }}" class="nav-link {{ request()->is(['izinkoreksi']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Koreksi
                    @if (!empty($notifikasi_izinkoreksi))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinkoreksi }}</span>
                    @endif
                    {{-- @if (!empty($notifikasi_izinkoreksi_presensi) && $level_user == 'spv presensi')
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinkoreksi_presensi }}</span>
                    @endif --}}
                </a>
            </li>
        @endcan

    </ul>
@endif
