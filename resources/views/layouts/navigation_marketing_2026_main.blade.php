<ul class="nav nav-tabs" role="tablist">
    @can('programikatan2026.index')
        <li class="nav-item" role="presentation">
            <a href="{{ route('programikatan2026.index') }}"
                class="nav-link {{ request()->is(['programikatan2026', 'programikatan2026/*']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-file-description ti-md me-1"></i>Program Marketing 2026
                @php
                    $total_ikatan_2026 = $notifikasi_ajuanprogramikatan + $notifikasi_pencairanprogramikatan;
                @endphp
                @if ($total_ikatan_2026 > 0)
                    <span class="badge rounded-pill bg-danger ms-1">{{ $total_ikatan_2026 }}</span>
                @endif
            </a>
        </li>
    @endcan
    @can('ajuankumulatif.index')
        <li class="nav-item" role="presentation">
            <a href="{{ route('ajuankumulatif.index') }}"
                class="nav-link {{ request()->is(['ajuankumulatif', 'ajuankumulatif/*', 'pencairanprogram', 'pencairanprogram/*']) ? 'active' : '' }}">
                <i class="tf-icons ti ti-file-description ti-md me-1"></i>Program Kumulatif
                @php
                    $total_kumulatif = $notifikasi_ajuanprogramkumulatif + $notifikasi_pencairanprogramkumulatif;
                @endphp
                @if ($total_kumulatif > 0)
                    <span class="badge rounded-pill bg-danger ms-1">{{ $total_kumulatif }}</span>
                @endif
            </a>
        </li>
    @endcan
</ul>
