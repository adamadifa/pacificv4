<li class="menu-item {{ request()->is(['pembayarantransfer', 'pembayarangiro', 'setoranpenjualan', 'setorantransfer', 'setorangiro']) ? 'open' : '' }}">
    @if (auth()->user()->hasAnyPermission(['pembayarantransfer.index', 'pembayarangiro.index', 'setorangiro.index']))
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-moneybag"></i>
            <div>Keuangan</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['pembayarantransfer.index', 'pembayarangiro.index']))
                <li class="menu-item {{ request()->is(['pembayarantransfer', 'pembayarangiro']) ? 'active' : '' }}">
                    <a href="{{ route('pembayarantransfer.index') }}" class="menu-link">
                        <div>Transfer & Giro</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['setoranpenjualan.index', 'setorantransfer.index', 'setorangiro.index']))
                <li class="menu-item {{ request()->is(['setoranpenjualan', 'setorantransfer']) ? 'active' : '' }}">
                    <a href="{{ route('setoranpenjualan.index') }}" class="menu-link">
                        <div>Kas Besar</div>
                    </a>
                </li>
            @endif
        </ul>
    @endif
</li>