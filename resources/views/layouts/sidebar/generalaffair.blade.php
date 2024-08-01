@if (auth()->user()->hasAnyPermission(['mutasikendaraan.index', 'servicekendaraan.index', 'badstokga.index', 'ga.servicekendaraan', 'ga.rekapbadstok']))
    <li
        class="menu-item {{ request()->is(['mutasikendaraan', 'servicekendaraan', 'servicekendaraan/*', 'badstokga', 'laporanga', 'laporanga/*']) ? 'open' : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-radioactive"></i>
            <div>General Afffair</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['mutasikendaraan.index']))
                <li class="menu-item {{ request()->is(['mutasikendaraan']) ? 'active' : '' }}">
                    <a href="{{ route('mutasikendaraan.index') }}" class="menu-link">
                        <div>Mutasi Kendaraan</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['servicekendaraan.index']))
                <li class="menu-item {{ request()->is(['servicekendaraan', 'servicekendaraan/*']) ? 'active' : '' }}">
                    <a href="{{ route('servicekendaraan.index') }}" class="menu-link">
                        <div>Service Kendaraan</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['badstokga.index']))
                <li class="menu-item {{ request()->is(['badstokga', 'badstokga/*']) ? 'active' : '' }}">
                    <a href="{{ route('badstokga.index') }}" class="menu-link">
                        <div>Bad Stok</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['ga.servicekendaraan', 'ga.rekapbadstok']))
                <li class="menu-item {{ request()->is(['laporanga', 'laporanga/*']) ? 'active' : '' }}">
                    <a href="{{ route('laporanga.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
