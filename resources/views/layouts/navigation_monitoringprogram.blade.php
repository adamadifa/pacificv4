@if (auth()->user()->hasAnyPermission(['monitoringprogram.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('sagudanglogistik.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sagudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['sagudanglogistik', 'sagudanglogistik/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Kumulatif
                </a>
            </li>
        @endcan
        @can('sagudanglogistik.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sagudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['sagudanglogistik', 'sagudanglogistik/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ikatan
                </a>
            </li>
        @endcan

    </ul>
@endif
