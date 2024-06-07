<li class="menu-item {{ request()->is(['pembayarantransfer', 'pembayarantransfer']) ? 'open' : '' }}">
   @if (auth()->user()->hasAnyPermission(['pembayarantransfer.index', 'pembayarangiro.index']))
      <a href="javascript:void(0);" class="menu-link menu-toggle">
         <i class="menu-icon tf-icons ti ti-moneybag"></i>
         <div>Keuangan</div>
      </a>
      <ul class="menu-sub">
         @if (auth()->user()->hasAnyPermission(['pembayarantransfer.index', 'pembayarangiro.index']))
            <li class="menu-item {{ request()->is(['pembayarantransfer', 'pembayarantransfer/*']) ? 'active' : '' }}">
               <a href="{{ route('pembayarantransfer.index') }}" class="menu-link">
                  <div>Transfer & Giro</div>
               </a>
            </li>
         @endif
      </ul>
   @endif
</li>
