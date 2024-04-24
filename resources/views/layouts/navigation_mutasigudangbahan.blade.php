@if (auth()->user()->hasAnyPermission($gudang_bahan_mutasi_permission))
   <ul class="nav nav-tabs" role="tablist">
      @can('sagudangbahan.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('sagudangbahan.index') }}"
               class="nav-link {{ request()->is(['sagudangbahan', 'sagudangbahan/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Awal
            </a>
         </li>
      @endcan
      @can('sahargagb.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('sahargagb.index') }}"
               class="nav-link {{ request()->is(['sahargagb', 'sahargagb/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Awal Harga
            </a>
         </li>
      @endcan
      @can('opgudangbahan.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('opgudangbahan.index') }}"
               class="nav-link {{ request()->is(['opgudangbahan', 'opgudangbahan/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> Opname
            </a>
         </li>
      @endcan
      @can('barangmasukgb.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('barangmasukgudangbahan.index') }}"
               class="nav-link {{ request()->is(['barangmasukgudangbahan', 'barangmasukgudangbahan/*']) ? 'active' : '' }}">
               <i class="tf-icons ti  ti-package-import ti-md me-1"></i> Barang Masuk
            </a>
         </li>
      @endcan
      @can('barangkeluargb.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('barangkeluargudangbahan.index') }}"
               class="nav-link {{ request()->is(['barangkeluargudangbahan', 'barangkeluargudangbahan/*']) ? 'active' : '' }}">
               <i class="tf-icons ti  ti-package-export ti-md me-1"></i> Barang Keluar
            </a>
         </li>
      @endcan
   </ul>
@endif
