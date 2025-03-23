<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{ route('monitoringprogram.saldosimpanan') }}"
            class="nav-link {{ request()->is(['monitoringprogram/saldosimpanan']) ? 'active' : '' }}">
            <i class="tf-icons ti ti-file-description ti-md me-1"></i> Simpanan
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('monitoringprogram.pencairansimpanan') }}"
            class="nav-link {{ request()->is(['monitoringprogram/pencairansimpanan']) ? 'active' : '' }}">
            <i class="tf-icons ti ti-file-description ti-md me-1"></i> Pencairan Simpanan
        </a>
    </li>
</ul>
