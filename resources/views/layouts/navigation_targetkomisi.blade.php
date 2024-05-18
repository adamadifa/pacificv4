@if (auth()->user()->hasAnyPermission(['targetkomisi.index', 'ratiodriverhelper.index']))
  <ul class="nav nav-tabs" role="tablist">
    @can('targetkomisi.index')
      <li class="nav-item" role="presentation">
        <a href="{{ route('targetkomisi.index') }}" class="nav-link {{ request()->is('targetkomisi') ? 'active' : '' }}">
          <i class="tf-icons ti ti-target-arrow ti-md me-1"></i> Target Komisi
        </a>
      </li>
    @endcan
    @can('ratiodriverhelper.index')
      <li class="nav-item" role="presentation">
        <a href="{{ route('ratiodriverhelper.index') }}" class="nav-link {{ request()->is('ratiodriverhelper') ? 'active' : '' }}">
          <i class="tf-icons ti ti-files ti-md me-1"></i> Ratio Driver Helper
        </a>
      </li>
    @endcan
  </ul>
@endcan
