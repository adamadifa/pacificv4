<li
  class="menu-item {{ request()->is(['omancabang', 'omancabang/*', 'oman', 'oman/*', 'permintaankiriman', 'permintaankiriman/*']) ? 'open' : '' }}">
  @if (auth()->user()->hasAnyPermission(['omancabang.index', 'oman.index', 'permintaankiriman.index', 'targetkomisi.index']))
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons ti ti-building-broadcast-tower"></i>
      <div>Marketing</div>
    </a>
    <ul class="menu-sub">
      @can('permintaankiriman.index')
        <li
          class="menu-item {{ request()->is(['permintaankiriman', 'permintaankiriman/*']) ? 'active' : '' }}">
          <a href="{{ route('permintaankiriman.index') }}" class="menu-link">
            <div>Permintaan Kiriman</div>
          </a>
        </li>
      @endcan
      @can('omancabang.index')
        <li
          class="menu-item {{ request()->is(['omancabang', 'omancabang/*', 'oman', 'oman/*']) ? 'active' : '' }}">
          <a href="{{ route('omancabang.index') }}" class="menu-link">
            <div>Order Management</div>
          </a>
        </li>
      @endcan
      @can(['targetkomisi.index'])
        <li
          class="menu-item {{ request()->is(['omancabang', 'omancabang/*', 'oman', 'oman/*']) ? 'active' : '' }}">
          <a href="{{ route('omancabang.index') }}" class="menu-link">
            <div>Komisi</div>
          </a>
        </li>
      @endcan
    </ul>
  @endif
</li>
