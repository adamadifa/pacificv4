@if (auth()->user()->hasAnyPermission([
            'dashboard.marketing',
            'dashboard.gudang',
            'dashboard.produksi',
            'dashboard.generalaffair',
        ]))
    @can('dashboard.marketing')
        <li class="nav-item" role="presentation">
            <a type="button" class="nav-link {{ request()->is(['dashboard', 'dashboard/marketing']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-chart-histogram ti-xs me-1"></i> Marketing
            </a>
        </li>
    @endcan
    @can('dashboard.gudang')
        <li class="nav-item" role="presentation">
            <a type="button" class="nav-link">
                <i class="tf-icons ti ti-building-warehouse ti-xs me-1"></i> Gudang
            </a>
        </li>
    @endcan
    @can('dashboard.produksi')
        <li class="nav-item" role="presentation">
            <a href="{{ route('dashboard.produksi') }}"
                class="nav-link {{ request()->is(['dashboard/produksi']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-box ti-xs me-1"></i> Produksi
            </a>
        </li>
    @endcan
@endif