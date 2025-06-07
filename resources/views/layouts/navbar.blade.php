@php
    $agent = new Jenssegers\Agent\Agent();
@endphp
<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar"
    @if ($agent->isMobile()) style="width:100% !important; margin:0 !important  " @endif>
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                    <i class="ti ti-search ti-md me-2"></i>
                    <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span>
                </a>
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            @if (Cookie::get('kodepelanggan') != null && $level_user == 'salesman')
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="btn btn-sm btn-primary mt-1" href="/sfa/pelanggan/{{ Cookie::get('kodepelanggan') }}/show">
                        <i class="ti ti-sm ti-user"></i> Pelanggan
                    </a>
                </li>
            @endif
            @if (in_array($level_user, [
                    'super admin',
                    'direktur',
                    'gm marketing',
                    'gm operasional',
                    'gm administrasi',
                    'operation manager',
                    'sales marketing manager',
                    'regional sales manager',
                    'regional operation manager',
                    'manager keuangan',
                ]))
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        <i class="ti ti-layout-grid-add ti-md"></i>
                        <span class="badge bg-danger rounded-pill badge-notifications">{{ $total_notifikasi }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                                <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Add shortcuts">
                                    <i class="ti ti-sm ti-apps"></i>
                                </a>

                            </div>
                        </div>
                        <div class="dropdown-shortcuts-list scrollable-container h-auto">
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-brand-shopee fs-4"></i>
                                        @if (!empty($notifikasi_limitkredit))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_limitkredit }}</span>
                                        @endif

                                    </span>

                                    <a href="{{ route('ajuanlimit.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}"
                                        class="stretched-link">Ajuan</a>
                                    <small class="text-muted mb-0">Limit Kredit</small>
                                </div>
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-file-invoice fs-4"></i>
                                        @if (!empty($notifikasi_ajuanfaktur))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_ajuanfaktur }}</span>
                                        @endif
                                    </span>
                                    <a href="{{ route('ajuanfaktur.index', ['posisi_ajuan' => $level_user, 'status' => 0]) }}"
                                        class="stretched-link">Ajuan</a>
                                    <small class="text-muted mb-0">Faktur Kredit</small>
                                </div>
                            </div>
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-users fs-4"></i>
                                        @if (!empty($notifikasi_penilaiankaryawan))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_penilaiankaryawan }}</span>
                                        @endif
                                    </span>

                                    <a href="{{ route('penilaiankaryawan.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}"
                                        class="stretched-link">Penilaian</a>
                                    <small class="text-muted mb-0">Karyawan</small>
                                </div>
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-target-arrow fs-4"></i>
                                        @if (!empty($notifikasi_target))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_target }}
                                            </span>
                                        @endif
                                    </span>
                                    <a href="/targetkomisi?posisi_ajuan={{ $level_user }}&status=0" class="stretched-link">Target</a>
                                    <small class="text-muted mb-0">Marketing</small>
                                </div>
                            </div>
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-receipt fs-4"></i>
                                        @if (!empty($notifikasi_pengajuan_izin))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_pengajuan_izin }}</span>
                                        @endif
                                    </span>

                                    <a href="{{ route('izinabsen.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}"
                                        class="stretched-link">Pengajuan Izin</a>
                                    <small class="text-muted mb-0">Karyawan</small>
                                </div>
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-target-arrow fs-4"></i>
                                        @if (!empty($notifikasi_lembur))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_lembur }}
                                            </span>
                                        @endif
                                    </span>
                                    <a href="{{ route('lembur.index', ['posisi_ajuan' => $level_user, 'status' => 'pending']) }}"
                                        class="stretched-link">Lembur</a>
                                    <small class="text-muted mb-0">Karyawan</small>
                                </div>
                            </div>
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-wallet fs-4"></i>
                                        @if (!empty($notifikasiajuantransferdana))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasiajuantransferdana }}</span>
                                        @endif
                                    </span>

                                    <a href="{{ route('ajuantransfer.index') }}" class="stretched-link">Ajuan Transfer</a>
                                    <small class="text-muted mb-0">Dana</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ti ti-archive ti-md"></i>
                        <span class="badge bg-danger rounded-pill badge-notifications">{{ $notifikasi_ajuan_program }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                                <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Add shortcuts">
                                    <i class="ti ti-sm ti-archive"></i>
                                </a>

                            </div>
                        </div>
                        <div class="dropdown-shortcuts-list scrollable-container h-auto">
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-file-invoice fs-4"></i>
                                        @if (!empty($notifikasi_ajuanprogramikatan))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_ajuanprogramikatan }}</span>
                                        @endif

                                    </span>

                                    <a href="{{ route('ajuanprogramikatan.index') }}?status=pending" class="stretched-link">Ajuan</a>
                                    <small class="text-muted mb-0">Program Ikatan</small>
                                </div>
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-file-invoice fs-4"></i>
                                        @if (!empty($notifikasi_pencairanprogramikatan))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_pencairanprogramikatan }}</span>
                                        @endif
                                    </span>
                                    <a href="{{ route('pencairanprogramikatan.index') }}" class="stretched-link">Pencairan</a>
                                    <small class="text-muted mb-0">Program Ikatan</small>
                                </div>
                            </div>
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-file-invoice fs-4"></i>
                                        @if (!empty($notifikasi_ajuanprogramkumulatif))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_ajuanprogramkumulatif }}</span>
                                        @endif

                                    </span>

                                    <a href="{{ route('ajuankumulatif.index') }}?status=pending" class="stretched-link">Ajuan</a>
                                    <small class="text-muted mb-0">Program Kumulatif</small>
                                </div>
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                        <i class="ti ti-file-invoice fs-4"></i>
                                        @if (!empty($notifikasi_pencairanprogramkumulatif))
                                            <span class="badge bg-danger rounded-pill badge-notifications"
                                                style="position: absolute; right: 50px; top:20px">{{ $notifikasi_pencairanprogramkumulatif }}</span>
                                        @endif
                                    </span>
                                    <a href="{{ route('pencairanprogram.index') }}" class="stretched-link">Pencairan</a>
                                    <small class="text-muted mb-0">Program Kumulatif</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </li>
                @if ($level_user == 'gm administrasi' || $level_user == 'super admin')
                    <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                        <a class="nav-link" href="{{ route('ticket.index') }}">
                            <i class="ti ti-tool ti-md"></i>
                            <span class="badge bg-danger rounded-pill badge-notifications">{{ $notifikasi_ticket }}</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                        <a class="nav-link" href="{{ route('ticketupdate.index') }}?status=pending">
                            <i class="ti ti-recycle ti-md"></i>
                            <span class="badge bg-danger rounded-pill badge-notifications">{{ $notifikasi_update_data }}</span>
                        </a>
                    </li>
                @endif

            @endif

            @if (in_array($level_user, ['spv presensi']))
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="{{ route('izinabsen.index') }}">
                        <i class="ti ti-layout-grid-add ti-md"></i>
                        <span class="badge bg-danger rounded-pill badge-notifications">{{ $total_notifikasi_izin_spvpresensi }}</span>
                    </a>
                </li>
            @endif
            <!-- Quick links  -->

            <!-- Quick links -->

            <!-- Notification -->
            {{-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                    aria-expanded="false">
                    <i class="ti ti-bell ti-md"></i>
                    <span class="badge bg-danger rounded-pill badge-notifications">5</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">Notification</h5>
                            <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Mark all as read"><i class="ti ti-mail-opened fs-4"></i></a>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <img src="{{ asset('') }}/assets/img/avatars/1.png" alt class="h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Congratulation Lettie 🎉</h6>
                                        <p class="mb-0">Won the monthly best seller gold badge</p>
                                        <small class="text-muted">1h ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Charles Franklin</h6>
                                        <p class="mb-0">Accepted your connection</p>
                                        <small class="text-muted">12hr ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <img src="{{ asset('') }}/assets/img/avatars/2.png" alt class="h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">New Message ✉️</h6>
                                        <p class="mb-0">You have new message from Natalie</p>
                                        <small class="text-muted">1h ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-success"><i class="ti ti-shopping-cart"></i></span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Whoo! You have new order 🛒</h6>
                                        <p class="mb-0">ACME Inc. made new order $1,154</p>
                                        <small class="text-muted">1 day ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <img src="{{ asset('') }}/assets/img/avatars/9.png" alt class="h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Application has been approved 🚀</h6>
                                        <p class="mb-0">Your ABC project application has been
                                            approved.</p>
                                        <small class="text-muted">2 days ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-success"><i class="ti ti-chart-pie"></i></span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Monthly report is generated</h6>
                                        <p class="mb-0">July monthly financial report is generated
                                        </p>
                                        <small class="text-muted">3 days ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <img src="{{ asset('') }}/assets/img/avatars/5.png" alt class="h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Send connection request</h6>
                                        <p class="mb-0">Peter sent you connection request</p>
                                        <small class="text-muted">4 days ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <img src="{{ asset('') }}/assets/img/avatars/6.png" alt class="h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">New message from Jane</h6>
                                        <p class="mb-0">Your have new message from Jane</p>
                                        <small class="text-muted">5 days ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-warning"><i class="ti ti-alert-triangle"></i></span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">CPU is running high</h6>
                                        <p class="mb-0">CPU Utilization Percent is currently at
                                            88.63%,</p>
                                        <small class="text-muted">5 days ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top">
                        <a href="javascript:void(0);"
                            class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </li> --}}
            <!--/ Notification -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('/assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="pages-account-settings-account.html">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('/assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ textCamelCase($level_user) }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('users.ubahpassword') }}">
                            <i class="ti ti-key me-2 ti-sm"></i>
                            <span class="align-middle">Ubah Password</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-fluid border-0" placeholder="Search..." aria-label="Search..." />
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
</nav>
