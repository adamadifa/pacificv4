@php
    $gudang_cabang_mutasi_permission = [
        'sagudangcabang.index',
        'suratjalancabang.index',
        'transitin.index',
        'dpb.index',
        'reject.index',
        'repackcbg.index',
        'kirimpusat.index',
        'penygudangcbg.index'
    ];
@endphp
@if (auth()->user()->hasAnyPermission($gudang_cabang_mutasi_permission))
    <ul class="nav nav-pills nav-pills-custom mb-0" role="tablist">
        @can('sagudangcabang.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('sagudangcabang.index') }}"
                    class="nav-link {{ request()->is(['sagudangcabang', 'sagudangcabang/*']) ? 'active' : '' }}">
                    <i class="ti ti-package me-1"></i> Saldo Awal
                </a>
            </li>
        @endcan
        @can('suratjalancabang.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('suratjalancabang.index') }}"
                    class="nav-link {{ request()->is(['suratjalancabang', 'suratjalancabang/*']) ? 'active' : '' }}">
                    <i class="ti ti-truck me-1"></i> Surat Jalan
                </a>
            </li>
        @endcan
        @can('transitin.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('transitin.index') }}"
                    class="nav-link {{ request()->is(['transitin', 'transitin/*']) ? 'active' : '' }}">
                    <i class="ti ti-transfer-in me-1"></i> Transit IN
                </a>
            </li>
        @endcan
        @can('dpb.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('dpb.index') }}"
                    class="nav-link {{ request()->is(['dpb', 'dpb/*']) ? 'active' : '' }}">
                    <i class="ti ti-list-details me-1"></i> DPB
                </a>
            </li>
        @endcan
        @can('reject.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('reject.index') }}"
                    class="nav-link {{ request()->is(['reject', 'reject/*']) ? 'active' : '' }}">
                    <i class="ti ti-trash-x me-1"></i> Reject
                </a>
            </li>
        @endcan
        @can('repackcbg.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('repackcbg.index') }}"
                    class="nav-link {{ request()->is(['repackcbg', 'repackcbg/*']) ? 'active' : '' }}">
                    <i class="ti ti-recycle me-1"></i> Repack
                </a>
            </li>
        @endcan
        @can('kirimpusat.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('kirimpusat.index') }}"
                    class="nav-link {{ request()->is(['kirimpusat', 'kirimpusat/*']) ? 'active' : '' }}">
                    <i class="ti ti-send me-1"></i> Kirim Pusat
                </a>
            </li>
        @endcan
        @can('penygudangcbg.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('penygudangcbg.index') }}"
                    class="nav-link {{ request()->is(['penygudangcbg', 'penygudangcbg/*']) ? 'active' : '' }}">
                    <i class="ti ti-adjustments me-1"></i> Penyesuaian
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
