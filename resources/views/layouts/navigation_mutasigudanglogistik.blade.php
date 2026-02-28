@if (auth()->user()->hasAnyPermission($gudang_logistik_mutasi_permission))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
        @can('sagudanglogistik.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sagudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['sagudanglogistik', 'sagudanglogistik/*']) ? 'active' : '' }}">
                    <i class="ti ti-database-import me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan
        @can('opgudanglogistik.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('opgudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['opgudanglogistik', 'opgudanglogistik/*']) ? 'active' : '' }}">
                    <i class="ti ti-clipboard-list me-1"></i> Opname
                </a>
            </li>
        @endcan
        @can('barangmasukgl.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangmasukgudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['barangmasukgudanglogistik', 'barangmasukgudanglogistik/*']) ? 'active' : '' }}">
                    <i class="ti ti-package-import me-1"></i> Barang Masuk
                </a>
            </li>
        @endcan
        @can('barangkeluargl.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangkeluargudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['barangkeluargudanglogistik', 'barangkeluargudanglogistik/*']) ? 'active' : '' }}">
                    <i class="ti ti-package-export me-1"></i> Barang Keluar
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
