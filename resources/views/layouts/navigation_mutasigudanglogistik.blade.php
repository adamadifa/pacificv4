@if (auth()->user()->hasAnyPermission($gudang_logistik_mutasi_permission))
    <ul class="nav nav-tabs" role="tablist">
        @can('sagudanglogistik.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sagudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['sagudanglogistik', 'sagudanglogistik/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan
        @can('opgudanglogistik.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('opgudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['opgudanglogistik', 'opgudanglogistik/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Opname
                </a>
            </li>
        @endcan
        @can('barangmasukgl.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangmasukgudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['barangmasukgudanglogistik', 'barangmasukgudanglogistik/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti  ti-package-import ti-md me-1"></i> Barang Masuk
                </a>
            </li>
        @endcan
        @can('barangkeluargl.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangkeluargudanglogistik.index') }}"
                    class="nav-link {{ request()->is(['barangkeluargudanglogistik', 'barangkeluargudanglogistik/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti  ti-package-export ti-md me-1"></i> Barang Keluar
                </a>
            </li>
        @endcan
    </ul>
@endif
