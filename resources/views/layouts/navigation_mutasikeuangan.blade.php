@if (auth()->user()->hasAnyPermission(['mutasikeuangan.index', 'samutasikeuangan.index']))
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

            @can('sakasbesarkeuangan.index')
                <li class="nav-item">
                    <a href="{{ route('sakasbesarkeuangan.index') }}"
                        class="nav-link {{ request()->is(['sakasbesarkeuangan']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-wallet me-1"></i> Saldo Kas Besar
                    </a>
                </li>
            @endcan
            @hasanyrole('manager keuangan')
                <li class="nav-item">
                    <a href="{{ route('sakasbesarkeuanganpusat.index') }}"
                        class="nav-link {{ request()->is(['sakasbesarkeuanganpusat']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-building-bank me-1"></i> Saldo Kas Besar (Keuangan)
                    </a>
                </li>
            @endhasanyrole
            @can('mutasikeuangan.index')
                <li class="nav-item">
                    <a href="{{ route('mutasikeuangan.index') }}"
                        class="nav-link {{ request()->is(['mutasikeuangan']) ? 'active' : '' }}">
                        <i class="tf-icons ti ti-arrows-transfer-down me-1"></i> Mutasi Keuangan
                    </a>
                </li>
            @endcan
        </ul>
    </div>
@endif
