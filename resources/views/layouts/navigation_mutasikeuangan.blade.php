@if (auth()->user()->hasAnyPermission(['mutasikeuangan.index', 'samutasikeuangan.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('samutasikeuangan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('samutasikeuangan.index') }}" class="nav-link {{ request()->is(['samutasikeuangan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan

        @can('mutasikeuangan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('mutasikeuangan.index') }}" class="nav-link {{ request()->is(['mutasikeuangan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Mutasi Keuangan
                </a>
            </li>
        @endcan
    </ul>
@endif
