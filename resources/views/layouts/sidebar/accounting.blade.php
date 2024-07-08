<li class="menu-item {{ request()->is(['coa', 'costratio']) ? 'open' : '' }}">
    @if (auth()->user()->hasAnyPermission(['coa.index', 'costratio.index']))
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
        </ul>
    @endif
</li>
