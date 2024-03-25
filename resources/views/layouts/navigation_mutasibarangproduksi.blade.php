@if (auth()->user()->hasAnyPermission(['sabarangproduksi.index', 'barangmasukproduksi.index', 'barangkeluarproduksi.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('sabarangproduksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sabarangproduksi.index') }}"
                    class="nav-link {{ request()->is(['sabarangproduksi', 'sabarangproduksi/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan
        @can('barangmasukproduksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangmasukproduksi.index') }}"
                    class="nav-link {{ request()->is(['barangmasukproduksi', 'barangmasukproduksi/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-package-import ti-md me-1"></i> Barang Masuk
                </a>
            </li>
        @endcan
        @can('barangkeluarproduksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangkeluarproduksi.index') }}"
                    class="nav-link {{ request()->is(['barangkeluarproduksi', 'barangkeluarproduksi/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-package-export ti-md me-1"></i> Barang Keluar
                </a>
            </li>
        @endcan

    </ul>
@endif
{{-- @can('sabarangproduksi.index')
                             <li
                                 class="menu-item {{ request()->is(['sabarangproduksi', 'sabarangproduksi/*']) ? 'active' : '' }}">
                                 <a href="{{ route('sabarangproduksi.index') }}" class="menu-link">
                                     <div>Saldo Awal</div>
                                 </a>
                             </li>
                         @endcan
                         @can('barangmasukproduksi.index')
                             <li
                                 class="menu-item {{ request()->is(['barangmasukproduksi', 'barangmasukproduksi/*']) ? 'active' : '' }}">
                                 <a href="{{ route('barangmasukproduksi.index') }}" class="menu-link">
                                     <div>Barang Masuk</div>
                                 </a>
                             </li>
                         @endcan
                         @can('barangkeluarproduksi.index')
                             <li
                                 class="menu-item {{ request()->is(['barangkeluarproduksi', 'barangkeluarproduksi/*']) ? 'active' : '' }}">
                                 <a href="{{ route('barangkeluarproduksi.index') }}" class="menu-link">
                                     <div>Barang Keluar</div>
                                 </a>
                             </li>
                         @endcan --}}
