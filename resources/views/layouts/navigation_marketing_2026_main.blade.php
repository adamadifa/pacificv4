<ul class="nav nav-tabs" role="tablist">
    @can('programikatan2026.index')
        <li class="nav-item" role="presentation">
            <a href="{{ route('programikatan2026.index') }}"
                class="nav-link {{ request()->is(['programikatan2026', 'programikatan2026/*']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-file-description ti-md me-1"></i>Program Marketing 2026
            </a>
        </li>
    @endcan
    @can('ajuankumulatif.index')
        <li class="nav-item" role="presentation">
            <a href="{{ route('ajuankumulatif.index') }}"
                class="nav-link {{ request()->is(['ajuankumulatif', 'ajuankumulatif/*', 'pencairanprogram', 'pencairanprogram/*']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-file-description ti-md me-1"></i>Program Kumulatif
            </a>
        </li>
    @endcan
</ul>
