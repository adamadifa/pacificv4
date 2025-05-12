@if (auth()->user()->hasAnyPermission(['ajuanprogramenambulan.index', 'pencairanprogramenambulan.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('ajuanprogramenambulan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuanprogramenambulan.index') }}"
                    class="nav-link {{ request()->is(['ajuanprogramenambulan', 'ajuanprogramenambulan/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i>Ajuan Program Ikatan Enambulan
                </a>
            </li>
        @endcan
        @can('pencairanprogramenambulan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pencairanprogramenambulan.index') }}"
                    class="nav-link {{ request()->is(['pencairanprogramenambulan', 'pencairanprogramenambulan/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pencairan Program Ikatan Enambulan
                </a>
            </li>
        @endcan
    </ul>
@endif
