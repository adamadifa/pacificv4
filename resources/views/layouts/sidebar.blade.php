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
         <li class="menu-item {{ request()->is(['dashboard', 'dashboard/*']) ? 'active' : '' }}">
             <a href="{{ route('dashboard') }}" class="menu-link">
                 <i class="menu-icon tf-icons ti ti-home"></i>
                 <div>Dashboard</div>
             </a>
         </li>
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
                 'barangproduksi',
                 'barangproduksi/*',
                 'tujuanangkutan',
                 'tujuanangkutan/*',
                 'angkutan',
                 'angkutan/*',
             ])
                 ? 'open'
                 : '' }}">
             @if (auth()->user()->hasAnyPermission([
                         'regional.index',
                         'cabang.index',
                         'salesman.index',
                         'kategoriproduk.index',
                         'jenisproduk.index',
                         'produk.index',
                         'hraga.index',
                         'pelanggan.index',
                         'wilayah.index',
                         'kendaraan.index',
                         'supplier.index',
                         'karyawan.index',
                         'rekening.index',
                         'gaji.index',
                         'insentif.index',
                         'bpjskesehatan.index',
                         'bpjstenagakerja.index',
                         'barangproduksi.index',
                         'bufferstok.index',
                     ]))
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-database"></i>
                     <div>Data Master</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-header small text-uppercase">
                         <span class="menu-header-text">MARKETING</span>
                     </li>
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
                     <li class="menu-header small text-uppercase">
                         <span class="menu-header-text">GENERAL AFFAIR</span>
                     </li>
                     @can('kendaraan.index')
                         <li class="menu-item {{ request()->is(['kendaraan', 'kendaraan/*']) ? 'active' : '' }}">
                             <a href="{{ route('kendaraan.index') }}" class="menu-link">
                                 <div>Kendaraan</div>
                             </a>
                         </li>
                     @endcan
                     <li class="menu-header small text-uppercase">
                         <span class="menu-header-text">PEMBELIAN</span>
                     </li>
                     @can('supplier.index')
                         <li class="menu-item {{ request()->is(['supplier', 'supplier/*']) ? 'active' : '' }}">
                             <a href="{{ route('supplier.index') }}" class="menu-link">
                                 <div>Supplier</div>
                             </a>
                         </li>
                     @endcan
                     <li class="menu-header small text-uppercase">
                         <span class="menu-header-text">HRD</span>
                     </li>
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
                     <li class="menu-header small text-uppercase">
                         <span class="menu-header-text">GUDANG JADI</span>
                     </li>
                     @can('bufferstok.index')
                         <li class="menu-item {{ request()->is(['bufferstok', 'bufferstok/*']) ? 'active' : '' }}">
                             <a href="{{ route('bufferstok.index') }}" class="menu-link">
                                 <div>Buffer & Max Stok</div>
                             </a>
                         </li>
                     @endcan
                     @can('angkutan.index')
                         <li class="menu-item {{ request()->is(['angkutan', 'angkutan/*']) ? 'active' : '' }}">
                             <a href="{{ route('angkutan.index') }}" class="menu-link">
                                 <div>Angkutan</div>
                             </a>
                         </li>
                     @endcan
                     @can('tujuanangkutan.index')
                         <li class="menu-item {{ request()->is(['tujuanangkutan', 'tujuanangkutan/*']) ? 'active' : '' }}">
                             <a href="{{ route('tujuanangkutan.index') }}" class="menu-link">
                                 <div>Tujuan Angkutan</div>
                             </a>
                         </li>
                     @endcan

                     <li class="menu-header small text-uppercase">
                         <span class="menu-header-text">PRODUKSI</span>
                     </li>
                     @can('barangproduksi.index')
                         <li class="menu-item {{ request()->is(['barangproduksi', 'barangproduksi/*']) ? 'active' : '' }}">
                             <a href="{{ route('barangproduksi.index') }}" class="menu-link">
                                 <div>Barang Produksi</div>
                             </a>
                         </li>
                     @endcan
                 </ul>
             @endif
         </li>
         <li
             class="menu-item {{ request()->is([
                 'bpbj',
                 'bpbj/*',
                 'fsthp',
                 'fsthp/*',
                 'samutasiproduksi',
                 'samutasiproduksi/*',
                 'barangmasukproduksi',
                 'barangmasukproduksi/*',
                 'barangkeluarproduksi',
                 'barangkeluarproduksi/*',
                 'sabarangproduksi',
                 'sabarangproduksi/*',
                 'permintaanproduksi',
                 'permintaanproduksi/*',
                 'laporanproduksi',
                 'laporanproduksi/*',
             ])
                 ? 'open'
                 : '' }}">
             @if (auth()->user()->hasAnyPermission([
                         'bpbj.index',
                         'fsthp.index',
                         'samutasiproduksi.index',
                         'barangmasukproduksi.index',
                         'barangkeluarproduksi.index',
                         'sabarangproduksi.index',
                         'permintaanproduksi.index',
                         'laporanproduksi.index',
                     ]))
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-box"></i>
                     <div>Produksi</div>
                 </a>
                 <ul class="menu-sub">
                     @can('permintaanproduksi.index')
                         <li
                             class="menu-item {{ request()->is(['permintaanproduksi', 'permintaanproduksi/*']) ? 'active' : '' }}">
                             <a href="{{ route('permintaanproduksi.index') }}" class="menu-link">
                                 <div>Permintaan Produksi</div>
                             </a>
                         </li>
                     @endcan
                     @if (auth()->user()->hasAnyPermission(['bpbj.index', 'fsthp.index', 'samutasiproduksi.index']))
                         @can('samutasiproduksi.index')
                             <li
                                 class="menu-item {{ request()->is(['samutasiproduksi', 'samutasiproduksi/*', 'bpbj', 'bpbj/*', 'fsthp', 'fsthp/*']) ? 'active' : '' }}">
                                 <a href="{{ route('samutasiproduksi.index') }}" class="menu-link">
                                     <div>Mutasi Produksi</div>
                                 </a>
                             </li>
                         @endcan
                     @endif
                     @if (auth()->user()->hasAnyPermission(['barangmasukproduksi.index', 'barangkeluarproduksi.index', 'sabarangproduksi.index']))
                         @can('sabarangproduksi.index')
                             <li
                                 class="menu-item {{ request()->is(['sabarangproduksi', 'sabarangproduksi/*', 'barangmasukproduksi', 'barangmasukproduksi/*', 'barangkeluarproduksi', 'barangkeluarproduksi/*']) ? 'active' : '' }}">
                                 <a href="{{ route('sabarangproduksi.index') }}" class="menu-link">
                                     <div>Mutasi Barang</div>
                                 </a>
                             </li>
                         @endcan
                     @endif
                     @if (auth()->user()->hasAnyPermission([
                                 'prd.mutasiproduksi',
                                 'prd.rekapmutasi',
                                 'prd.pemasukan',
                                 'prd.pengeluaran',
                                 'prd.rekappersediaan',
                             ]))
                         @can('sabarangproduksi.index')
                             <li
                                 class="menu-item {{ request()->is(['laporanproduksi', 'laporanproduksi/*']) ? 'active' : '' }}">
                                 <a href="{{ route('laporanproduksi.index') }}" class="menu-link">
                                     <div>Laporan</div>
                                 </a>
                             </li>
                         @endcan
                     @endif
                 </ul>
             @endif
         </li>
         <li
             class="menu-item {{ request()->is([
                 'sagudangjadi',
                 'sagudangjadi/*',
                 'suratjalan',
                 'suratjalan/*',
                 'fsthpgudang',
                 'fsthpgudang/*',
                 'repackgudangjadi',
                 'repackgudangjadi/*',
                 'rejectgudangjadi',
                 'rejectgudangjadi/*',
                 'lainnyagudangjadi',
                 'lainnyagudangjadi/*',
                 'suratjalanangkutan',
                 'suratjalanangkutan/*',
                 'laporangudangjadi',
                 'laporangudangjadi/*',
             ])
                 ? 'open'
                 : '' }}">
             @if (auth()->user()->hasAnyPermission(['suratjalan.index']))
                 <a href="javascript:void(0);" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
                     <div>Gudang Jadi</div>
                 </a>
                 <ul class="menu-sub">
                     @if (auth()->user()->hasAnyPermission($all_gudang_jadi))
                         <li
                             class="menu-item {{ request()->is([
                                 'sagudangjadi',
                                 'sagudangjadi/*',
                                 'suratjalan',
                                 'suratjalan/*',
                                 'fsthpgudang',
                                 'fsthpgudang/*',
                                 'repackgudangjadi',
                                 'repackgudangjadi/*',
                                 'rejectgudangjadi',
                                 'rejectgudangjadi/*',
                                 'lainnyagudangjadi',
                                 'lainnyagudangjadi/*',
                             ])
                                 ? 'active'
                                 : '' }}">
                             <a href="{{ route('sagudangjadi.index') }}" class="menu-link">
                                 <div>Mutasi Produk</div>
                             </a>
                         </li>
                     @endif
                     @can('suratjalanangkutan.index')
                         <li
                             class="menu-item {{ request()->is(['suratjalanangkutan', 'suratjalanangkutan/*']) ? 'active' : '' }}">
                             <a href="{{ route('suratjalanangkutan.index') }}" class="menu-link">
                                 <div>Angkutan</div>
                             </a>
                         </li>
                     @endcan
                     @if (auth()->user()->hasAnyPermission($laporan_gudang_jadi))
                         <li
                             class="menu-item {{ request()->is(['laporangudangjadi', 'laporangudangjadi/*']) ? 'active' : '' }}">
                             <a href="{{ route('laporangudangjadi.index') }}" class="menu-link">
                                 <div>Laporan</div>
                             </a>
                         </li>
                     @endif
                 </ul>
             @endif
         </li>
         <li
             class="menu-item {{ request()->is(['omancabang', 'omancabang/*', 'oman', 'oman/*', 'permintaankiriman', 'permintaankiriman/*'])
                 ? 'open'
                 : '' }}">
             @if (auth()->user()->hasAnyPermission(['omancabang.index', 'oman.index', 'permintaankiriman.index']))
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
                     @can('omancabang.index')
                         <li
                             class="menu-item {{ request()->is(['omancabang', 'omancabang/*', 'oman', 'oman/*']) ? 'active' : '' }}">
                             <a href="{{ route('omancabang.index') }}" class="menu-link">
                                 <div>OMAN</div>
                             </a>
                         </li>
                     @endcan

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
