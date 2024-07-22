@if (auth()->user()->hasAnyPermission(['izinabsen.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('izinabsen.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinabsen.index') }}" class="nav-link {{ request()->is(['izinabsen']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Absen
                </a>
            </li>
        @endcan




    </ul>
@endif
