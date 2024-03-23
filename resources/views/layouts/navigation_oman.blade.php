@can(['omancabang.index', 'oman.index'])
    <ul class="nav nav-tabs nav-fill" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="{{ route('omancabang.index') }}" class="nav-link {{ request()->is('omancabang') ? 'active' : '' }}">
                <i class="tf-icons ti ti-file-description ti-lg me-1"></i> Oman Cabang
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{ route('oman.index') }}" class="nav-link {{ request()->is('oman') ? 'active' : '' }}">
                <i class="tf-icons ti ti-file-description ti-lg me-1"></i> Oman Marketing
            </a>
        </li>

    </ul>
@endcan
