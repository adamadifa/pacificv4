@if (auth()->user()->hasAnyPermission(['setoranpenjualan.index']))
   <ul class="nav nav-tabs" role="tablist">
      @can('setoranpenjualan.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('setoranpenjualan.index') }}"
               class="nav-link {{ request()->is(['setoranpenjualan']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> Setoran Penjualan
            </a>
         </li>
      @endcan
   </ul>
@endif
