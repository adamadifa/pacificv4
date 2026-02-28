@if (auth()->user()->hasAnyPermission(['bpbj.index', 'fsthp.index', 'samutasiproduksi.index']))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
        @can('samutasiproduksi.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('samutasiproduksi.index') }}"
                    class="nav-link {{ request()->is(['samutasiproduksi', 'samutasiproduksi/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan
        @can('bpbj.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('bpbj.index') }}" class="nav-link {{ request()->is(['bpbj', 'bpbj/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-package-import me-1"></i> BPBJ
                </a>
            </li>
        @endcan
        @can('fsthp.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('fsthp.index') }}"
                    class="nav-link {{ request()->is(['fsthp', 'fsthp/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-package-export me-1"></i> FSTHP
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
