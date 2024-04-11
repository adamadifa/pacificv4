@if (auth()->user()->hasAnyPermission(['suratjalan.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('suratjalan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('suratjalan.index') }}"
                    class="nav-link {{ request()->is(['suratjalan', 'suratjalan/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-truck ti-md me-1"></i> Surat Jalan
                </a>
            </li>
        @endcan

    </ul>
@endif
