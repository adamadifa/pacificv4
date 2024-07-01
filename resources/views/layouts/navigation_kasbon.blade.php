@if (auth()->user()->hasAnyPermission(['kasbon.index', 'pembayarankasbon.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('kasbon.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('kasbon.index') }}" class="nav-link {{ request()->is(['kasbon']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Kasbon
                </a>
            </li>
        @endcan

        @can('pembayarankasbon.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pembayarankasbon.index') }}" class="nav-link {{ request()->is(['pembayarankasbon']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pembayaran
                </a>
            </li>
        @endcan
    </ul>
@endif
