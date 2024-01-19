 <!-- Menu -->

 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
     <div class="app-brand demo">
         <a href="index.html" class="app-brand-link">
             <span class="app-brand-logo demo">
                 <img src="{{ asset('assets/img/logo/hibah.png') }}" alt="" width="64">
             </span>
             <span class="app-brand-text demo menu-text fw-bold"><i><b>e</b></i>Hibah</span>
         </a>

         <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
             <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
             <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
         </a>
     </div>

     <div class="menu-inner-shadow"></div>

     <ul class="menu-inner py-1">
         <!-- Dashboards -->
         <li
             class="menu-item {{ request()->is([
                 'roles',
                 'roles/*',
                 'permissiongroups',
                 'permissiongroups/*',
                 'permissions',
                 'permissions/*',
                 'users',
                 'users/*',
             ])
                 ? 'open'
                 : '' }} ">
             <a href="javascript:void(0);" class="menu-link menu-toggle">
                 <i class="menu-icon tf-icons ti ti-settings"></i>
                 <div>Settings</div>
                 <div class="badge bg-primary rounded-pill ms-auto">5</div>
             </a>
             <ul class="menu-sub">
                 <li class="menu-item {{ request()->is(['users', 'users/*']) ? 'active' : '' }}">
                     <a href="{{ route('users.index') }}" class="menu-link">
                         <div>User</div>
                     </a>
                 </li>
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
             </ul>
         </li>


     </ul>
 </aside>
 <!-- / Menu -->
