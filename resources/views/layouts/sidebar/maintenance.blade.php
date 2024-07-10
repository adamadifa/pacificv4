<li class="menu-item {{ request()->is(['barangmasukmaintenance']) ? 'open' : '' }}">
    @if (auth()->user()->hasAnyPermission(['barangmasukmtc.index', 'barangkeluarmtc.index']))
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-tools-kitchen-2"></i>
            <div>Maintenance</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['barangmasukmtc.index']))
                <li class="menu-item {{ request()->is(['barangmasukmaintenance']) ? 'active' : '' }}">
                    <a href="{{ route('barangmasukmtc.index') }}" class="menu-link">
                        <div>Mutasi</div>
                    </a>
                </li>
            @endif
        </ul>
    @endif
</li>
