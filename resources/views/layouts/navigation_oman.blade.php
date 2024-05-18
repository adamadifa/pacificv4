@if (auth()->user()->hasAnyPermission(['omancabang.index', 'oman.index']))
  <ul class="nav nav-tabs" role="tablist">
    @can('omancabang.index')
      <li class="nav-item" role="presentation">
        <a href="{{ route('omancabang.index') }}" class="nav-link {{ request()->is('omancabang') ? 'active' : '' }}">
          <i class="tf-icons ti ti-archive ti-md me-1"></i> Oman Cabang
        </a>
      </li>
    @endcan

    @can('oman.index')
      <li class="nav-item" role="presentation">
        <a href="{{ route('oman.index') }}" class="nav-link {{ request()->is('oman') ? 'active' : '' }}">
          <i class="tf-icons ti ti-archive ti-md me-1"></i> Oman Marketing
        </a>
      </li>
    @endcan


  </ul>
@endif
