<li
   class="menu-item {{ request()->is(['omancabang', 'omancabang/*', 'oman', 'oman/*', 'permintaankiriman', 'permintaankiriman/*', 'targetkomisi', 'ratiodriverhelper', 'penjualan']) ? 'open' : '' }}">
   @if (auth()->user()->hasAnyPermission(['omancabang.index', 'oman.index', 'permintaankiriman.index', 'targetkomisi.index', 'penjualan.index']))
      <a href="javascript:void(0);" class="menu-link menu-toggle">
         <i class="menu-icon tf-icons ti ti-building-broadcast-tower"></i>
         <div>Marketing</div>
      </a>
      <ul class="menu-sub">
         @can('permintaankiriman.index')
            <li
               class="menu-item {{ request()->is(['permintaankiriman', 'permintaankiriman/*']) ? 'active' : '' }}">
               <a href="{{ route('permintaankiriman.index') }}" class="menu-link">
                  <div>Permintaan Kiriman</div>
               </a>
            </li>
         @endcan
         @if (auth()->user()->hasAnyPermission(['omancabang.index', 'oman.index']))
            @if (auth()->user()->hasAllPermissions(['omancabang.index', 'oman.index']))
               <li
                  class="menu-item {{ request()->is(['omancabang', 'omancabang/*', 'oman', 'oman/*']) ? 'active' : '' }}">
                  <a href="{{ route('omancabang.index') }}" class="menu-link">
                     <div>Order Management</div>
                  </a>
               </li>
            @elseif(auth()->user()->hasAllPermissions(['omancabang.index']))
               <li
                  class="menu-item {{ request()->is(['omancabang', 'omancabang/*', 'oman', 'oman/*']) ? 'active' : '' }}">
                  <a href="{{ route('omancabang.index') }}" class="menu-link">
                     <div>Order Management</div>
                  </a>
               </li>
            @elseif (auth()->user()->hasAllPermissions(['oman.index']))
               <li
                  class="menu-item {{ request()->is(['omancabang', 'omancabang/*', 'oman', 'oman/*']) ? 'active' : '' }}">
                  <a href="{{ route('oman.index') }}" class="menu-link">
                     <div>Order Management</div>
                  </a>
               </li>
            @endif
         @endif
         @if (auth()->user()->hasAnyPermission(['targetkomisi.index', 'ratiodriverhelper.index']))
            @if (auth()->user()->hasAllPermissions(['targetkomisi.index', 'ratiodriverhelper.index']))
               <li
                  class="menu-item {{ request()->is(['targetkomisi', 'targetkomisi/*', 'ratiodriverhelper']) ? 'active' : '' }}">
                  <a href="{{ route('targetkomisi.index') }}" class="menu-link">
                     <div>Komisi</div>
                  </a>
               </li>
            @elseif (auth()->user()->hasAllPermissions(['targetkomisi.index']))
               <li
                  class="menu-item {{ request()->is(['targetkomisi', 'targetkomisi/*', 'ratiodriverhelper']) ? 'active' : '' }}">
                  <a href="{{ route('targetkomisi.index') }}" class="menu-link">
                     <div>Komisi</div>
                  </a>
               </li>
            @elseif (auth()->user()->hasAllPermissions(['ratiodriverhelper.index']))
               <li
                  class="menu-item {{ request()->is(['ratiodriverhelper', 'ratiodriverhelper/*', 'ratiodriverhelper']) ? 'active' : '' }}">
                  <a href="{{ route('ratiodriverhelper.index') }}" class="menu-link">
                     <div>Komisi</div>
                  </a>
               </li>
            @endif
         @endif
         @can('penjualan.index')
            <li
               class="menu-item {{ request()->is(['penjualan']) ? 'active' : '' }}">
               <a href="{{ route('penjualan.index') }}" class="menu-link">
                  <div>Penjualan</div>
               </a>
            </li>
         @endcan
      </ul>
   @endif
</li>
