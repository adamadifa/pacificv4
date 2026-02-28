@if (auth()->user()->hasAnyPermission($gudang_jadi_mutasi_permission))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
        @can('sagudangjadi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sagudangjadi.index') }}"
                    class="nav-link {{ request()->is(['sagudangjadi', 'sagudangjadi/*']) ? 'active' : '' }}">
                    <i class="ti ti-database-import me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan
        @can('fsthpgudang.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('fsthpgudang.index') }}"
                    class="nav-link {{ request()->is(['fsthpgudang', 'fsthpgudang/*']) ? 'active' : '' }}">
                    <i class="ti ti-file-check me-1"></i> FSTHP
                </a>
            </li>
        @endcan
        @can('suratjalan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('suratjalan.index') }}"
                    class="nav-link {{ request()->is(['suratjalan', 'suratjalan/*', 'suratjalancabang', 'suratjalancabang/*']) ? 'active' : '' }}">
                    <i class="ti ti-truck me-1"></i> Surat Jalan
                </a>
            </li>
        @endcan
        @can('repackgudangjadi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('repackgudangjadi.index') }}"
                    class="nav-link {{ request()->is(['repackgudangjadi', 'repackgudangjadi/*']) ? 'active' : '' }}">
                    <i class="ti ti-recycle me-1"></i> Repack
                </a>
            </li>
        @endcan
        @can('rejectgudangjadi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('rejectgudangjadi.index') }}"
                    class="nav-link {{ request()->is(['rejectgudangjadi', 'rejectgudangjadi/*']) ? 'active' : '' }}">
                    <i class="ti ti-trash-x me-1"></i> Reject
                </a>
            </li>
        @endcan
        @can('lainnyagudangjadi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('lainnyagudangjadi.index') }}"
                    class="nav-link {{ request()->is(['lainnyagudangjadi', 'lainnyagudangjadi/*']) ? 'active' : '' }}">
                    <i class="ti ti-file-description me-1"></i> Lainnya
                </a>
            </li>
        @endcan
    </ul>

    <style>
        .nav-pills-custom .nav-link {
            color: #5d596c;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem 0.375rem 0 0;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-pills-custom .nav-link:hover {
            color: #002e65;
            background-color: rgba(0, 46, 101, 0.05);
        }

        .nav-pills-custom .nav-link.active {
            color: #002e65 !important;
            background-color: #fff !important;
            border-bottom: 3px solid #002e65;
            box-shadow: none !important;
        }

        .nav-pills-custom .nav-link i {
            font-size: 1.2rem;
        }
    </style>
@endif
