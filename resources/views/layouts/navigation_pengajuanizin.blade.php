@if (auth()->user()->hasAnyPermission(['izinabsen.index', 'izinkeluar.index', 'izinpulang.index']))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">

        @can('izinabsen.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinabsen.index') }}" class="nav-link {{ request()->is(['izinabsen', 'izinabsen/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Absen
                    @if (!empty($notifikasi_izinabsen))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinabsen }}</span>
                    @endif
                </a>
            </li>
        @endcan

        @can('izinkeluar.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinkeluar.index') }}" class="nav-link {{ request()->is(['izinkeluar', 'izinkeluar/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-logout ti-md me-1"></i> Izin Keluar
                    @if (!empty($notifikasi_izinkeluar))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinkeluar }}</span>
                    @endif
                </a>
            </li>
        @endcan
        @can('izinpulang.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinpulang.index') }}" class="nav-link {{ request()->is(['izinpulang', 'izinpulang/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-home-door ti-md me-1"></i> Izin Pulang
                    @if (!empty($notifikasi_izinpulang))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinpulang }}</span>
                    @endif
                </a>
            </li>
        @endcan

        @can('izinterlambat.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinterlambat.index') }}"
                    class="nav-link {{ request()->is(['izinterlambat', 'izinterlambat/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-clock-pause ti-md me-1"></i> Izin Terlambat
                    @if (!empty($notifikasi_izinterlambat))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinterlambat }}</span>
                    @endif
                </a>
            </li>
        @endcan

        @can('izinsakit.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinsakit.index') }}" class="nav-link {{ request()->is(['izinsakit', 'izinsakit/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-pill ti-md me-1"></i> Izin Sakit
                    @if (!empty($notifikasi_izinsakit))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinsakit }}</span>
                    @endif
                </a>
            </li>
        @endcan

        @can('izincuti.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izincuti.index') }}" class="nav-link {{ request()->is(['izincuti', 'izincuti/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-calendar-event ti-md me-1"></i> Izin Cuti
                    @if (!empty($notifikasi_izincuti))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izincuti }}</span>
                    @endif
                </a>
            </li>
        @endcan

        @can('izindinas.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izindinas.index') }}" class="nav-link {{ request()->is(['izindinas', 'izindinas/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-briefcase ti-md me-1"></i> Perjalanan Dinas
                    @if (!empty($notifikasi_izindinas))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izindinas }}</span>
                    @endif
                </a>
            </li>
        @endcan

        @can('izinkoreksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinkoreksi.index') }}"
                    class="nav-link {{ request()->is(['izinkoreksi', 'izinkoreksi/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-edit ti-md me-1"></i> Izin Koreksi
                    @if (!empty($notifikasi_izinkoreksi))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinkoreksi }}</span>
                    @endif
                </a>
            </li>
        @endcan

    </ul>

    <style>
        .nav-pills-custom .nav-link {
            color: #5d596c;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem 0.375rem 0 0;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-pills-custom .nav-link:hover {
            color: #002e65;
            background-color: rgba(0, 46, 101, 0.05);
        }

        .nav-pills-custom .nav-link.active {
            color: #002e65 !important;
            background-color: #fff !important;
            border-bottom: 3px solid #002e65;
            box-shadow: none !important;
        }

        .nav-pills-custom .nav-link i {
            font-size: 1.2rem;
        }
    </style>
@endif
