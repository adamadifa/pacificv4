@if (auth()->user()->hasAnyPermission(['setoranpenjualan.index', 'setorantransfer.index', 'setorangiro.index', 'setoranpusat.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('setoranpenjualan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('setoranpenjualan.index') }}" class="nav-link {{ request()->is(['setoranpenjualan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Setoran Penjualan
                </a>
            </li>
        @endcan

        @can('setorantransfer.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('setorantransfer.index') }}" class="nav-link {{ request()->is(['setorantransfer']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Setoran Transfer
                </a>
            </li>
        @endcan

        @can('setorangiro.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('setorangiro.index') }}" class="nav-link {{ request()->is(['setorangiro']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Setoran Giro
                </a>
            </li>
        @endcan

        @can('setoranpusat.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('setoranpusat.index') }}" class="nav-link {{ request()->is(['setoranpusat']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Setoran Pusat
                </a>
            </li>
        @endcan
    </ul>
@endif
