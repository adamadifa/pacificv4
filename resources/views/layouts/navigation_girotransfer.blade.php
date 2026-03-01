@if (auth()->user()->hasAnyPermission(['pembayarantransfer.index', 'pembayarangiro.index']))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
        @can('pembayarantransfer.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pembayarantransfer.index') }}"
                    class="nav-link {{ request()->is(['pembayarantransfer', 'pembayarantransfer/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-arrows-transfer-down me-1"></i> Transfer
                </a>
            </li>
        @endcan
        @can('pembayarangiro.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('pembayarangiro.index') }}"
                    class="nav-link {{ request()->is(['pembayarangiro', 'pembayarangiro/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description me-1"></i> Giro
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
