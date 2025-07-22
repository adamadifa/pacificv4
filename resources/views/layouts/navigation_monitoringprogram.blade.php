@if (auth()->user()->hasAnyPermission([
            'monitoringprogram.index',
            'ajuanprogramikatan.index',
            'pencairanprogramikatan.index',
            'ajuankumulatif.index',
            'pencairanprogram.index',
        ]))
    <ul class="nav nav-tabs" role="tablist">
        @can('ajuanprogramikatan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuanprogramikatan.index') }}"
                    class="nav-link {{ request()->is(['ajuanprogramikatan', 'ajuanprogramikatan/*', 'pencairanprogramikatan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i>Program Ikatan
                </a>
            </li>
        @endcan
        @can('ajuanprogramenambulan.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuanprogramenambulan.index') }}"
                    class="nav-link {{ request()->is(['ajuanprogramenambulan', 'ajuanprogramenambulan/*', 'pencairanprogramenambulan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i>Program Ikatan Enam Bulan
                </a>
            </li>
        @endcan
        @if (auth()->user()->hasAnyRole(['staff keuangan', 'manager keuangan']))
            @can('pencairanprogramikt.index')
                <li class="nav-item" role="presentation">
                    <a href="{{ route('pencairanprogramikatan.index') }}"
                        class="nav-link {{ request()->is(['pencairanprogramikatan', 'pencairanprogramikatan/*']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pencairan Program Ikatan
                    </a>
                </li>
            @endcan
        @endif

        @if (auth()->user()->hasAnyRole(['staff keuangan', 'manager keuangan']))
            @can('pencairanprogramenambulan.index')
                <li class="nav-item" role="presentation">
                    <a href="{{ route('pencairanprogramenambulan.index') }}"
                        class="nav-link {{ request()->is(['pencairanprogramenambulan', 'pencairanprogramenambulan/*']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pencairan Program Ikatan Enam Bulan
                    </a>
                </li>
            @endcan
        @endif

        
        
        @can('ajuankumulatif.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuankumulatif.index') }}"
                    class="nav-link {{ request()->is(['ajuankumulatif', 'ajuankumulatif/*', 'pencairanprogram']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Program Kumulatif
                </a>
            </li>
        @endcan
        @if (auth()->user()->hasAnyRole(['staff keuangan', 'manager keuangan']))
            @can('pencairanprogram.index')
                <li class="nav-item" role="presentation">
                    <a href="{{ route('pencairanprogram.index') }}"
                        class="nav-link {{ request()->is(['pencairanprogram', 'pencairanprogram/*']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pencairan Program Kumulatif
                    </a>
                </li>
            @endcan
        @endif
        <li class="nav-item" role="presentation">
            <a href="{{ route('monitoringprogram.index') }}" class="nav-link {{ request()->is(['monitoringprogram']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-file-description ti-md me-1"></i> Monitoring Program
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('monitoringprogram.saldosimpanan') }}"
                class="nav-link {{ request()->is(['monitoringprogram/saldosimpanan']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-file-description ti-md me-1"></i> Simpanan
            </a>
        </li>
        @if (auth()->user()->hasAnyRole(['staff keuangan', 'manager keuangan']))
            <li class="nav-item" role="presentation">
                <a href="{{ route('monitoringprogram.pencairansimpanan') }}"
                    class="nav-link {{ request()->is(['monitoringprogram/pencairansimpanan']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pencairan Simpanan
                </a>
            </li>
        @endif
        <li class="nav-item" role="presentation">
            <a href="{{ route('monitoringprogram.saldovoucher') }}"
                class="nav-link {{ request()->is(['monitoringprogram/saldovoucher']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-file-description ti-md me-1"></i> Voucher
            </a>
        </li>
    </ul>
@endif
