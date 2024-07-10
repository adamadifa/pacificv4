@if (auth()->user()->hasAnyPermission(['hpp.index', 'hargaawalhpp.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('hpp.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('hpp.index') }}" class="nav-link {{ request()->is(['hpp']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Harga HPP
                </a>
            </li>
        @endcan

        @can('hargaawalhpp.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('hargaawalhpp.index') }}" class="nav-link {{ request()->is(['hargaawalhpp']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Harga Awal
                </a>
            </li>
        @endcan
    </ul>
@endif
