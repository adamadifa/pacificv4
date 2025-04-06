<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-wide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('/assets/') }}" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

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
</body>

</html>
