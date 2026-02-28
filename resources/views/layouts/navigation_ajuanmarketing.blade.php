@if (auth()->user()->hasAnyPermission(['ajuanlimit.index', 'ajuanfaktur.index']))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
        @can('ajuanlimit.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuanlimit.index') }}" class="nav-link {{ request()->is(['ajuanlimit', 'ajuanlimit/*']) ? 'active' : '' }}">
                    <i class="ti ti-file-description me-1"></i> Ajuan Limit Kredit
                    @if (!empty($notifikasi_limitkredit))
                        <span class="badge bg-danger rounded-pill ms-2 text-white">{{ $notifikasi_limitkredit }}</span>
                    @endif
                </a>
            </li>
        @endcan
        @can('ajuanfaktur.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuanfaktur.index') }}" class="nav-link {{ request()->is(['ajuanfaktur', 'ajuanfaktur/*']) ? 'active' : '' }}">
                    <i class="ti ti-file-description me-1"></i> Ajuan Faktur Kredit
                    @if (!empty($notifikasi_ajuanfaktur))
                        <span class="badge bg-danger rounded-pill ms-2 text-white">{{ $notifikasi_ajuanfaktur }}</span>
                    @endif
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
