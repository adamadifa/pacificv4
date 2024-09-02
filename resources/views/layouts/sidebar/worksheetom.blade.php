@if (auth()->user()->hasAnyPermission($produksi_permission))
    <li class="menu-item {{ request()->is($produksi_request) ? 'open' : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-box"></i>
            <div>Produksi</div>
        </a>
        <ul class="menu-sub">
        </ul>
    </li>
@endif
