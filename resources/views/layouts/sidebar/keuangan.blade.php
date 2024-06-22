<li
    class="menu-item {{ request()->is([
        'pembayarantransfer',
        'pembayarangiro',
        'setoranpenjualan',
        'setorantransfer',
        'setorangiro',
        'setoranpusat',
        'logamtokertas',
        'sakasbesar',
        'ajuantransfer',
        'kaskecil',
        'ledger',
        'saledger',
        'mutasibank',
        'samutasibank',
        'pjp',
    ])
        ? 'open'
        : '' }}">
    @if (auth()->user()->hasAnyPermission([
                'pembayarantransfer.index',
                'pembayarangiro.index',
                'setorangiro.index',
                'setoranpusat.index',
                'ajuantransfer.index',
                'kaskecil.index',
                'ledger.index',
                'saledger.index',
                'mutasibank.index',
                'pjp.index',
            ]))
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
            @if (auth()->user()->hasAnyPermission([
                        'setoranpenjualan.index',
                        'setorantransfer.index',
                        'setorangiro.index',
                        'setoranpusat.index',
                        'logamtokertas.index',
                        'sakasbesar.index',
                    ]))
                <li
                    class="menu-item {{ request()->is(['setoranpenjualan', 'setorantransfer', 'setorangiro', 'setoranpusat', 'logamtokertas', 'sakasbesar']) ? 'active' : '' }}">
                    <a href="{{ route('sakasbesar.index') }}" class="menu-link">
                        <div>Kas Besar</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['ajuantransfer.index']))
                <li class="menu-item {{ request()->is(['ajuantransfer']) ? 'active' : '' }}">
                    <a href="{{ route('ajuantransfer.index') }}" class="menu-link">
                        <div>Ajuan Transfer Dana</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['kaskecil.index']))
                <li class="menu-item {{ request()->is(['kaskecil']) ? 'active' : '' }}">
                    <a href="{{ route('kaskecil.index') }}" class="menu-link">
                        <div>Kas Kecil</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['ledger.index', 'saledger.index']))
                <li class="menu-item {{ request()->is(['ledger', 'saledger']) ? 'active' : '' }}">
                    <a href="{{ route('ledger.index') }}" class="menu-link">
                        <div>Ledger</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['mutasibank.index']))
                <li class="menu-item {{ request()->is(['mutasibank', 'samutasibank']) ? 'active' : '' }}">
                    <a href="{{ route('mutasibank.index') }}" class="menu-link">
                        <div>Mutasi Bank</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['pjp.index']))
                <li class="menu-item {{ request()->is(['pjp', 'bayarpjp']) ? 'active' : '' }}">
                    <a href="{{ route('pjp.index') }}" class="menu-link">
                        <div>PJP</div>
                    </a>
                </li>
            @endif
        </ul>
    @endif
</li>
