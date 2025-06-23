@if (auth()->user()->hasAnyPermission(['mutasikeuangan.index', 'samutasikeuangan.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('sakasbesarkeuangan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sakasbesarkeuangan.index') }}" class="nav-link {{ request()->is(['sakasbesarkeuangan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Kas Besar
                </a>
            </li>
        @endcan
        @hasanyrole('manager keuangan')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sakasbesarkeuanganpusat.index') }}" class="nav-link {{ request()->is(['sakasbesarkeuanganpusat']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Kas Besar (Keuangan)
                </a>
            </li>
        @endhasanyrole
        @can('mutasikeuangan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('mutasikeuangan.index') }}" class="nav-link {{ request()->is(['mutasikeuangan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Mutasi Keuangan
                </a>
            </li>
        @endcan
    </ul>
@endif
