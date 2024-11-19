@if (auth()->user()->hasAnyPermission(['monitoringprogram.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('sagudanglogistik.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('monitoringprogram.index') }}"
                    class="nav-link {{ request()->is(['monitoringprogram', 'monitoringprogram/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Program
                </a>
            </li>
        @endcan
        @can('sagudanglogistik.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pencairanprogram.index') }}"
                    class="nav-link {{ request()->is(['pencairanprogram', 'pencairanprogram/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ajuan Pencairan
                </a>
            </li>
        @endcan

    </ul>
@endif
