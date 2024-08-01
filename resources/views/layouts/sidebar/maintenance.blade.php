@if (auth()->user()->hasAnyPermission(['barangmasukmtc.index', 'barangkeluarmtc.index', 'mtc.bahanbakar']))
    <li class="menu-item {{ request()->is(['barangmasukmaintenance', 'barangkeluarmaintenance', 'laporanmtc']) ? 'open' : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-tools-kitchen-2"></i>
            <div>Maintenance</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['barangmasukmtc.index']))
                <li class="menu-item {{ request()->is(['barangmasukmaintenance', 'barangkeluarmaintenance']) ? 'active' : '' }}">
                    <a href="{{ route('barangmasukmtc.index') }}" class="menu-link">
                        <div>Mutasi Barang</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['mtc.bahanbakar']))
                <li class="menu-item {{ request()->is(['laporanmtc', 'laporanmtc/*']) ? 'active' : '' }}">
                    <a href="{{ route('laporanmtc.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
