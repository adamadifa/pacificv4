<!-- Menu -->
 <style>
     #layout-menu {
         background: linear-gradient(180deg, #002e65 0%, #001a3d 100%) !important;
         border-right: 0 !important;
         box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15) !important;
     }

     #layout-menu .app-brand {
         background: transparent;
         margin-bottom: 0.5rem;
         padding: 1.5rem 1rem;
         border-bottom: none;
     }

     .user-profile-sidebar {
         display: flex !important;
         flex-direction: row !important;
         align-items: center !important;
         padding: 0.75rem 1rem;
         margin: 0.5rem 0.75rem 1rem 0.75rem;
         background: #002e65;
         border-radius: 12px;
         transition: all 0.3s ease;
         gap: 0.75rem;
         position: relative;
         overflow: hidden;
         z-index: 1;
     }

     .user-profile-sidebar::before {
         content: '';
         position: absolute;
         top: -50%;
         left: -50%;
         width: 200%;
         height: 200%;
         background: conic-gradient(
             transparent,
             rgba(0, 210, 255, 0.4),
             transparent 30%
         );
         animation: rotate-glow 4s linear infinite;
         z-index: -2;
     }

     .user-profile-sidebar::after {
         content: '';
         position: absolute;
         inset: 1px;
         background: #002e65;
         border-radius: 11px;
         z-index: -1;
     }

     @keyframes rotate-glow {
         from { transform: rotate(0deg); }
         to { transform: rotate(360deg); }
     }

     .user-profile-sidebar .avatar-wrapper {
         position: relative;
         flex-shrink: 0;
         width: 48px;
         height: 48px;
     }

     .user-profile-sidebar .avatar {
         width: 100%;
         height: 100%;
         border-radius: 50%;
         padding: 2px;
         border: 2px solid rgba(255, 255, 255, 0.1);
     }

     .user-profile-sidebar .avatar img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         border-radius: 50%;
     }

     .user-profile-sidebar .status-indicator {
         position: absolute;
         bottom: 1px;
         right: 1px;
         width: 13px;
         height: 13px;
         background: #28c76f;
         border: 2px solid #002e65;
         border-radius: 50%;
         box-shadow: 0 0 5px rgba(40, 199, 111, 0.4);
     }

     .user-profile-sidebar .user-info-text {
         flex: 1;
         min-width: 0;
         display: flex;
         flex-direction: column;
         justify-content: center;
     }

     .user-profile-sidebar .user-name {
         font-size: 0.875rem;
         font-weight: 600;
         color: #fff;
         margin: 0;
         line-height: 1.2;
         overflow: hidden;
         text-overflow: ellipsis;
         white-space: nowrap;
     }

     .user-profile-sidebar .user-role-wrapper {
         display: flex;
         align-items: center;
         gap: 0.25rem;
         margin-top: 2px;
     }

     .user-profile-sidebar .role-icon {
         font-size: 0.75rem;
         color: #00d2ff;
         opacity: 0.8;
     }

     .user-profile-sidebar .user-role {
         font-size: 0.75rem;
         color: rgba(255, 255, 255, 0.5);
         text-transform: lowercase;
         font-weight: 400;
     }

     #layout-menu .menu-link {
         color: rgba(255, 255, 255, 0.7) !important;
         border-radius: 8px;
         margin: 2px 0;
         transition: all 0.3s ease;
         padding-right: 1.5rem !important;
     }

     #layout-menu .menu-toggle > .menu-link {
         padding-right: 4rem !important;
     }

     #layout-menu .menu-toggle::after {
         right: 1.5rem !important;
     }

     #layout-menu .menu-item.active>.menu-link {
         background: rgba(255, 255, 255, 0.1) !important;
         color: #fff !important;
         backdrop-filter: blur(10px);
         box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.1);
         position: relative;
     }

     #layout-menu .menu-item.active>.menu-link::before {
         content: '';
         position: absolute;
         left: 0;
         top: 15%;
         height: 70%;
         width: 4px;
         background: #00d2ff;
         border-radius: 0 4px 4px 0;
         box-shadow: 0 0 10px #00d2ff;
     }

     #layout-menu .menu-item.open>.menu-link {
         background: rgba(255, 255, 255, 0.03);
     }

     #layout-menu .menu-link:hover {
         background: rgba(255, 255, 255, 0.05) !important;
         color: #fff !important;
         transform: translateX(4px);
     }

     #layout-menu .menu-icon {
         color: rgba(255, 255, 255, 0.6) !important;
         transition: all 0.3s ease;
     }

     #layout-menu .menu-item.active .menu-icon,
     #layout-menu .menu-link:hover .menu-icon {
         color: #00d2ff !important;
         transform: scale(1.1);
     }

     #layout-menu .menu-inner-shadow {
         display: none !important;
     }

     /* Perfect Scrollbar Thumb */
     .ps__thumb-y {
         background-color: rgba(255, 255, 255, 0.2) !important;
     }
 </style>

 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
     <div class="app-brand demo">
         <a href="#" class="app-brand-link">
             <span class="app-brand-logo demo">
                 <img src="{{ asset('assets/img/logo/logoportal64.png') }}" alt="" width="50" style="filter: drop-shadow(0 0 8px rgba(0,210,255,0.4))">
             </span>
             <span class="app-brand-text demo menu-text fw-bold ms-2 text-white" style="font-size: 1.2rem; letter-spacing: 1px">PORTAL</span>
         </a>

         <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
             <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
             <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
         </a>
     </div>

     <div class="user-profile-sidebar d-none d-xl-block">
         @php
             $user = Auth::user();
             $level_user = $level_user ?? '';
             $photo = asset('assets/img/avatars/1.png');
             if ($user->username) {
                 $karyawan = DB::table('hrd_karyawan')->where('nik', $user->username)->select('foto')->first();
                 if ($karyawan && !empty($karyawan->foto)) {
                     $photo = url('/storage/karyawan/' . $karyawan->foto);
                 }
             }
         @endphp
         <div class="avatar-wrapper">
             <div class="avatar border-0">
                 <img src="{{ $photo }}" alt class="h-100 w-100 rounded-circle" onerror="this.src='{{ asset('assets/img/avatars/1.png') }}'" style="object-fit: cover;" />
             </div>
             <div class="status-indicator"></div>
         </div>
         <div class="user-info-text">
             <div class="user-name">
                 @php
                     $nameParts = explode(' ', trim($user->name));
                     echo count($nameParts) > 1 ? $nameParts[0] . ' ' . $nameParts[1] : $nameParts[0];
                 @endphp
             </div>
             <div class="user-role-wrapper">
                 <i class="ti ti-shield role-icon"></i>
                 <span class="user-role">{{ strtolower($level_user) }}</span>
             </div>
         </div>
     </div>

     <div class="menu-inner-shadow"></div>

     <ul class="menu-inner py-2 px-2">
         <!-- Dashboards -->
         <li class="menu-item {{ request()->is(['dashboard', 'dashboard/*']) ? 'active' : '' }}">
             <a href="{{ route('dashboard') }}" class="menu-link">
                 <i class="menu-icon tf-icons ti ti-smart-home"></i>
                 <div>Dashboard</div>
             </a>
         </li>
         @if (in_array($level_user, ['super admin', 'direktur', 'gm administrasi', 'manager keuangan', 'regional operation manager', 'spv accounting']))
             <li class="menu-item {{ request()->is(['dashboard/owner']) ? 'active' : '' }}">
                 <a href="{{ route('dashboard.owner') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-device-analytics"></i>
                     <div>Dashboard Owner</div>
                 </a>
             </li>
         @endif


         @if (auth()->user()->hasAnyPermission(['dashboard.sfa']))
             <li class="menu-item {{ request()->is(['sfa/dashboard', 'sfa/dashboard/*']) ? 'active' : '' }}">
                 <a href="{{ route('dashboard.sfa') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-chart-bar"></i>
                     <div>Dashboard SFA</div>
                 </a>
             </li>
         @endif

         @if (auth()->user()->hasAnyPermission(['sfa.trackingsalesman']))
             <li class="menu-item {{ request()->is(['sfa/trackingsalesman']) ? 'active' : '' }}">
                 <a href="{{ route('sfa.trackingsalesman') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-map-pin"></i>
                     <div>Tracking Salesman</div>
                 </a>
             </li>
         @endif
         <!-- Salesman -->
         @if (auth()->user()->hasAnyPermission(['sfa.pelanggan']))
             <li class="menu-item {{ request()->is(['sfa/pelanggan']) ? 'active' : '' }}">
                 <a href="{{ route('sfa.pelanggan') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-users-group"></i>
                     <div>Pelanggan</div>
                 </a>
             </li>
         @endif

         @if (auth()->user()->hasAnyPermission(['sfa.penjualan']))
             <li class="menu-item {{ request()->is(['sfa/penjualan']) ? 'active' : '' }}">
                 <a href="{{ route('sfa.penjualan') }}" class="menu-link">
                     <i class="menu-icon tf-icons ti ti-shopping-cart-check"></i>
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

         <li class="menu-header small text-uppercase" style="color: rgba(255,255,255,0.4)">
             <span class="menu-header-text">Utilities & Settings</span>
         </li>

         @if (auth()->user()->hasAnyPermission(['kirimlhp.index', 'kirimlpc.index', 'tutuplaporan.index', 'activitylog.index']) ||
                 auth()->user()->hasRole(['super admin', 'gm administrasi']))
             <li class="menu-item {{ request()->is(['kirimlhp', 'kirimlpc', 'tutuplaporan', 'activitylog', 'ticket']) ? 'open' : '' }} ">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-tool"></i>
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
                     @can('activitylog.index')
                         <li class="menu-item {{ request()->is(['activitylog', 'activitylog/*']) ? 'active' : '' }}">
                             <a href="{{ route('activitylog.index') }}" class="menu-link">
                                 <div>Log Aktivitas</div>
                             </a>
                         </li>
                     @endcan

                     @can('backup.database')
                         <li class="menu-item {{ request()->is(['backup-database', 'backup-database/*']) ? 'active' : '' }}">
                             <a href="{{ route('backup.database.index') }}" class="menu-link">
                                 <div>Backup Database</div>
                             </a>
                         </li>
                     @endcan

                     {{-- <li class="menu-item {{ request()->is(['ticket', 'ticket/*']) ? 'active' : '' }}">
                         <a href="{{ route('ticket.index') }}" class="menu-link">
                             <div>Ticket</div>
                         </a>
                     </li> --}}

                 </ul>
             </li>
         @endif
         @if (auth()->user()->hasRole(['super admin', 'gm administrasi']))
             <li
                 class="menu-item {{ request()->is(['roles', 'roles/*', 'permissiongroups', 'permissiongroups/*', 'permissions', 'permissions/*', 'users', 'users/*']) ? 'open' : '' }} ">
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-adjustments-horizontal"></i>
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

         <li class="menu-item {{ request()->is(['ticket', 'ticket/*']) ? 'active' : '' }}">
             <a href="{{ route('ticket.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons ti ti-ticket"></i>
                 <div>Ticket</div>
             </a>
         </li>

     </ul>
 </aside>
 <!-- / Menu -->
