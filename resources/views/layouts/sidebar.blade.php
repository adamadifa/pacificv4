 <!-- Menu -->

 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
     <div class="app-brand demo">
         <a href="index.html" class="app-brand-link">
             <span class="app-brand-logo demo">
                 <img src="{{ asset('assets/img/logo/pcf.png') }}" alt="" width="32">
             </span>
             <span class="app-brand-text demo menu-text fw-bold"><i><b>P</b></i>acific</span>
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
                 'regional',
                 'regional/*',
                 'cabang',
                 'cabang/*',
                 'salesman',
                 'salesman/*',
                 'kategoriproduk',
                 'kategoriproduk/*',
                 'jenisproduk',
                 'jenisproduk/*',
                 'produk',
                 'produk/*',
                 'harga',
                 'harga/*',
                 'pelanggan',
                 'pelanggan/*',
                 'wilayah',
                 'wilayah/*',
                 'kendaraan',
                 'kendaraan/*',
                 'supplier',
                 'supplier/*',
                 'karyawan',
                 'karyawan/*',
                 'rekening',
                 'rekening/*',
                 'gaji',
                 'gaji/*',
                 'insentif',
                 'insentif/*',
                 'bpjskesehatan',
                 'bpjskesehatan/*',
                 'bpjstenagakerja',
                 'bpjstenagakerja/*',
                 'bufferstok',
                 'bufferstok/*',
             ])
                 ? 'open'
                 : '' }}">
             @if (auth()->user()->hasAnyPermission([
                         'regional.index',
                         'cabang.index',
                         'salesman.index',
                         'karyawan.index',
                         'gaji.index',
                         'insentif.index',
                         'bpjskesehatan.index',
                         'bpjstenagakerja.index',
                     ]))
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-database"></i>
                     <div>Data Master</div>
                 </a>
                 <ul class="menu-sub">
                     @can('regional.index')
                         <li class="menu-item {{ request()->is(['regional', 'regional/*']) ? 'active' : '' }}">
                             <a href="{{ route('regional.index') }}" class="menu-link">
                                 <div>Regional</div>
                             </a>
                         </li>
                     @endcan
                     @can('wilayah.index')
                         <li class="menu-item {{ request()->is(['wilayah', 'wilayah/*']) ? 'active' : '' }}">
                             <a href="{{ route('wilayah.index') }}" class="menu-link">
                                 <div>Wilayah / Rute</div>
                             </a>
                         </li>
                     @endcan
                     @can('cabang.index')
                         <li class="menu-item {{ request()->is(['cabang', 'cabang/*']) ? 'active' : '' }}">
                             <a href="{{ route('cabang.index') }}" class="menu-link">
                                 <div>Cabang</div>
                             </a>
                         </li>
                     @endcan
                     @can('salesman.index')
                         <li class="menu-item {{ request()->is(['salesman', 'salesman/*']) ? 'active' : '' }}">
                             <a href="{{ route('salesman.index') }}" class="menu-link">
                                 <div>Salesman</div>
                             </a>
                         </li>
                     @endcan
                     @can('kategoriproduk.index')
                         <li class="menu-item {{ request()->is(['kategoriproduk', 'kategoriproduk/*']) ? 'active' : '' }}">
                             <a href="{{ route('kategoriproduk.index') }}" class="menu-link">
                                 <div>Kategori Produk</div>
                             </a>
                         </li>
                     @endcan
                     @can('jenisproduk.index')
                         <li class="menu-item {{ request()->is(['jenisproduk', 'jenisproduk/*']) ? 'active' : '' }}">
                             <a href="{{ route('jenisproduk.index') }}" class="menu-link">
                                 <div>Jenis Produk</div>
                             </a>
                         </li>
                     @endcan
                     @can('produk.index')
                         <li class="menu-item {{ request()->is(['produk', 'produk/*']) ? 'active' : '' }}">
                             <a href="{{ route('produk.index') }}" class="menu-link">
                                 <div>Produk</div>
                             </a>
                         </li>
                     @endcan
                     @can('harga.index')
                         <li class="menu-item {{ request()->is(['harga', 'harga/*']) ? 'active' : '' }}">
                             <a href="{{ route('harga.index') }}" class="menu-link">
                                 <div>Harga</div>
                             </a>
                         </li>
                     @endcan
                     @can('pelanggan.index')
                         <li class="menu-item {{ request()->is(['pelanggan', 'pelanggan/*']) ? 'active' : '' }}">
                             <a href="{{ route('pelanggan.index') }}" class="menu-link">
                                 <div>Pelanggan</div>
                             </a>
                         </li>
                     @endcan

                     @can('kendaraan.index')
                         <li class="menu-item {{ request()->is(['kendaraan', 'kendaraan/*']) ? 'active' : '' }}">
                             <a href="{{ route('kendaraan.index') }}" class="menu-link">
                                 <div>Kendaraan</div>
                             </a>
                         </li>
                     @endcan

                     @can('supplier.index')
                         <li class="menu-item {{ request()->is(['supplier', 'supplier/*']) ? 'active' : '' }}">
                             <a href="{{ route('supplier.index') }}" class="menu-link">
                                 <div>Supplier</div>
                             </a>
                         </li>
                     @endcan

                     @can('karyawan.index')
                         <li class="menu-item {{ request()->is(['karyawan', 'karyawan/*']) ? 'active' : '' }}">
                             <a href="{{ route('karyawan.index') }}" class="menu-link">
                                 <div>Karyawan</div>
                             </a>
                         </li>
                     @endcan

                     @can('rekening.index')
                         <li class="menu-item {{ request()->is(['rekening', 'rekening/*']) ? 'active' : '' }}">
                             <a href="{{ route('rekening.index') }}" class="menu-link">
                                 <div>Rekening</div>
                             </a>
                         </li>
                     @endcan

                     @can('gaji.index')
                         <li class="menu-item {{ request()->is(['gaji', 'gaji/*']) ? 'active' : '' }}">
                             <a href="{{ route('gaji.index') }}" class="menu-link">
                                 <div>Gaji</div>
                             </a>
                         </li>
                     @endcan
                     @can('insentif.index')
                         <li class="menu-item {{ request()->is(['insentif', 'insentif/*']) ? 'active' : '' }}">
                             <a href="{{ route('insentif.index') }}" class="menu-link">
                                 <div>Insentif</div>
                             </a>
                         </li>
                     @endcan
                     @can('bpjskesehatan.index')
                         <li class="menu-item {{ request()->is(['bpjskesehatan', 'bpjskesehatan/*']) ? 'active' : '' }}">
                             <a href="{{ route('bpjskesehatan.index') }}" class="menu-link">
                                 <div>BPJS Kesehatan</div>
                             </a>
                         </li>
                     @endcan
                     @can('bpjstenagakerja.index')
                         <li
                             class="menu-item {{ request()->is(['bpjstenagakerja', 'bpjstenagakerja/*']) ? 'active' : '' }}">
                             <a href="{{ route('bpjstenagakerja.index') }}" class="menu-link">
                                 <div>BPJS Tenaga Kerja</div>
                             </a>
                         </li>
                     @endcan

                     @can('bufferstok.index')
                         <li class="menu-item {{ request()->is(['bufferstok', 'bufferstok/*']) ? 'active' : '' }}">
                             <a href="{{ route('bufferstok.index') }}" class="menu-link">
                                 <div>Buffer & Max Stok</div>
                             </a>
                         </li>
                     @endcan
                 </ul>
             @endif
         </li>
         <li
             class="menu-item {{ request()->is(['bpbj', 'bpbj/*', 'fsthp', 'fsthp/*', 'samutasiproduksi', 'samutasiproduksi/*'])
                 ? 'open'
                 : '' }}">
             @if (auth()->user()->hasAnyPermission(['bpbj.index']))
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-box"></i>
                     <div>Produksi</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-header small text-uppercase">
                         <span class="menu-header-text">Mutasi Produksi</span>
                     </li>
                     @can('samutasiproduksi.index')
                         <li
                             class="menu-item {{ request()->is(['samutasiproduksi', 'samutasiproduksi/*']) ? 'active' : '' }}">
                             <a href="{{ route('samutasiproduksi.index') }}" class="menu-link">
                                 <div>Saldo Awal</div>
                             </a>
                         </li>
                     @endcan
                     @can('bpbj.index')
                         <li class="menu-item {{ request()->is(['bpbj', 'bpbj/*']) ? 'active' : '' }}">
                             <a href="{{ route('bpbj.index') }}" class="menu-link">
                                 <div>BPBJ</div>
                             </a>
                         </li>
                     @endcan
                     @can('fsthp.index')
                         <li class="menu-item {{ request()->is(['fsthp', 'fsthp/*']) ? 'active' : '' }}">
                             <a href="{{ route('fsthp.index') }}" class="menu-link">
                                 <div>FSTHP</div>
                             </a>
                         </li>
                     @endcan
                     <li class="menu-header small text-uppercase">
                         <span class="menu-header-text">Mutasi Barang</span>
                     </li>
                 </ul>
             @endif
         </li>
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
                 <li
                     class="menu-item  {{ request()->is(['permissiongroups', 'permissiongroups/*']) ? 'active' : '' }}">
                     <a href="{{ route('permissiongroups.index') }}" class="menu-link">
                         <div>Group Permission</div>
                     </a>
                 </li>
             </ul>
         </li>


     </ul>
 </aside>
 <!-- / Menu -->
