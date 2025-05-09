@if (auth()->user()->hasAnyPermission(['kaskecil.index', 'klaim.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('sakaskecil.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sakaskecil.index') }}" class="nav-link {{ request()->is(['sakaskecil']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Awal Kas Kecil
                </a>
            </li>
        @endcan
        @can('kaskecil.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('kaskecil.index') }}" class="nav-link {{ request()->is(['kaskecil']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Kas Kecil
                </a>
            </li>
        @endcan
        @can('klaimkaskecil.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('klaimkaskecil.index') }}" class="nav-link {{ request()->is(['klaimkaskecil']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Klaim
                </a>
            </li>
        @endcan

    </ul>
@endif
