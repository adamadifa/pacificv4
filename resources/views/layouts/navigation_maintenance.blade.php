@if (auth()->user()->hasAnyPermission(['barangmasukmtc.index', 'barangkeluarmtc.index']))
    <ul class="nav nav-pills nav-pills-custom" role="tablist">
        @can('barangmasukmtc.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangmasukmtc.index') }}"
                    class="nav-link {{ request()->is(['barangmasukmaintenance']) ? 'active text-primary border-bottom border-3 border-primary' : '' }} border-0 rounded-0 pb-3 h-100 d-flex align-items-center">
                    <i class="ti ti-package-import me-2"></i> Barang Masuk
                </a>
            </li>
        @endcan

        @can('barangkeluarmtc.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangkeluarmtc.index') }}"
                    class="nav-link {{ request()->is(['barangkeluarmaintenance']) ? 'active text-primary border-bottom border-3 border-primary' : '' }} border-0 rounded-0 pb-3 h-100 d-flex align-items-center">
                    <i class="ti ti-package-export me-2"></i> Barang Keluar
                </a>
            </li>
        @endcan
    </ul>
    <style>
        .nav-pills-custom .nav-link {
            color: #677788;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-pills-custom .nav-link:hover {
            color: #002e65;
            background: rgba(0, 46, 101, 0.05);
        }

        .nav-pills-custom .nav-link.active {
            color: #002e65 !important;
            background: transparent !important;
            border-bottom: 3px solid #002e65 !important;
        }
    </style>
@endif
