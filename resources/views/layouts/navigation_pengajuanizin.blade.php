@if (auth()->user()->hasAnyPermission(['izinabsen.index', 'izinkeluar.index', 'izinpulang.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('izinabsen.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinabsen.index') }}" class="nav-link {{ request()->is(['izinabsen']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Absen
                </a>
            </li>
        @endcan

        @can('izinkeluar.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinkeluar.index') }}" class="nav-link {{ request()->is(['izinkeluar']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Keluar
                </a>
            </li>
        @endcan
        @can('izinpulang.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinpulang.index') }}" class="nav-link {{ request()->is(['izinpulang']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Pulang
                </a>
            </li>
        @endcan

        @can('izinterlambat.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinterlambat.index') }}" class="nav-link {{ request()->is(['izinterlambat']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Terlambat
                </a>
            </li>
        @endcan

        @can('izinsakit.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinsakit.index') }}" class="nav-link {{ request()->is(['izinsakit']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Sakit
                </a>
            </li>
        @endcan
    </ul>
@endif
