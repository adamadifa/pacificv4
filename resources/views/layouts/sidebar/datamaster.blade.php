 <li class="menu-item {{ request()->is($datamaster_request) ? 'open' : '' }}">
     @if (auth()->user()->hasAnyPermission($datamaster_permission))
         <a href="javascript:void(0);" class="menu-link menu-toggle">
             <i class="menu-icon tf-icons ti ti-database"></i>
             <div>Data Master</div>
         </a>
         <ul class="menu-sub">
             {{-- <li class="menu-header small text-uppercase">
                 <span class="menu-header-text">MARKETING</span>
             </li> --}}
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
             @can('driverhelper.index')
                 <li class="menu-item {{ request()->is(['driverhelper']) ? 'active' : '' }}">
                     <a href="{{ route('driverhelper.index') }}" class="menu-link">
                         <div>Driver Helper</div>
                     </a>
                 </li>
             @endcan
             {{-- <li class="menu-header small text-uppercase">
                 <span class="menu-header-text">GENERAL AFFAIR</span>
             </li> --}}
             @can('kendaraan.index')
                 <li class="menu-item {{ request()->is(['kendaraan', 'kendaraan/*']) ? 'active' : '' }}">
                     <a href="{{ route('kendaraan.index') }}" class="menu-link">
                         <div>Kendaraan</div>
                     </a>
                 </li>
             @endcan
             {{-- <li class="menu-header small text-uppercase">
                 <span class="menu-header-text">PEMBELIAN</span>
             </li> --}}
             @can('barangpembelian.index')
                 <li class="menu-item {{ request()->is(['barangpembelian', 'barangpembelian/*']) ? 'active' : '' }}">
                     <a href="{{ route('barangpembelian.index') }}" class="menu-link">
                         <div>Barang</div>
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
             {{-- <li class="menu-header small text-uppercase">
                 <span class="menu-header-text">HRD</span>
             </li> --}}
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
                 <li class="menu-item {{ request()->is(['bpjstenagakerja', 'bpjstenagakerja/*']) ? 'active' : '' }}">
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

             {{-- <li class="menu-header small text-uppercase">
                 <span class="menu-header-text">PRODUKSI</span>
             </li> --}}
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
