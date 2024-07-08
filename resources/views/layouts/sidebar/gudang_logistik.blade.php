@if (auth()->user()->hasAnyPermission($gudang_logistik_permission))
    <li class="menu-item {{ request()->is($gudang_logistik_request) ? 'open' : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
            <div>Gudang Logistik</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission($gudang_logistik_mutasi_permission))
                <li class="menu-item {{ request()->is($gudang_logistik_mutasi_request) ? 'active' : '' }}">
                    <a href="{{ route('barangmasukgudanglogistik.index') }}" class="menu-link">
                        <div>Mutasi Barang</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission($gudang_logistik_laporan_permission))
                <li class="menu-item {{ request()->is(['laporangudanglogistik', 'laporangudanglogistik/*']) ? 'active' : '' }}">
                    <a href="{{ route('laporangudanglogistik.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
