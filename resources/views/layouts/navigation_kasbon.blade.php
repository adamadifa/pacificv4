@if (auth()->user()->hasAnyPermission(['kasbon.index', 'pembayarankasbon.index']))
    <style>
        .nav-pills-custom .nav-link {
            padding: 0.85rem 1.25rem;
            position: relative;
            transition: all 0.2s ease;
            border-radius: 0;
            font-weight: 500;
            color: #5d596c;
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

            @can('kasbon.index')
                <li class="nav-item" role="presentation">
                    <a href="{{ route('kasbon.index') }}"
                        class="nav-link {{ request()->is(['kasbon']) ? 'active' : '' }} d-flex align-items-center justify-content-center">
                        <i class="ti ti-file-description me-2"></i> Kasbon
                    </a>
                </li>
            @endcan

            @can('pembayarankasbon.index')
                <li class="nav-item" role="presentation">
                    <a href="{{ route('pembayarankasbon.index') }}"
                        class="nav-link {{ request()->is(['pembayarankasbon']) ? 'active' : '' }} d-flex align-items-center justify-content-center">
                        <i class="ti ti-report-money me-2"></i> Pembayaran
                    </a>
                </li>
            @endcan
        </ul>
    </div>
@endif
