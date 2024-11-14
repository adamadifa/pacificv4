 <!-- Menu -->

 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
     <div class="app-brand demo">
         <a href="index.html" class="app-brand-link">
             <span class="app-brand-logo demo">
                 <img src="{{ asset('assets/img/logo/logoportal64.png') }}" alt="" width="64">
             </span>
             <span class="app-brand-text demo menu-text fw-bold">ortal</span>
         </a>

         <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
             <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
             <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
         </a>
     </div>

     <div class="menu-inner-shadow"></div>

     <ul class="menu-inner py-1">
         <!-- Dashboards -->
         <li class="menu-item {{ request()->is(['dashboard', 'dashboard/*']) ? 'active' : '' }}">
             <a href="{{ route('dashboard') }}" class="menu-link">
                 <i class="menu-icon tf-icons ti ti-home"></i>
                 <div>Dashboard</div>
             </a>
         </li>
         @if (auth()->user()->hasAnyPermission(['dashboard.sfa']))
             <li class="menu-item {{ request()->is(['sfa/dashboard', 'sfa/dashboard/*']) ? 'active' : '' }}">
                 <a href="{{ route('dashboard.sfa') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-home"></i>
                     <div>Dashboard SFA</div>
                 </a>
             </li>
         @endif

         @if (auth()->user()->hasAnyPermission(['sfa.trackingsalesman']))
             <li class="menu-item {{ request()->is(['sfa/trackingsalesman']) ? 'active' : '' }}">
                 <a href="{{ route('sfa.trackingsalesman') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-location"></i>
                     <div>Tracking Salesman</div>
                 </a>
             </li>
         @endif
         <!-- Salesman -->
         @if (auth()->user()->hasAnyPermission(['sfa.pelanggan']))
             <li class="menu-item {{ request()->is(['sfa/pelanggan']) ? 'active' : '' }}">
                 <a href="{{ route('sfa.pelanggan') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-users"></i>
                     <div>Pelanggan</div>
                 </a>
             </li>
         @endif

         @if (auth()->user()->hasAnyPermission(['sfa.penjualan']))
             <li class="menu-item {{ request()->is(['sfa/penjualan']) ? 'active' : '' }}">
                 <a href="{{ route('sfa.penjualan') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-shopping-bag"></i>
                     <div>Penjualan</div>
                 </a>
             </li>
         @endif
         @include('layouts.sidebar.datamaster')
         @include('layouts.sidebar.produksi')
         @include('layouts.sidebar.gudang_bahan')
         @include('layouts.sidebar.gudang_logistik')
         @include('layouts.sidebar.gudang_jadi')
         @include('layouts.sidebar.gudang_cabang')
         @include('layouts.sidebar.marketing')
         @include('layouts.sidebar.pembelian')
         @include('layouts.sidebar.keuangan')
         @include('layouts.sidebar.accounting')
         @include('layouts.sidebar.maintenance')
         @include('layouts.sidebar.generalaffair')
         @include('layouts.sidebar.hrd')
         @include('layouts.sidebar.worksheetom')

         @if (auth()->user()->hasAnyPermission(['kirimlhp.index', 'kirimlpc.index', 'tutuplaporan.index']))
             <li class="menu-item {{ request()->is(['kirimlhp', 'kirimlpc', 'tutuplaporan']) ? 'open' : '' }} ">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-settings"></i>
                     <div>Utilities</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-item {{ request()->is(['kirimlhp', 'kirimlhp/*']) ? 'active' : '' }}">
                         <a href="{{ route('kirimlhp.index') }}" class="menu-link">
                             <div>Kirim LHP</div>
                         </a>
                     </li>
                     <li class="menu-item {{ request()->is(['kirimlpc', 'kirimlpc/*']) ? 'active' : '' }}">
                         <a href="{{ route('kirimlpc.index') }}" class="menu-link">
                             <div>Kirim LPC</div>
                         </a>
                     </li>
                     <li class="menu-item {{ request()->is(['tutuplaporan', 'tutuplaporan/*']) ? 'active' : '' }}">
                         <a href="{{ route('tutuplaporan.index') }}" class="menu-link">
                             <div>Tutup Laporan</div>
                         </a>
                     </li>
                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin', 'gm administrasi']))
             <li
                 class="menu-item {{ request()->is(['roles', 'roles/*', 'permissiongroups', 'permissiongroups/*', 'permissions', 'permissions/*', 'users', 'users/*']) ? 'open' : '' }} ">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-settings"></i>
                     <div>Settings</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-item {{ request()->is(['users', 'users/*']) ? 'active' : '' }}">
                         <a href="{{ route('users.index') }}" class="menu-link">
                             <div>User</div>
                         </a>
                     </li>
                     @if (auth()->user()->hasRole(['super admin']))
                         <li class="menu-item {{ request()->is(['roles', 'roles/*']) ? 'active' : '' }}">
                             <a href="{{ route('roles.index') }}" class="menu-link">
                                 <div>Role</div>
                             </a>
                         </li>
                         <li class="menu-item {{ request()->is(['permissions', 'permissions/*']) ? 'active' : '' }}"">
                             <a href="{{ route('permissions.index') }}" class="menu-link">
                                 <div>Permission</div>
                             </a>
                         </li>
                         <li class="menu-item  {{ request()->is(['permissiongroups', 'permissiongroups/*']) ? 'active' : '' }}">
                             <a href="{{ route('permissiongroups.index') }}" class="menu-link">
                                 <div>Group Permission</div>
                             </a>
                         </li>
                     @endif

                 </ul>
             </li>
         @endif



     </ul>
 </aside>
 <!-- / Menu -->
