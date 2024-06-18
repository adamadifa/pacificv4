@if (auth()->user()->hasAnyPermission(['ledger.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('ledger.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ledger.index') }}" class="nav-link {{ request()->is(['ledger']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ledger
                </a>
            </li>
        @endcan
    </ul>
@endif
