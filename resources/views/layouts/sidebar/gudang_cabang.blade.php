<li class="menu-item {{ request()->is($gudang_cabang_request) ? 'open' : '' }}">
   @if (auth()->user()->hasAnyPermission($gudang_cabang_permission))
      <a href="javascript:void(0);" class="menu-link menu-toggle">
         <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
         <div>Gudang Cabang</div>
      </a>
   @endif
   <ul class="menu-sub">
      @if (auth()->user()->hasAnyPermission(['suratjalancabang.index']))
         <li class="menu-item {{ request()->is(['suratjalancabang']) ? 'active' : '' }}">
            <a href="{{ route('suratjalancabang.index') }}" class="menu-link">
               <div>Surat Jalan</div>
            </a>
         </li>
      @endif
      @if (auth()->user()->hasAnyPermission(['dpb.index']))
         <li class="menu-item {{ request()->is(['dpb']) ? 'active' : '' }}">
            <a href="{{ route('dpb.index') }}" class="menu-link">
               <div>DPB</div>
            </a>
         </li>
      @endif
   </ul>
</li>
