@if (auth()->user()->hasAnyPermission($gudang_jadi_mutasi_permission))
   <ul class="nav nav-tabs" role="tablist">
      @can('sagudangjadi.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('sagudangjadi.index') }}"
               class="nav-link {{ request()->is(['sagudangjadi', 'sagudangjadi/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Awal
            </a>
         </li>
      @endcan
      @can('fsthpgudang.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('fsthpgudang.index') }}"
               class="nav-link {{ request()->is(['fsthpgudang', 'fsthpgudang/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> FSTHP
            </a>
         </li>
      @endcan
      @can('suratjalan.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('suratjalan.index') }}"
               class="nav-link {{ request()->is(['suratjalan', 'suratjalan/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-truck ti-md me-1"></i> Surat Jalan
            </a>
         </li>
      @endcan
      @can('repackgudangjadi.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('repackgudangjadi.index') }}"
               class="nav-link {{ request()->is(['repackgudangjadi', 'repackgudangjadi/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-recycle ti-md me-1"></i> Repack
            </a>
         </li>
      @endcan
      @can('rejectgudangjadi.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('rejectgudangjadi.index') }}"
               class="nav-link {{ request()->is(['rejectgudangjadi', 'rejectgudangjadi/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-trash-x ti-md me-1"></i> Reject
            </a>
         </li>
      @endcan
      @can('lainnyagudangjadi.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('lainnyagudangjadi.index') }}"
               class="nav-link {{ request()->is(['lainnyagudangjadi', 'lainnyagudangjadi/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> Lainnya
            </a>
         </li>
      @endcan
   </ul>
@endif
