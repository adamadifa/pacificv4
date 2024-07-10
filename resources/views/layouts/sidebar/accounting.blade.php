<li class="menu-item {{ request()->is(['coa', 'costratio', 'jurnalumum', 'hpp', 'hargaawalhpp']) ? 'open' : '' }}">
    @if (auth()->user()->hasAnyPermission(['coa.index', 'costratio.index', 'jurnalumum.index', 'hpp.index', 'hargawalahpp.index']))
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-scale"></i>
            <div>Accounting</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['coa.index']))
                <li class="menu-item {{ request()->is(['coa']) ? 'active' : '' }}">
                    <a href="{{ route('coa.index') }}" class="menu-link">
                        <div>Chart of Account (COA)</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['costratio.index']))
                <li class="menu-item {{ request()->is(['costratio']) ? 'active' : '' }}">
                    <a href="{{ route('costratio.index') }}" class="menu-link">
                        <div>Cost Ratio</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['jurnalumum.index']))
                <li class="menu-item {{ request()->is(['jurnalumum']) ? 'active' : '' }}">
                    <a href="{{ route('jurnalumum.index') }}" class="menu-link">
                        <div>Jurnal Umum</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['hpp.index', 'hargawalahpp.index']))
                <li class="menu-item {{ request()->is(['hpp', 'hargaawalhpp', 'hargaawalhpp/*']) ? 'active' : '' }}">
                    <a href="{{ route('hpp.index') }}" class="menu-link">
                        <div>HPP</div>
                    </a>
                </li>
            @endif
        </ul>
    @endif
</li>
