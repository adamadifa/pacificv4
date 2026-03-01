@if (auth()->user()->hasAnyPermission(['hpp.index', 'hargaawalhpp.index']))
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
            @can('hpp.index')
                <li class="nav-item">
                    <a href="{{ route('hpp.index') }}" class="nav-link {{ request()->is(['hpp']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-file-description me-1"></i> Harga HPP
                    </a>
                </li>
            @endcan

            @can('hargaawalhpp.index')
                <li class="nav-item">
                    <a href="{{ route('hargaawalhpp.index') }}"
                        class="nav-link {{ request()->is(['hargaawalhpp']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-file-analytics me-1"></i> Harga Awal
                    </a>
                </li>
            @endcan
        </ul>
    </div>
@endif
