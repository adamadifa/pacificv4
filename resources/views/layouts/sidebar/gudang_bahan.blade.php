<li class="menu-item {{ request()->is($gudang_bahan_request) ? 'open' : '' }}">
    @if (auth()->user()->hasAnyPermission($gudang_bahan_permission))
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
            <div>Gudang Bahan</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission($gudang_bahan_mutasi_permission))
                <li class="menu-item {{ request()->is($gudang_bahan_mutasi_request) ? 'active' : '' }}">
                    <a href="{{ route('barangmasukgudangbahan.index') }}" class="menu-link">
                        <div>Mutasi Barang</div>
                    </a>
                </li>
            @endif

        </ul>
    @endif
</li>
