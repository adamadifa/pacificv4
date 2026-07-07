<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-wide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('/assets/') }}" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>@yield('titlepage')</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/img/favicon/favicon.ico') }}" />

    @include('layouts.fonts')

    @include('layouts.icons')

    @include('layouts.styles')
    @yield('style')
    <!-- Helpers -->
    <script src="{{ asset('/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Sidebar -->
            @include('layouts.sidebar')
            <!-- / Sidebar-->
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-fluid flex-grow-1 container-p-y">
                        @php
                            $agent = new Jenssegers\Agent\Agent();
                        @endphp
                        <h4 class="{{ !$agent->isMobile() ? 'py-3 mb-2' : '' }}">@yield('navigasi')</h4>
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.footer')
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    <!-- BEGIN: Customizer-->
    @hasanyrole(['super admin', 'gm administrasi', 'direktur'])
    <div class="customizer d-none d-md-block"><a class="customizer-toggle d-flex align-items-center justify-content-center" href="#"><i
                class="spinner-grow white"></i></a>
        <div class="customizer-content">
            <!-- Customizer header -->
            <div class="customizer-header px-4 pt-2 pb-0 position-relative">
                <h4 class="mb-0">Daftar User Online</h4>
                <p class="m-0">User Online</p>

                <a class="customizer-close" href="#"><i data-feather="x"></i></a>
            </div>
            <hr>
            @foreach ($users as $d)
                <div class="customizer-menu px-2 mt-2">





                    <div id="customizer-menu-collapsible" class="d-flex justify-content-between align-items-center">
                        <div class="mr-50 d-flex justify-content-start">
                            <div class="image">
                                @if (!empty($d->foto))
                                    @php
                                        $path = Storage::url('users/' . $d->foto);
                                    @endphp
                                    <img src="{{ url($path) }}" alt="avtar img holder" height="35" width="35">
                                @else
                                    <img src="{{ asset('assets/img/avatars/1.png') }}" class="rounded" alt="" height="52" width="52">
                                @endif
                            </div>
                            <div class="user-page-info ms-3">
                                <span class="mt-1 p-0 mb-0" style="font-size: 16px">{{ $d->name }}</span><br>
                                <small class="text-success"><i>Last Seen {{ Carbon\Carbon::parse($d->last_seen)->diffForHumans() }}</i></small>
                            </div>
                        </div>

                        @if (Cache::has('user-is-online-' . $d->id))
                            <div class="ml-auto"><i class="fa fa-circle text-success"></i></div>
                        @else
                            <div class="ml-auto"><i class="fa fa-circle text-danger"></i></div>
                        @endif


                    </div>

                </div>
            @endforeach
        </div>
    </div>
    @endhasanyrole
    <!-- Bottom Navigation -->
    @hasanyrole(['gm marketing', 'regional sales manager', 'sales marketing manager'])
        <nav class="navbar fixed-bottom navbar-light bg-white shadow d-md-none">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><i class="ti ti-user" style="font-size: 20px"></i></a>
                <a class="navbar-brand" href="{{ route('aktifitassmm.index') }}"><i
                        class="ti ti-file-description {{ request()->is('aktifitassmm') ? 'text-primary' : '' }}" style="font-size: 20px"></i></a>
                <a class="navbar-brand" href="/dashboard"><i class="fa fa-home {{ request()->is('dashboard') ? 'text-primary' : '' }}"
                        style="font-size: 25px; border-radius: 50%;"></i></a>
                <a class="navbar-brand" href="#"><i class="ti ti-mail" style="font-size: 20px"></i></a>
                <a class="navbar-brand" href="#"><i class="ti ti-help" style="font-size: 20px"></i></a>
            </div>
        </nav>
    @endhasanyrole
    <!-- End: Customizer-->
    <!-- Core JS -->
    @include('layouts.scripts')
    <!-- Page JS -->
    @if (!request()->routeIs('users.ubahpassword'))
    @php
        $is_weak_password = false;
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->password_secure == 0) {
                $is_weak_password = true;
            }
        }
    @endphp
    <!-- Modal Informasi Ganti Password Default -->
    <div class="modal fade" id="defaultCredentialsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-danger text-white py-3 border-0">
                    <h5 class="modal-title text-white d-flex align-items-center gap-2 fw-bold" id="defaultCredentialsModalLabel">
                        <i class="ti ti-shield-lock" style="font-size: 1.5rem;"></i>
                        Pemberitahuan Keamanan Penting
                    </h5>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <div class="avatar avatar-xl bg-label-danger mb-2 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(234, 84, 85, 0.16);">
                            <i class="ti ti-alert-triangle text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold text-danger">Segera Ubah Kredensial Default!</h4>
                    </div>
                    
                    <p class="text-secondary leading-relaxed mb-3">
                        Untuk menjaga keamanan data operasional perusahaan, seluruh pengguna diwajibkan untuk segera <strong>mengganti username dan password bawaan (default)</strong> masing-masing.
                    </p>

                    <!-- Cara Mengubah Password -->
                    <div class="card bg-light border-0 mb-3" style="border-radius: 10px; background-color: #f8f9fa;">
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-2 text-dark"><i class="ti ti-list-numbers me-1"></i> Cara Mengubah Password:</h6>
                            <ol class="ps-3 mb-0 text-secondary" style="font-size: 0.9rem; line-height: 1.5;">
                                <li class="mb-1">Klik tombol <strong>Ubah Password Sekarang</strong> di bawah (atau akses menu profil di pojok kanan atas).</li>
                                <li class="mb-1">Masukkan password lama/default Anda saat ini.</li>
                                <li class="mb-1">Ketik password baru Anda yang aman dan sulit ditebak.</li>
                                <li>Klik tombol update untuk menyelesaikan pembaruan data.</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning border-0 p-3 d-flex align-items-start gap-3" style="border-radius: 10px; background-color: rgba(255, 193, 7, 0.15);">
                        <i class="ti ti-info-circle text-warning mt-1" style="font-size: 1.3rem;"></i>
                        <div class="text-warning-dark" style="color: #664d03; font-size: 0.9rem;">
                            <strong>PENTING:</strong> Keamanan akun serta setiap aktivitas/transaksi yang terjadi menggunakan akun Anda sepenuhnya merupakan <strong>tanggung jawab masing-masing user</strong>. Jangan bagikan username atau password Anda kepada siapapun.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light d-flex justify-content-between gap-2">
                    <a href="{{ route('users.ubahpassword') }}" class="btn btn-outline-danger px-3 py-2 fw-bold flex-grow-1" style="border-radius: 8px;">
                        <i class="ti ti-key me-1"></i> Ubah Password Sekarang
                    </a>
                    <button type="button" class="btn btn-danger px-3 py-2 fw-bold flex-grow-1" id="btnUnderstandCredentials" style="border-radius: 8px;">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isWeakPassword = {{ $is_weak_password ? 'true' : 'false' }};
            if (isWeakPassword || !sessionStorage.getItem('dismissed_default_credentials_warning')) {
                var myModal = new bootstrap.Modal(document.getElementById('defaultCredentialsModal'), {
                    keyboard: false,
                    backdrop: 'static'
                });
                myModal.show();

                document.getElementById('btnUnderstandCredentials').addEventListener('click', function() {
                    sessionStorage.setItem('dismissed_default_credentials_warning', 'true');
                    myModal.hide();
                });
            }
        });
    </script>
    @endif
</body>

</html>
