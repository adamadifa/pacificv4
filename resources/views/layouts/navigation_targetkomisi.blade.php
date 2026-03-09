@if (auth()->user()->hasAnyPermission(['targetkomisi.index', 'ratiodriverhelper.index']))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
        @can('targetkomisi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('targetkomisi.index') }}"
                    class="nav-link {{ request()->is(['targetkomisi', 'targetkomisi/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-target-arrow ti-md me-1"></i> Target Komisi
                    @if (!empty($notifikasi_target))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_target }}</span>
                    @endif
                </a>
            </li>
        @endcan
        @can('ratiodriverhelper.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('settingkomisidriverhelper.index') }}"
                    class="nav-link {{ request()->is(['settingkomisidriverhelper', 'settingkomisidriverhelper/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-settings ti-md me-1"></i> Setting Komisi Driver Helper
                </a>
            </li>
        @endcan
    </ul>

    <style>
        .nav-pills-custom .nav-link {
            color: #5d596c;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem 0.375rem 0 0;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-pills-custom .nav-link:hover {
            color: #002e65;
            background-color: rgba(0, 46, 101, 0.05);
        }

        .nav-pills-custom .nav-link.active {
            color: #002e65 !important;
            background-color: #fff !important;
            border-bottom: 3px solid #002e65;
            box-shadow: none !important;
        }

        .nav-pills-custom .nav-link i {
            font-size: 1.2rem;
        }
    </style>
@endif
