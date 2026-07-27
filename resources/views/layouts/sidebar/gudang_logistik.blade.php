@if (auth()->user()->hasAnyPermission($gudang_logistik_permission))
    <li class="menu-item {{ request()->is($gudang_logistik_request) ? 'open' : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
            <div>Gudang Logistik</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission($gudang_logistik_mutasi_permission))
                @php
                    $logistikMutasiRoute = '#';
                    if (auth()->user()->can('barangmasukgl.index')) {
                        $logistikMutasiRoute = route('barangmasukgudanglogistik.index');
                    } elseif (auth()->user()->can('barangkeluargl.index')) {
                        $logistikMutasiRoute = route('barangkeluargudanglogistik.index');
                    } elseif (auth()->user()->can('sagudanglogistik.index')) {
                        $logistikMutasiRoute = route('sagudanglogistik.index');
                    } elseif (auth()->user()->can('opgudanglogistik.index')) {
                        $logistikMutasiRoute = route('opgudanglogistik.index');
                    }
                @endphp
                <li class="menu-item {{ request()->is($gudang_logistik_mutasi_request) ? 'active' : '' }}">
                    <a href="{{ $logistikMutasiRoute }}" class="menu-link">
                        <div>Mutasi Barang</div>
                    </a>
                </li>
            @endif
            @can('bpb.index')
                <li class="menu-item {{ request()->is(['bpb', 'bpb/*']) ? 'active' : '' }}">
                    <a href="{{ route('bpb.index') }}" class="menu-link">
                        <div>BPB</div>
                        @if (!empty($notifikasi_bpb))
                            <div class="badge bg-danger rounded-pill ms-auto">{{ $notifikasi_bpb }}</div>
                        @endif
                    </a>
                </li>
            @endcan
            @if (auth()->user()->hasAnyPermission($gudang_logistik_laporan_permission))
                <li class="menu-item {{ request()->is(['laporangudanglogistik', 'laporangudanglogistik/*']) ? 'active' : '' }}">
                    <a href="{{ route('laporangudanglogistik.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
