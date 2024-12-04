@if (auth()->user()->hasAnyPermission(['monitoringprogram.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('ajuanprogramikatan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuanprogramikatan.index') }}"
                    class="nav-link {{ request()->is(['ajuanprogramikatan', 'ajuanprogramikatan/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ajuan Program Ikatan
                </a>
            </li>
        @endcan
        @can('pencairanprogramikt.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pencairanprogramikatan.index') }}"
                    class="nav-link {{ request()->is(['pencairanprogramikatan', 'pencairanprogramikatan/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ajuan Pencairan Program Ikatan
                </a>
            </li>
        @endcan
        @can('pencairanprogram.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pencairanprogram.index') }}"
                    class="nav-link {{ request()->is(['pencairanprogram', 'pencairanprogram/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ajuan Pencairan Program Kumulatif
                </a>
            </li>
        @endcan


    </ul>
@endif
