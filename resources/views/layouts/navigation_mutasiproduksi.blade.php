@if (auth()->user()->hasAnyPermission(['bpbj.index', 'fsthp.index', 'samutasiproduksi.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('samutasiproduksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('samutasiproduksi.index') }}"
                    class="nav-link {{ request()->is(['samutasiproduksi', 'samutasiproduksi/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan
        @can('bpbj.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('bpbj.index') }}" class="nav-link {{ request()->is(['bpbj', 'bpbj/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti  ti-package-import ti-md me-1"></i> BPBJ
                </a>
            </li>
        @endcan
        @can('fsthp.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('fsthp.index') }}"
                    class="nav-link {{ request()->is(['fsthp', 'fsthp/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti  ti-package-export ti-md me-1"></i> FSTHP
                </a>
            </li>
        @endcan

    </ul>
@endif
