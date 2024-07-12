@if (auth()->user()->hasAnyPermission(['barangmasukmtc.index', 'barangkeluarmtc.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('barangmasukmtc.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangmasukmtc.index') }}" class="nav-link {{ request()->is(['barangmasukmaintenance']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Barang Masuk
                </a>
            </li>
        @endcan

        @can('barangkeluarmtc.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangkeluarmtc.index') }}" class="nav-link {{ request()->is(['barangkeluarmaintenance']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Barang Keluar
                </a>
            </li>
        @endcan


    </ul>
@endif
