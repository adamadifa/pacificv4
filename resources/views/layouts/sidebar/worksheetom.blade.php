@if (auth()->user()->hasAnyPermission([
            'worksheetom.oman',
            'worksheetom.komisisalesman',
            'worksheetom.insentifom',
            'worksheetom.komisidriverhelper',
            'worksheetom.costratio',
            'worksheetom.visitpelanggan',
            'worksheetom.monitoringretur',
            'worksheetom.monitoringprogram',
            'worksheetom.kebutuhancabang',
            'worksheetom.produkexpired',
            'worksheetom.evaluasisharing',
            'worksheetom.bbm',
            'worksheetom.ratiobs',
            'ajuanprogramikatan.index',
            'pencairanprogramikt.index',
            'pencairanprogram.index',
            'ajuankumulatif.index',
        ]))
    <li
        class="menu-item {{ request()->is([
            'worksheetom/omancabang',
            'worksheetom/oman',
            'worksheetom/komisisalesman',
            'worksheetom/insentifom',
            'worksheetom/komisidriverhelper',
            'worksheetom/costratio',
            'visitpelanggan',
            'worksheetom/monitoringretur',
            'worksheetom/monitoringprogram',
            'worksheetom/kebutuhancabang',
            'worksheetom/produkexpired',
            'worksheetom/evaluasisharing',
            'worksheetom/bbm',
            'worksheetom/ratiobs',
            'worksheetom/*',
            'ajuanprogramikatan',
            'ajuanprogramikatan/*',
            'pencairanprogramikatan',
            'pencairanprogramikatan/*',
            'pencairanprogram',
            'pencairanprogram/*',
            'ajuankumulatif',
            'ajuankumulatif/*',
            'settingkomisidriverhelper',
        ])
            ? 'open'
            : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-box"></i>
            <div>Worksheet OM</div>
        </a>
        <ul class="menu-sub">
            @can('omancabang.index')
                <li class="menu-item {{ request()->is('worksheetom/oman') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.oman') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>OMAN</div>
                    </a>
                </li>
            @endcan

            @can('mkt.komisisalesman')
                <li class="menu-item {{ request()->is('worksheetom/komisisalesman') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.komisisalesman') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Komisi Salesman</div>
                    </a>
                </li>
            @endcan

            @can('worksheetom.insentifom')
                <li class="menu-item {{ request()->is('worksheetom/insentifom') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.insentifom') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Insentif OM</div>
                    </a>
                </li>
            @endcan

            @can('mkt.komisidriverhelper')
                <li class="menu-item {{ request()->is('settingkomisidriverhelper') ? 'active' : '' }}">
                    <a href="{{ route('settingkomisidriverhelper.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Komisi Driver Helper</div>
                    </a>
                </li>
            @endcan

            @can('worksheetom.costratio')
                <li class="menu-item {{ request()->is('worksheetom/costratio') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.costratio') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Cost Ratio</div>
                    </a>
                </li>
            @endcan

            @can('worksheetom.visitpelanggan')
                <li class="menu-item {{ request()->is('visitpelanggan') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.visitpelanggan') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Visit Pelanggan</div>
                    </a>
                </li>
            @endcan

            @can('worksheetom.monitoringretur')
                <li class="menu-item {{ request()->is('worksheetom/monitoringretur') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.monitoringretur') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Monitoring Retur</div>
                    </a>
                </li>
            @endcan

            @can('monitoringprogram.index')
                <li
                    class="menu-item {{ request()->is(
                        'monitoringprogram',
                        'ajuanprogramikatan',
                        'ajuanprogramikatan/*',
                        'pencairanprogram',
                        'pencairanprogram/*',
                        'pencairanprogramikatan',
                        'pencairanprogramikatan/*',
                        'ajuankumulatif',
                        'ajuankumulatif/*',
                    )
                        ? 'active'
                        : '' }}">
                    @if ($level_user == 'manager keuangan' || $level_user == 'staff keuangan')
                        <a href="{{ route('pencairanprogramikatan.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-box"></i>
                            <div>Monitoring Program</div>
                        </a>
                    @else
                        <a href="{{ route('ajuanprogramikatan.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-box"></i>
                            <div>Monitoring Program</div>
                        </a>
                    @endif

                </li>
            @endcan

            @can('worksheetom.kebutuhancabang')
                <li class="menu-item {{ request()->is('worksheetom/kebutuhancabang') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.kebutuhancabang') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Kebutuhan Cabang</div>
                    </a>
                </li>
            @endcan

            @can('worksheetom.produkexpired')
                <li class="menu-item {{ request()->is('worksheetom/produkexpired') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.produkexpired') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Produk Expired</div>
                    </a>
                </li>
            @endcan

            @can('worksheetom.evaluasisharing')
                <li class="menu-item {{ request()->is('worksheetom/evaluasisharing') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.evaluasisharing') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Evaluasi Sharing</div>
                    </a>
                </li>
            @endcan

            @can('worksheetom.bbm')
                <li class="menu-item {{ request()->is('worksheetom/bbm') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.bbm') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>BBM</div>
                    </a>
                </li>
            @endcan

            @can('worksheetom.ratiobs')
                <li class="menu-item {{ request()->is('worksheetom/ratiobs') ? 'active' : '' }}">
                    <a href="{{ route('worksheetom.ratiobs') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-box"></i>
                        <div>Ratio BS</div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endif
