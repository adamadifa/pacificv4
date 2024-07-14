<li class="menu-item {{ request()->is(['kontrakkerja', 'suratperingatan']) ? 'open' : '' }}">
    @if (auth()->user()->hasAnyPermission(['kontrakkerja.index', 'suratperingatan.index']))
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-users-group"></i>
            <div>HRD</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['kontrakkerja.index']))
                <li class="menu-item {{ request()->is(['kontrakkerja']) ? 'active' : '' }}">
                    <a href="{{ route('kontrakkerja.index') }}" class="menu-link">
                        <div>Kontrak Kerja</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['suratperingatan.index']))
                <li class="menu-item {{ request()->is(['suratperingatan']) ? 'active' : '' }}">
                    <a href="{{ route('suratperingatan.index') }}" class="menu-link">
                        <div>Surat Peringatan</div>
                    </a>
                </li>
            @endif
        </ul>
    @endif
</li>
