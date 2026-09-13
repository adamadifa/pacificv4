@if (auth()->user()->hasAnyPermission(['ajuankumulatif.index', 'pencairanprogram.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('ajuankumulatif.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuankumulatif.index') }}" class="nav-link {{ request()->is(['ajuankumulatif', 'ajuankumulatif/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ajuan Program Kumulatif
                    @if ($notifikasi_ajuanprogramkumulatif > 0)
                        <span class="badge rounded-pill bg-danger ms-1">{{ $notifikasi_ajuanprogramkumulatif }}</span>
                    @endif
                </a>
            </li>
        @endcan
        @can('pencairanprogram.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pencairanprogram.index') }}"
                    class="nav-link {{ request()->is(['pencairanprogram', 'pencairanprogram/*']) && !request()->is(['pencairanprogram/saldosimpanan', 'pencairanprogram/pencairansimpanan*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pencairan Program Kumulatif
                    @if ($notifikasi_pencairanprogramkumulatif > 0)
                        <span class="badge rounded-pill bg-danger ms-1">{{ $notifikasi_pencairanprogramkumulatif }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('pencairanprogram.saldosimpanan') }}"
                    class="nav-link {{ request()->is(['pencairanprogram/saldosimpanan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-wallet ti-md me-1"></i> Simpanan
                </a>
            </li>
            @if (auth()->user()->hasAnyRole(['staff keuangan', 'manager keuangan', 'super admin']))
                <li class="nav-item" role="presentation">
                    <a href="{{ route('pencairanprogram.pencairansimpanan') }}"
                        class="nav-link {{ request()->is(['pencairanprogram/pencairansimpanan', 'pencairanprogram/pencairansimpanan/*']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-cash ti-md me-1"></i> Pencairan Simpanan
                    </a>
                </li>
            @endif
        @endcan
    </ul>
@endif
