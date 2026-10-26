@if (auth()->user()->hasAnyPermission(['dashboard.marketing', 'dashboard.gudang', 'dashboard.produksi', 'dashboard.generalaffair', 'dashboard.grafik']) || auth()->user()->hasRole(['super admin', 'direktur', 'gm marketing', 'regional sales manager', 'manager keuangan', 'regional operation manager']))
    @can('dashboard.marketing')
        <li class="nav-item" role="presentation">
            <a href="{{ route('dashboard.marketing') }}" class="nav-link {{ request()->is(['dashboard', 'dashboard/marketing']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-chart-histogram ti-xs me-1"></i> Marketing
            </a>
        </li>
    @endcan

    @if (auth()->user()->hasRole(['super admin', 'direktur', 'regional operation manager', 'rom']) || auth()->user()->can('dashboard.grafik'))
        <li class="nav-item" role="presentation">
            <a href="{{ route('dashboard.grafik') }}" class="nav-link {{ request()->routeIs('dashboard.grafik') ? 'active' : '' }}">
                <i class="tf-icons ti ti-chart-dots ti-xs me-1"></i> Grafik Cash In
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{ route('dashboard.grafik.qty') }}" class="nav-link {{ request()->routeIs('dashboard.grafik.qty') ? 'active' : '' }}">
                <i class="tf-icons ti ti-box ti-xs me-1"></i> Grafik Qty Produk
            </a>
        </li>
    @endif

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
