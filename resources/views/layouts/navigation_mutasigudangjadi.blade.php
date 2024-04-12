@if (auth()->user()->hasAnyPermission(['suratjalan.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('fsthpgudang.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('fsthpgudang.index') }}"
                    class="nav-link {{ request()->is(['fsthpgudang', 'fsthpgudang/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> FSTHP
                </a>
            </li>
        @endcan
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
