@if (auth()->user()->hasAnyPermission(['ajuankumulatif.index', 'pencairanprogram.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('ajuankumulatif.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuankumulatif.index') }}" class="nav-link {{ request()->is(['ajuankumulatif', 'ajuankumulatif/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ajuan Program Kumulatif
                </a>
            </li>
        @endcan
        @can('pencairanprogram.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pencairanprogram.index') }}"
                    class="nav-link {{ request()->is(['pencairanprogram', 'pencairanprogram/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pencairan Program Kumulatif
                </a>
            </li>
        @endcan
    </ul>
@endif
