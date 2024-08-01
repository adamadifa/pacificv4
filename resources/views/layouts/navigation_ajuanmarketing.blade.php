@if (auth()->user()->hasAnyPermission(['ajuanlimit.index', 'ajuanfaktur.index']))
    <ul class="nav nav-tabs" role="tablist">
        @can('ajuanlimit.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuanlimit.index') }}" class="nav-link {{ request()->is(['ajuanlimit', 'ajuanlimit/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ajuan Limit Kredit
                    @if (!empty($notifikasi_limitkredit))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_limitkredit }}</span>
                    @endif
                </a>

            </li>
        @endcan
        @can('ajuanfaktur.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('ajuanfaktur.index') }}" class="nav-link {{ request()->is(['ajuanfaktur', 'ajuanfaktur/*']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Ajuan Faktur Kredit
                    @if (!empty($notifikasi_ajuanfaktur))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_ajuanfaktur }}</span>
                    @endif
                </a>
            </li>
        @endcan
    </ul>
@endif
