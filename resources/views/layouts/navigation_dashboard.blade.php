@if (auth()->user()->hasAnyPermission(['dashboard.marketing', 'dashboard.gudang', 'dashboard.produksi', 'dashboard.generalaffair']))
    @can('dashboard.marketing')
        <li class="nav-item" role="presentation">
            <a href="{{ route('dashboard.marketing') }}" class="nav-link {{ request()->is(['dashboard', 'dashboard/marketing']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-chart-histogram ti-xs me-1"></i> Marketing
            </a>
        </li>
    @endcan

    @can('dashboard.gudang')
        <li class="nav-item" role="presentation">
            <a href="{{ route('dashboard.gudang') }}" class="nav-link  {{ request()->is(['dashboard/gudang']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-building-warehouse ti-xs me-1"></i> Gudang
            </a>
        </li>
    @endcan
    @can('dashboard.hrd')
        <li class="nav-item" role="presentation">
            <a type="button" href="{{ route('dashboard.hrd') }}" class="nav-link {{ request()->is(['dashboard/hrd']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-users ti-xs me-1"></i> HRD
            </a>
        </li>
    @endcan
    @can('dashboard.produksi')
        <li class="nav-item" role="presentation">
            <a href="{{ route('dashboard.produksi') }}" class="nav-link {{ request()->is(['dashboard/produksi']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-box ti-xs me-1"></i> Produksi
            </a>
        </li>
    @endcan
    @can('dashboard.generalaffair')
        <li class="nav-item" role="presentation">
            <a type="button" href="{{ route('dashboard.generalaffair') }}"
                class="nav-link {{ request()->is(['dashboard/generalaffair']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-building-warehouse ti-xs me-1"></i> General Affair
            </a>
        </li>
    @endcan
@endif
