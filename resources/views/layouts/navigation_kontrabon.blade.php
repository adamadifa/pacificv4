@if (auth()->user()->hasAnyPermission(['kontrabonpmb.index', 'kontrabonangkutan.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('kontrabonpmb.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('kontrabonkeuangan.pembelian') }}" class="nav-link {{ request()->is(['kontrabonkeuangan/pembelian']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Kontrabon Pembelian
                </a>
            </li>
        @endcan


        @can('kontrabonangkutan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('kontrabonkeuangan.angkutan') }}" class="nav-link {{ request()->is(['kontrabonkeuangan/angkutan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Kontrabon Angkutan
                </a>
            </li>
        @endcan

    </ul>
@endif
