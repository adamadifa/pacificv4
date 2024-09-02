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
        ]))
    <li
        class="menu-item {{ request()->is([
            '/worksheetom/omancabang',
            '/worksheetom/oman',
            '/worksheetom/komisisalesman',
            '/worksheetom/insentifom',
            '/worksheetom/komisidriverhelper',
            '/worksheetom/costratio',
            '/worksheetom/visitpelanggan',
            '/worksheetom/monitoringretur',
            '/worksheetom/monitoringprogram',
            '/worksheetom/kebutuhancabang',
            '/worksheetom/produkexpired',
            '/worksheetom/evaluasisharing',
            '/worksheetom/bbm',
            '/worksheetom/ratiobs',
        ])
            ? 'open'
            : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-box"></i>
            <div>Worksheet OM</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('worksheetom/omancabang') ? 'open' : '' }}">
                <a href="{{ route('omancabang.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>OMAN Cabang</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/oman') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.oman') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>OMAN</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/komisisalesman') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.komisisalesman') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Komisi Salesman</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/insentifom') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.insentifom') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Insentif OM</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/komisidriverhelper') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.komisidriverhelper') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Komisi Driver Helper</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/costratio') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.costratio') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Cost Ratio</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/visitpelanggan') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.visitpelanggan') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Visit Pelanggan</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/monitoringretur') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.monitoringretur') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Monitoring Retur</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/monitoringprogram') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.monitoringprogram') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Monitoring Program</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/kebutuhancabang') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.kebutuhancabang') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Kebutuhan Cabang</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/produkexpired') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.produkexpired') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Produk Expired</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/evaluasisharing') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.evaluasisharing') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Evaluasi Sharing</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/bbm') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.bbm') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>BBM</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('worksheetom/ratiobs') ? 'open' : '' }}">
                <a href="{{ route('worksheetom.ratiobs') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-box"></i>
                    <div>Ratio BS</div>
                </a>
            </li>
        </ul>
    </li>
@endif
