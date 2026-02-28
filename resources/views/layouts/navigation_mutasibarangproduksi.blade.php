@if (auth()->user()->hasAnyPermission(['sabarangproduksi.index', 'barangmasukproduksi.index', 'barangkeluarproduksi.index']))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
        @can('sabarangproduksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sabarangproduksi.index') }}"
                    class="nav-link {{ request()->is(['sabarangproduksi', 'sabarangproduksi/*']) ? 'active' : '' }}">
                    <i class="ti ti-file-description me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan
        @can('barangmasukproduksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangmasukproduksi.index') }}"
                    class="nav-link {{ request()->is(['barangmasukproduksi', 'barangmasukproduksi/*']) ? 'active' : '' }}">
                    <i class="ti ti-package-import me-1"></i> Barang Masuk
                </a>
            </li>
        @endcan
        @can('barangkeluarproduksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('barangkeluarproduksi.index') }}"
                    class="nav-link {{ request()->is(['barangkeluarproduksi', 'barangkeluarproduksi/*']) ? 'active' : '' }}">
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
