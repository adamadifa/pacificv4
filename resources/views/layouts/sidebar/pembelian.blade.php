<li class="menu-item {{ request()->is(['pembelian', 'pembelian/*']) ? 'open' : '' }}">

    @if (auth()->user()->hasAnyPermission(['pembelian.index']))
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
            <div>Pembelian</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['pembelian.index']))
                <li class="menu-item {{ request()->is(['pembelian', 'pembelian/*']) ? 'active' : '' }}">
                    <a href="{{ route('pembelian.index') }}" class="menu-link">
                        <div>Pembelian</div>
                    </a>
                </li>
            @endif
        </ul>
    @endif
</li>
