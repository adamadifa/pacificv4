@if (auth()->user()->hasAnyPermission([
            'pembelian.index',
            'pembelian.jatuhtempo',
            'jurnalkoreksi.index',
            'kontrabonpmb.index',
            'pb.pembelian',
            'pb.rekapsupplier',
            'pb.rekappembelian',
            'pb.kartuhutang',
            'pb.auh',
            'pb.bahankemasan',
            'pb.jurnalkoreksi',
            'pb.rekapakun',
            'pb.rekapkontrabon',
        ]))
    <li
        class="menu-item {{ request()->is(['pembelian', 'pembelian/*', 'jurnalkoreksi', 'kontrabonpembelian', 'kontrabonpembelian/*', 'laporanpembelian']) ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
            <div>Pembelian</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['pembelian.index']))
                <li class="menu-item {{ request()->is(['pembelian', 'pembelian/create', 'pembelian/edit']) ? 'active' : '' }}">
                    <a href="{{ route('pembelian.index') }}" class="menu-link">
                        <div>Pembelian</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['pembelian.jatuhtempo']))
                <li class="menu-item {{ request()->is(['pembelian/jatuhtempo']) ? 'active' : '' }}">
                    <a href="{{ route('pembelian.jatuhtempo') }}" class="menu-link">
                        <div>Jatuh Tempo</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['jurnalkoreksi.index']))
                <li class="menu-item {{ request()->is(['jurnalkoreksi']) ? 'active' : '' }}">
                    <a href="{{ route('jurnalkoreksi.index') }}" class="menu-link">
                        <div>Jurnal Koreksi</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['kontrabonpmb.index']))
                <li class="menu-item {{ request()->is(['kontrabonpembelian', 'kontrabonpembelian/*']) ? 'active' : '' }}">
                    <a href="{{ route('kontrabonpmb.index') }}" class="menu-link">
                        <div>Kontrabon</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission([
                        'pb.pembelian',
                        'pb.rekapsupplier',
                        'pb.rekappembelian',
                        'pb.kartuhutang',
                        'pb.auh',
                        'pb.bahankemasan',
                        'pb.jurnalkoreksi',
                        'pb.rekapakun',
                        'pb.rekapkontrabon',
                    ]))
                <li class="menu-item {{ request()->is(['laporanpembelian', 'laporanpembelian/*']) ? 'active' : '' }}">
                    <a href="{{ route('laporanpembelian.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
