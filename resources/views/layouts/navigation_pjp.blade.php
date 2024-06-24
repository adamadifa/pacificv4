@if (auth()->user()->hasAnyPermission(['pjp.index', 'bayarpjp.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('pjp.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pjp.index') }}" class="nav-link {{ request()->is(['pjp']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> PJP
                </a>
            </li>
        @endcan

        @can('pembayaranpjp.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pembayaranpjp.index') }}" class="nav-link {{ request()->is(['pembayaranpjp']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pembayaran
                </a>
            </li>
        @endcan
    </ul>
@endif
