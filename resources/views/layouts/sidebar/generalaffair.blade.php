<li class="menu-item {{ request()->is(['mutasikendaraan', 'servicekendaraan', 'servicekendaraan/*']) ? 'open' : '' }}">
    @if (auth()->user()->hasAnyPermission(['mutasikendaraan.index', 'servicekendaraan.index']))
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
        </ul>
    @endif
</li>