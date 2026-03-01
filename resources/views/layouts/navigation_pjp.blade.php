@if (auth()->user()->hasAnyPermission(['pjp.index', 'bayarpjp.index']))
    <style>
        .nav-pills-custom .nav-link {
            color: #5d596c;
            border-radius: 0;
            padding: 0.85rem 1.25rem;
            font-weight: 500;
            position: relative;
            transition: all 0.2s ease;
        }

        .nav-pills-custom .nav-link.active {
            color: #002e65 !important;
            background-color: transparent !important;
        }

        .nav-pills-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #002e65;
        }

        .nav-pills-custom .nav-link:hover {
            color: #002e65;
        }
    </style>
    <div class="nav-align-top mb-1">
        <ul class="nav nav-pills nav-pills-custom border-bottom" role="tablist">

            @can('pjp.index')
                <li class="nav-item">
                    <a href="{{ route('pjp.index') }}" class="nav-link {{ request()->is(['pjp']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-file-description ti-sm me-1"></i> PJP
                    </a>
                </li>
            @endcan

            @can('pembayaranpjp.index')
                <li class="nav-item">
                    <a href="{{ route('pembayaranpjp.index') }}" class="nav-link {{ request()->is(['pembayaranpjp']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-report-money ti-sm me-1"></i> Pembayaran
                    </a>
                </li>
            @endcan
        </ul>
    </div>
@endif
