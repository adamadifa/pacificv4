@if (auth()->user()->hasAnyPermission($produksi_permission))
    <li class="menu-item {{ request()->is($produksi_request) ? 'open' : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-box"></i>
            <div>Produksi</div>
        </a>
        <ul class="menu-sub">
            @can('permintaanproduksi.index')
                <li class="menu-item {{ request()->is(['permintaanproduksi', 'permintaanproduksi/*']) ? 'active' : '' }}">
                    <a href="{{ route('permintaanproduksi.index') }}" class="menu-link">
                        <div>Permintaan Produksi</div>
                    </a>
                </li>
            @endcan
            @if (auth()->user()->hasAnyPermission($produksi_mutasi_produk_permission))
                @php
                    $mutasiProduksiRoute = '#';
                    if (auth()->user()->can('samutasiproduksi.index')) {
                        $mutasiProduksiRoute = route('samutasiproduksi.index');
                    } elseif (auth()->user()->can('bpbj.index')) {
                        $mutasiProduksiRoute = route('bpbj.index');
                    } elseif (auth()->user()->can('fsthp.index')) {
                        $mutasiProduksiRoute = route('fsthp.index');
                    }
                @endphp
                <li class="menu-item {{ request()->is($produksi_mutasi_produk_request) ? 'active' : '' }}">
                    <a href="{{ $mutasiProduksiRoute }}" class="menu-link">
                        <div>Mutasi Produksi</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission($produksi_mutasi_barang_permission))
                @php
                    $mutasiBarangRoute = '#';
                    if (auth()->user()->can('sabarangproduksi.index')) {
                        $mutasiBarangRoute = route('sabarangproduksi.index');
                    } elseif (auth()->user()->can('barangmasukproduksi.index')) {
                        $mutasiBarangRoute = route('barangmasukproduksi.index');
                    } elseif (auth()->user()->can('barangkeluarproduksi.index')) {
                        $mutasiBarangRoute = route('barangkeluarproduksi.index');
                    }
                @endphp
                <li class="menu-item {{ request()->is($produksi_mutasi_barang_request) ? 'active' : '' }}">
                    <a href="{{ $mutasiBarangRoute }}" class="menu-link">
                        <div>Mutasi Barang</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission($produksi_laporan_permission))
                <li class="menu-item {{ request()->is(['laporanproduksi', 'laporanproduksi/*']) ? 'active' : '' }}">
                    <a href="{{ route('laporanproduksi.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
