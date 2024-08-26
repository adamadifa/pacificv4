@if (auth()->user()->hasAnyPermission(['kaskecil.index', 'klaim.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('kaskecil.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('kaskecil.index') }}" class="nav-link {{ request()->is(['kaskecil']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Kas Kecil
                </a>
            </li>
        @endcan


    </ul>
@endif
