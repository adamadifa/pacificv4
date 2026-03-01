@if (auth()->user()->hasAnyPermission(['mutasibank.index', 'samutasibank.index']))
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

            @can('samutasibank.index')
                <li class="nav-item">
                    <a href="{{ route('samutasibank.index') }}" class="nav-link {{ request()->is(['samutasibank']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-database-import ti-sm me-1"></i> Saldo Awal
                    </a>
                </li>
            @endcan

            @can('mutasibank.index')
                <li class="nav-item">
                    <a href="{{ route('mutasibank.index') }}" class="nav-link {{ request()->is(['mutasibank']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-building-bank ti-sm me-1"></i> Mutasi Bank
                    </a>
                </li>
            @endcan
        </ul>
    </div>
@endif
