@if (auth()->user()->hasAnyPermission($gudang_cabang_permission))
    <li class="menu-item {{ request()->is($gudang_cabang_request) ? 'open' : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
            <div>Gudang Cabang</div>
        </a>

        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['sagudangcabang.index']))
                <li class="menu-item {{ request()->is(['sagudangcabang', 'sagudangcabang/*']) ? 'active' : '' }}">
                    <a href="{{ route('sagudangcabang.index') }}" class="menu-link">
                        <div>Saldo Awal</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['suratjalancabang.index']))
                <li class="menu-item {{ request()->is(['suratjalancabang']) ? 'active' : '' }}">
                    <a href="{{ route('suratjalancabang.index') }}" class="menu-link">
                        <div>Surat Jalan</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['transitin.index']))
                <li class="menu-item {{ request()->is(['transitin']) ? 'active' : '' }}">
                    <a href="{{ route('transitin.index') }}" class="menu-link">
                        <div>Transit IN</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['dpb.index']))
                <li class="menu-item {{ request()->is(['dpb']) ? 'active' : '' }}">
                    <a href="{{ route('dpb.index') }}" class="menu-link">
                        <div>DPB</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['reject.index']))
                <li class="menu-item {{ request()->is(['reject']) ? 'active' : '' }}">
                    <a href="{{ route('reject.index') }}" class="menu-link">
                        <div>Reject</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['repackcbg.index']))
                <li class="menu-item {{ request()->is(['repackcbg']) ? 'active' : '' }}">
                    <a href="{{ route('repackcbg.index') }}" class="menu-link">
                        <div>Repack</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['kirimpusat.index']))
                <li class="menu-item {{ request()->is(['kirimpusat']) ? 'active' : '' }}">
                    <a href="{{ route('kirimpusat.index') }}" class="menu-link">
                        <div>Kirim Pusat</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['penygudangcbg.index']))
                <li class="menu-item {{ request()->is(['penygudangcbg']) ? 'active' : '' }}">
                    <a href="{{ route('penygudangcbg.index') }}" class="menu-link">
                        <div>Penyesuaian</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission($gudang_cabang_laporan_permission))
                <li class="menu-item {{ request()->is(['laporangudangcabang']) ? 'active' : '' }}">
                    <a href="{{ route('laporangudangcabang.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
