@if (auth()->user()->hasAnyPermission(['mutasibank.index', 'samutasibank.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('samutasibank.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('samutasibank.index') }}" class="nav-link {{ request()->is(['samutasibank']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan

        @can('mutasibank.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('mutasibank.index') }}" class="nav-link {{ request()->is(['mutasibank']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Mutasi Bank
                </a>
            </li>
        @endcan
    </ul>
@endif
