@if (auth()->user()->hasAnyPermission(['pembayarantransfer.index', 'pembayarangiro.index']))
   <ul class="nav nav-tabs" role="tablist">
      @can('pembayarantransfer.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('pembayarantransfer.index') }}"
               class="nav-link {{ request()->is(['pembayarantransfer', 'pembayarantransfer/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> Transfer
            </a>
         </li>
      @endcan
      @can('pembayarangiro.index')
         <li class="nav-item" role="presentation">
            <a href="{{ route('pembayarangiro.index') }}"
               class="nav-link {{ request()->is(['pembayarangiro', 'pembayarangiro/*']) ? 'active' : '' }}">
               <i class="tf-icons ti ti-file-description ti-md me-1"></i> Giro
            </a>
         </li>
      @endcan
   </ul>
@endif
