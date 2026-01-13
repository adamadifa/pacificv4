@if (auth()->user()->hasAnyPermission(['programikatan2026.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('programikatan2026.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('programikatan2026.index') }}"
                    class="nav-link {{ request()->is(['programikatan2026', 'programikatan2026/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i>Ajuan Program Marketing 2026
                </a>
            </li>
        @endcan
        @can('programikatan2026.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pencairanprogramikatan2026.index') }}"
                    class="nav-link {{ request()->is(['pencairanprogramikatan2026', 'pencairanprogramikatan2026/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pencairan Program Marketing 2026
                </a>
            </li>
        @endcan
    </ul>
@endif
