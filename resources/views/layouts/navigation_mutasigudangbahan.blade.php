@if (auth()->user()->hasAnyPermission($gudang_bahan_mutasi_permission))
   <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
      @can('sagudangbahan.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('sagudangbahan.index') }}"
               class="nav-link {{ request()->is(['sagudangbahan', 'sagudangbahan/*']) ? 'active' : '' }}">
               <i class="ti ti-database-import me-1"></i> Saldo Awal
            </a>
         </li>
      @endcan
      @can('sahargagb.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('sahargagb.index') }}"
               class="nav-link {{ request()->is(['sahargagb', 'sahargagb/*']) ? 'active' : '' }}">
               <i class="ti ti-currency-dollar me-1"></i> Saldo Awal Harga
            </a>
         </li>
      @endcan
      @can('opgudangbahan.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('opgudangbahan.index') }}"
               class="nav-link {{ request()->is(['opgudangbahan', 'opgudangbahan/*']) ? 'active' : '' }}">
               <i class="ti ti-clipboard-list me-1"></i> Opname
            </a>
         </li>
      @endcan
      @can('barangmasukgb.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('barangmasukgudangbahan.index') }}"
               class="nav-link {{ request()->is(['barangmasukgudangbahan', 'barangmasukgudangbahan/*']) ? 'active' : '' }}">
               <i class="ti ti-package-import me-1"></i> Barang Masuk
            </a>
         </li>
      @endcan
      @can('barangkeluargb.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('barangkeluargudangbahan.index') }}"
               class="nav-link {{ request()->is(['barangkeluargudangbahan', 'barangkeluargudangbahan/*']) ? 'active' : '' }}">
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
