@if (auth()->user()->hasAnyPermission(['kaskecil.index', 'klaim.index']))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
        @can('sakaskecil.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sakaskecil.index') }}"
                    class="nav-link {{ request()->is(['sakaskecil', 'sakaskecil/*']) ? 'active' : '' }}">
                    <i class="ti ti-database-import me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan
        @can('kaskecil.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('kaskecil.index') }}"
                    class="nav-link {{ request()->is(['kaskecil', 'kaskecil/*']) ? 'active' : '' }}">
                    <i class="ti ti-wallet me-1"></i> Kas Kecil
                </a>
            </li>
        @endcan
        @can('klaimkaskecil.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('klaimkaskecil.index') }}"
                    class="nav-link {{ request()->is(['klaimkaskecil', 'klaimkaskecil/*']) ? 'active' : '' }}">
                    <i class="ti ti-clipboard-list me-1"></i> Klaim
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
