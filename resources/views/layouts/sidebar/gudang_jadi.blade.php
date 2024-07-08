@if (auth()->user()->hasAnyPermission($gudang_jadi_permission))
    <li class="menu-item {{ request()->is($gudang_jadi_request) ? 'open' : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
            <div>Gudang Jadi</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission($gudang_jadi_mutasi_permission))
                <li class="menu-item {{ request()->is($gudang_jadi_mutasi_request) ? 'active' : '' }}">
                    <a href="{{ route('sagudangjadi.index') }}" class="menu-link">
                        <div>Mutasi Produk</div>
                    </a>
                </li>
            @endif
            @can('suratjalanangkutan.index')
                <li class="menu-item {{ request()->is(['suratjalanangkutan', 'suratjalanangkutan/*']) ? 'active' : '' }}">
                    <a href="{{ route('suratjalanangkutan.index') }}" class="menu-link">
                        <div>Angkutan</div>
                    </a>
                </li>
            @endcan
            @can('kontrabonangkutan.index')
                <li class="menu-item {{ request()->is(['kontrabonangkutan', 'kontrabonangkutan/*']) ? 'active' : '' }}">
                    <a href="{{ route('kontrabonangkutan.index') }}" class="menu-link">
                        <div>Kontrabon Angkutan</div>
                    </a>
                </li>
            @endcan
            @if (auth()->user()->hasAnyPermission($gudang_jadi_laporan_permission))
                <li class="menu-item {{ request()->is(['laporangudangjadi', 'laporangudangjadi/*']) ? 'active' : '' }}">
                    <a href="{{ route('laporangudangjadi.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
