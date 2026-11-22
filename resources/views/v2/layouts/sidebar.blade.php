@php
    $user = Auth::user();
    $level_user = $level_user ?? ($user && $user->roles->first() ? $user->roles->first()->name : '');
    $photo = asset('assets/img/avatars/1.png');
    if ($user && $user->username) {
        $karyawan = DB::table('hrd_karyawan')->where('nik', $user->username)->select('foto')->first();
        if ($karyawan && !empty($karyawan->foto) && function_exists('getfotoKaryawan')) {
            $photo = getfotoKaryawan($karyawan->foto);
        }
    }
@endphp

<style>
    #layout-menu-v2 {
        background: linear-gradient(180deg, #284c9a 0%, #162a5b 100%) !important;
        border-right: 0 !important;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15) !important;
        width: 260px !important;
        min-width: 260px !important;
        max-width: 260px !important;
    }

    /* User Profile Card from V1 with Rotating Glow */
    .user-profile-sidebar-v2 {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        padding: 0.625rem 0.875rem;
        margin: 0.4rem 0.625rem 0.625rem 0.625rem;
        background: #284c9a;
        border-radius: 12px;
        transition: all 0.3s ease;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .user-profile-sidebar-v2::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(
            transparent,
            rgba(0, 210, 255, 0.4),
            transparent 30%
        );
        animation: rotate-glow-v2 4s linear infinite;
        z-index: -2;
    }

    .user-profile-sidebar-v2::after {
        content: '';
        position: absolute;
        inset: 1px;
        background: #284c9a;
        border-radius: 11px;
        z-index: -1;
    }

    @keyframes rotate-glow-v2 {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .user-profile-sidebar-v2 .avatar-wrapper {
        position: relative;
        flex-shrink: 0;
        width: 44px;
        height: 44px;
    }

    .user-profile-sidebar-v2 .avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        padding: 2px;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .user-profile-sidebar-v2 .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .user-profile-sidebar-v2 .status-indicator {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 11px;
        height: 11px;
        background: #28c76f;
        border: 2px solid #284c9a;
        border-radius: 50%;
        box-shadow: 0 0 5px rgba(40, 199, 111, 0.4);
    }

    .user-profile-sidebar-v2 .user-info-text {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .user-profile-sidebar-v2 .user-name {
        font-size: 0.8125rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .user-profile-sidebar-v2 .user-role-wrapper {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 2px;
    }

    .user-profile-sidebar-v2 .role-icon {
        font-size: 0.75rem;
        color: #00d2ff;
        opacity: 0.8;
    }

    .user-profile-sidebar-v2 .user-role {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.65);
        text-transform: uppercase;
        font-weight: 600;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .user-profile-sidebar-v2 .meta-item {
        font-size: 0.625rem;
        background: rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.85);
        padding: 1px 5px;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 2px;
        display: inline-block;
        width: fit-content;
    }

    /* Menu Item & Hierarchy Styles */
    #layout-menu-v2 .menu-inner {
        list-style: none;
        padding: 0 0.5rem 1.5rem 0.5rem;
        margin: 0;
    }

    #layout-menu-v2 .menu-item {
        position: relative;
        list-style: none;
        margin-bottom: 1px;
    }

    #layout-menu-v2 .menu-link {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.42rem 0.75rem;
        color: rgba(255, 255, 255, 0.75);
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        position: relative;
    }

    #layout-menu-v2 .menu-link:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        transform: translateX(3px);
    }

    #layout-menu-v2 .menu-item.active > .menu-link {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        font-weight: 700;
        box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.08);
    }

    #layout-menu-v2 .menu-item.active > .menu-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 15%;
        height: 70%;
        width: 3.5px;
        background: #00d2ff;
        border-radius: 0 4px 4px 0;
        box-shadow: 0 0 10px #00d2ff;
    }

    #layout-menu-v2 .menu-icon {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.65);
        flex-shrink: 0;
        width: 1.25rem;
        text-align: center;
        transition: all 0.2s ease;
    }

    #layout-menu-v2 .menu-item.active > .menu-link .menu-icon,
    #layout-menu-v2 .menu-link:hover .menu-icon {
        color: #00d2ff;
        transform: scale(1.1);
    }

    /* Submenu Accordion */
    #layout-menu-v2 .menu-toggle::after {
        content: '';
        position: absolute;
        right: 0.875rem;
        top: 50%;
        width: 0.4rem;
        height: 0.4rem;
        border-right: 2px solid rgba(255, 255, 255, 0.5);
        border-bottom: 2px solid rgba(255, 255, 255, 0.5);
        transform: translateY(-50%) rotate(-45deg);
        transition: transform 0.2s ease;
    }

    #layout-menu-v2 .menu-item.open > .menu-toggle::after {
        transform: translateY(-50%) rotate(45deg);
    }

    #layout-menu-v2 .menu-sub {
        list-style: none;
        padding: 0.15rem 0 0.15rem 1.25rem;
        margin: 0;
        display: none;
        position: relative;
    }

    #layout-menu-v2 .menu-sub::before {
        content: '';
        position: absolute;
        left: 1.5rem;
        top: 0.25rem;
        bottom: 0.25rem;
        width: 1.5px;
        background: rgba(255, 255, 255, 0.1);
    }

    #layout-menu-v2 .menu-item.open > .menu-sub {
        display: block;
    }

    #layout-menu-v2 .menu-sub .menu-link {
        padding: 0.32rem 0.75rem 0.32rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.65);
    }

    #layout-menu-v2 .menu-sub .menu-item.active > .menu-link {
        color: #00d2ff;
        background: rgba(0, 210, 255, 0.08);
    }

    #layout-menu-v2 .menu-header {
        list-style: none;
        padding: 0.75rem 0.75rem 0.25rem 0.75rem;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.45);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    #layout-menu-v2 .menu-header:first-child {
        padding-top: 0.25rem;
    }

    #layout-menu-v2 .menu-header::after {
        content: '';
        height: 1px;
        flex: 1;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.02) 100%);
    }

    /* Thin Custom Scrollbar for Sidebar */
    #layout-menu-v2 .sidebar-scroll-container::-webkit-scrollbar {
        width: 4px;
    }
    #layout-menu-v2 .sidebar-scroll-container::-webkit-scrollbar-track {
        background: transparent;
    }
    #layout-menu-v2 .sidebar-scroll-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 9999px;
    }
</style>

<!-- SIDEBAR V2 (Width 260px & Exact V1 Background Color) -->
<aside id="layout-menu-v2" class="fixed inset-y-0 left-0 z-50 lg:static flex flex-col justify-between shrink-0 select-none -translate-x-full lg:translate-x-0 h-full overflow-hidden transition-all duration-300">
    
    <!-- Top Area: Brand & User Profile -->
    <div class="shrink-0">
        <!-- Brand Header -->
        <div class="flex items-center justify-between px-4 pt-4 pb-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 min-w-0">
                <span class="shrink-0">
                    <img src="{{ asset('assets/img/logo/logoportal64.png') }}" alt="Logo" width="42" style="filter: drop-shadow(0 0 8px rgba(0,210,255,0.4))">
                </span>
                <span class="font-bold text-base tracking-widest text-white block truncate" style="letter-spacing: 1.5px">PORTAL</span>
            </a>

            <!-- Mobile Close Button -->
            <button id="closeMobileSidebarBtn" type="button" class="lg:hidden text-white/60 hover:text-white p-1 rounded-lg hover:bg-white/10 transition">
                <i class="ti ti-x text-lg"></i>
            </button>
        </div>

        <!-- User Profile Card from V1 -->
        <div class="user-profile-sidebar-v2">
            <div class="avatar-wrapper">
                <div class="avatar border-0">
                    <img src="{{ $photo }}" alt="{{ $user->name ?? 'User' }}" onerror="this.src='{{ asset('assets/img/avatars/1.png') }}'" />
                </div>
                <div class="status-indicator"></div>
            </div>
            <div class="user-info-text">
                <div class="user-name">
                    @php
                        $nameParts = explode(' ', trim($user->name ?? 'User'));
                        echo count($nameParts) > 1 ? $nameParts[0] . ' ' . $nameParts[1] : $nameParts[0];
                    @endphp
                </div>
                <div class="user-role-wrapper">
                    <i class="ti ti-shield role-icon"></i>
                    <span class="user-role">{{ strtolower($level_user) }}</span>
                </div>
                @if(auth()->user()->kode_salesman || auth()->user()->kode_cabang)
                    <div class="flex items-center gap-1">
                        @if(auth()->user()->kode_salesman)
                            <span class="meta-item">{{ auth()->user()->kode_salesman }}</span>
                        @endif
                        @if(auth()->user()->kode_cabang)
                            <span class="meta-item">{{ auth()->user()->kode_cabang }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Scrollable Navigation Area (Categorized & Grouped Menu) -->
    <div class="sidebar-scroll-container flex-1 overflow-y-auto overflow-x-hidden">
        <ul class="menu-inner">
            
            <!-- 1. SECTION: UTAMA -->
            <li class="menu-header">
                <span>Utama</span>
            </li>

            <!-- Dashboard -->
            <li class="menu-item {{ request()->is(['dashboard', 'dashboard/*']) ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            <!-- Dashboard Owner -->
            @if (in_array($level_user, ['super admin', 'direktur', 'gm administrasi', 'manager keuangan', 'regional operation manager', 'spv accounting']))
                <li class="menu-item {{ request()->is(['dashboard/owner', 'sarekening', 'sarekening/*']) ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-device-analytics"></i>
                        <div>Dashboard Owner</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->is(['dashboard/owner']) ? 'active' : '' }}">
                            <a href="{{ route('dashboard.owner') }}" class="menu-link">
                                <div>Dashboard</div>
                            </a>
                        </li>
                        @if (auth()->user()->hasAnyPermission(['sarekening.index']))
                            <li class="menu-item {{ request()->is(['sarekening', 'sarekening/*']) ? 'active' : '' }}">
                                <a href="{{ route('sarekening.index') }}" class="menu-link">
                                    <div>Saldo Awal Rekening</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            <!-- 2. SECTION: SALES FORCE AUTOMATION (SFA) -->
            @if (auth()->user()->hasAnyPermission(['dashboard.sfa', 'sfa.trackingsalesman', 'tracking.index', 'sfa.pelanggan', 'sfa.penjualan']))
                <li class="menu-header">
                    <span>Monitoring & SFA</span>
                </li>

                <!-- Dashboard SFA -->
                @if (auth()->user()->hasAnyPermission(['dashboard.sfa']))
                    <li class="menu-item {{ request()->is(['sfa/dashboard', 'sfa/dashboard/*']) ? 'active' : '' }}">
                        <a href="{{ route('dashboard.sfa') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-chart-bar"></i>
                            <div>Dashboard SFA</div>
                        </a>
                    </li>
                @endif

                <!-- Tracking Salesman -->
                @if (auth()->user()->hasAnyPermission(['sfa.trackingsalesman']))
                    <li class="menu-item {{ request()->is(['sfa/trackingsalesman']) ? 'active' : '' }}">
                        <a href="{{ route('sfa.trackingsalesman') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-map-pin"></i>
                            <div>Tracking Salesman</div>
                        </a>
                    </li>
                @endif

                <!-- Realtime Tracking -->
                @if (auth()->user()->hasAnyPermission(['tracking.index']))
                    <li class="menu-item {{ request()->is(['tracking', 'tracking/*']) ? 'active' : '' }}">
                        <a href="{{ route('tracking.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-location"></i>
                            <div>Realtime Tracking</div>
                        </a>
                    </li>
                @endif

                <!-- Pelanggan SFA -->
                @if (auth()->user()->hasAnyPermission(['sfa.pelanggan']))
                    <li class="menu-item {{ request()->is(['sfa/pelanggan']) ? 'active' : '' }}">
                        <a href="{{ route('sfa.pelanggan') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-users-group"></i>
                            <div>Pelanggan</div>
                        </a>
                    </li>
                @endif

                <!-- Penjualan SFA -->
                @if (auth()->user()->hasAnyPermission(['sfa.penjualan']))
                    <li class="menu-item {{ request()->is(['sfa/penjualan']) ? 'active' : '' }}">
                        <a href="{{ route('sfa.penjualan') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                            <div>Penjualan</div>
                        </a>
                    </li>
                @endif
            @endif


            <!-- 3. SECTION: MASTER DATA -->
            @if (auth()->user()->hasAnyPermission($datamaster_permission ?? []))
                <li class="menu-header">
                    <span>Master Data</span>
                </li>
                @include('layouts.sidebar.datamaster')
            @endif


            <!-- 4. SECTION: PRODUKSI & LOGISTIK -->
            @php
                $hasProduksiLogistik = 
                    auth()->user()->hasAnyPermission($produksi_permission ?? []) ||
                    auth()->user()->hasAnyPermission($gudang_bahan_permission ?? []) ||
                    auth()->user()->hasAnyPermission($gudang_logistik_permission ?? []) ||
                    auth()->user()->hasAnyPermission($gudang_jadi_permission ?? []) ||
                    auth()->user()->hasAnyPermission($gudang_cabang_permission ?? []) ||
                    auth()->user()->hasAnyPermission(['barangmasukmtc.index', 'barangkeluarmtc.index', 'mtc.bahanbakar']);
            @endphp
            @if ($hasProduksiLogistik)
                <li class="menu-header">
                    <span>Produksi & Logistik</span>
                </li>
                @include('layouts.sidebar.produksi')
                @include('layouts.sidebar.gudang_bahan')
                @include('layouts.sidebar.gudang_logistik')
                @include('layouts.sidebar.gudang_jadi')
                @include('layouts.sidebar.gudang_cabang')
                @include('layouts.sidebar.maintenance')
            @endif


            <!-- 5. SECTION: MARKETING & PENJUALAN -->
            @php
                $hasMarketing = 
                    auth()->user()->hasAnyPermission([
                        'omancabang.index', 'oman.index', 'permintaankiriman.index',
                        'targetkomisi.index', 'penjualan.index', 'retur.index',
                        'ajuanlimit.index', 'sapiutangsales.index', 'movefaktur.index',
                        'trackingtruck.index'
                    ]) ||
                    auth()->user()->hasAnyPermission([
                        'worksheetom.oman', 'worksheetom.komisisalesman', 'worksheetom.insentifom',
                        'worksheetom.komisidriverhelper', 'worksheetom.costratio', 'worksheetom.visitpelanggan',
                        'worksheetom.monitoringretur', 'worksheetom.monitoringprogram', 'worksheetom.kebutuhancabang',
                        'worksheetom.produkexpired', 'worksheetom.evaluasisharing', 'worksheetom.bbm',
                        'worksheetom.ratiobs', 'worksheetom.dokumenopname', 'ajuanprogramikatan.index',
                        'pencairanprogramikt.index', 'pencairanprogram.index', 'ajuankumulatif.index',
                        'programikatan2026.index', 'programmarketing.index'
                    ]);
            @endphp
            @if ($hasMarketing)
                <li class="menu-header">
                    <span>Marketing & Penjualan</span>
                </li>
                @include('layouts.sidebar.marketing')
                @include('layouts.sidebar.worksheetom')
            @endif


            <!-- 6. SECTION: PEMBELIAN -->
            @php
                $hasPembelian = auth()->user()->hasAnyPermission([
                    'pembelian.index', 'pembelian.jatuhtempo', 'jurnalkoreksi.index',
                    'kontrabonpmb.index', 'po.index', 'bpbpembelian.index',
                    'pb.pembelian', 'pb.rekapsupplier', 'pb.rekappembelian',
                    'pb.kartuhutang', 'pb.auh', 'pb.bahankemasan',
                    'pb.jurnalkoreksi', 'pb.rekapakun', 'pb.rekapkontrabon'
                ]);
            @endphp
            @if ($hasPembelian)
                <li class="menu-header">
                    <span>Pembelian</span>
                </li>
                @include('layouts.sidebar.pembelian')
            @endif


            <!-- 7. SECTION: KEUANGAN & AKUNTANSI -->
            @php
                $hasFinance = 
                    auth()->user()->hasAnyPermission([
                        'pembayarantransfer.index', 'pembayarangiro.index', 'setorangiro.index',
                        'setoranpusat.index', 'ajuantransfer.index', 'kaskecil.index',
                        'sakaskecil.index', 'klaimkaskecil.index', 'ledger.index',
                        'saledger.index', 'mutasibank.index', 'pjp.index', 'kasbon.index',
                        'pembayarankasbon.index', 'piutangkaryawan.index', 'piutangekskaryawan.index',
                        'kontrabonpembelian.index', 'coa.index', 'coacabang.index',
                        'costratio.index', 'jurnalumum.index', 'hpp.index', 'hargawalahpp.index'
                    ]);
            @endphp
            @if ($hasFinance)
                <li class="menu-header">
                    <span>Keuangan & Akuntansi</span>
                </li>
                @include('layouts.sidebar.keuangan')
                @include('layouts.sidebar.accounting')
            @endif


            <!-- 8. SECTION: SDM & GENERAL AFFAIR -->
            @php
                $hasHrdGa = 
                    auth()->user()->hasAnyPermission([
                        'kontrakkerja.index', 'suratperingatan.index', 'jasamasakerja.index', 'kb.index',
                        'penilaiankaryawan.index', 'jadwalshift.index', 'harilibur.index', 'lembur.index',
                        'izinabsen.index', 'izincuti.index', 'izinsakit.index', 'izinpulang.index',
                        'izinkoreksi.index', 'izindinas.index', 'izinterlambat.index', 'presensi.index',
                        'slipgaji.index', 'penyupah.index', 'resign.index',
                        'mutasikendaraan.index', 'servicekendaraan.index', 'badstokga.index'
                    ]);
            @endphp
            @if ($hasHrdGa)
                <li class="menu-header">
                    <span>SDM & General Affair</span>
                </li>
                @include('layouts.sidebar.hrd')
                @include('layouts.sidebar.generalaffair')
            @endif


            <!-- 9. SECTION: PENGATURAN & SISTEM -->
            @php
                $hasSettings = 
                    auth()->user()->hasRole(['super admin', 'gm administrasi']) ||
                    auth()->user()->hasAnyPermission([
                        'kirimlhp.index', 'kirimlpc.index', 'tutuplaporan.index',
                        'activitylog.index', 'push-subscriptions.index', 'backup.database',
                        'ticket.index'
                    ]);
            @endphp
            @if ($hasSettings)
                <li class="menu-header">
                    <span>Pengaturan & Sistem</span>
                </li>

                <!-- Utilities -->
                @if (auth()->user()->hasAnyPermission(['kirimlhp.index', 'kirimlpc.index', 'tutuplaporan.index', 'activitylog.index', 'push-subscriptions.index']) ||
                        auth()->user()->hasRole(['super admin', 'gm administrasi']))
                    <li class="menu-item {{ request()->is(['kirimlhp', 'kirimlpc', 'tutuplaporan', 'activitylog', 'ticket', 'mesinfingerprint', 'push-subscriptions']) ? 'open' : '' }} ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons ti ti-tool"></i>
                            <div>Utilities</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is(['kirimlhp', 'kirimlhp/*']) ? 'active' : '' }}">
                                <a href="{{ route('kirimlhp.index') }}" class="menu-link">
                                    <div>Kirim LHP</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is(['kirimlpc', 'kirimlpc/*']) ? 'active' : '' }}">
                                <a href="{{ route('kirimlpc.index') }}" class="menu-link">
                                    <div>Kirim LPC</div>
                                </a>
                            </li>
                            @can('tutuplaporan.index')
                            <li class="menu-item {{ request()->is(['tutuplaporan', 'tutuplaporan/*']) ? 'active' : '' }}">
                                <a href="{{ route('tutuplaporan.index') }}" class="menu-link">
                                    <div>Tutup Laporan</div>
                                </a>
                            </li>
                            @endcan
                            @can('activitylog.index')
                                <li class="menu-item {{ request()->is(['activitylog', 'activitylog/*']) ? 'active' : '' }}">
                                    <a href="{{ route('activitylog.index') }}" class="menu-link">
                                        <div>Log Aktivitas</div>
                                    </a>
                                </li>
                            @endcan

                            @can('backup.database')
                                <li class="menu-item {{ request()->is(['backup-database', 'backup-database/*']) ? 'active' : '' }}">
                                    <a href="{{ route('backup.database.index') }}" class="menu-link">
                                        <div>Backup Database</div>
                                    </a>
                                </li>
                            @endcan

                            @if (auth()->user()->hasRole(['super admin']))
                                <li class="menu-item {{ request()->is(['mesinfingerprint', 'mesinfingerprint/*']) ? 'active' : '' }}">
                                    <a href="{{ route('mesinfingerprint.index') }}" class="menu-link">
                                        <div>Mesin Fingerprint</div>
                                    </a>
                                </li>
                            @endif
                            @can('push-subscriptions.index')
                            <li class="menu-item {{ request()->is(['push-subscriptions', 'push-subscriptions/*']) ? 'active' : '' }}">
                                <a href="{{ route('push-subscriptions.index') }}" class="menu-link">
                                    <div>Push Subscriptions</div>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                <!-- Settings -->
                @if (auth()->user()->hasRole(['super admin', 'gm administrasi']))
                    <li class="menu-item {{ request()->is(['roles', 'roles/*', 'permissiongroups', 'permissiongroups/*', 'permissions', 'permissions/*', 'users', 'users/*']) ? 'open' : '' }} ">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons ti ti-adjustments-horizontal"></i>
                            <div>Settings</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is(['users', 'users/*']) ? 'active' : '' }}">
                                <a href="{{ route('users.index') }}" class="menu-link">
                                    <div>User</div>
                                </a>
                            </li>
                            @if (auth()->user()->hasRole(['super admin']))
                                <li class="menu-item {{ request()->is(['roles', 'roles/*']) ? 'active' : '' }}">
                                    <a href="{{ route('roles.index') }}" class="menu-link">
                                        <div>Role</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->is(['permissions', 'permissions/*']) ? 'active' : '' }}">
                                    <a href="{{ route('permissions.index') }}" class="menu-link">
                                        <div>Permission</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->is(['permissiongroups', 'permissiongroups/*']) ? 'active' : '' }}">
                                    <a href="{{ route('permissiongroups.index') }}" class="menu-link">
                                        <div>Group Permission</div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Ticket -->
                @can('ticket.index')
                <li class="menu-item {{ request()->is(['ticket', 'ticket/*']) ? 'active' : '' }}">
                    <a href="{{ route('ticket.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-ticket"></i>
                        <div>Ticket</div>
                    </a>
                </li>
                @endcan

                <!-- WA Komplain -->
                @if (auth()->user()->hasRole(['super admin']))
                    <li class="menu-item {{ request()->is(['wa-komplain', 'wa-komplain/*']) ? 'active' : '' }}">
                        <a href="{{ route('wa-komplain.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-brand-whatsapp"></i>
                            <div>Komplain WA</div>
                        </a>
                    </li>
                @endif

                <!-- Panduan -->
                @if (auth()->user()->hasRole(['super admin', 'gm administrasi']))
                    <li class="menu-item {{ request()->is(['panduan', 'panduan/*']) ? 'active' : '' }}">
                        <a href="{{ route('panduan.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-book"></i>
                            <div>Panduan</div>
                        </a>
                    </li>
                @endif
            @endif

        </ul>
    </div>

    <!-- Bottom Area: Logout Button -->
    <div class="p-3 border-t border-white/10 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-rose-300 hover:text-white bg-rose-500/20 hover:bg-rose-600 transition cursor-pointer">
                <i class="ti ti-logout text-sm"></i>
                <span>Keluar Sistem</span>
            </button>
        </form>
    </div>

</aside>

<!-- Click Handler for Submenu Accordion & Sidebar Scroll Position Restoration -->
<script>
    (function() {
        function scrollSidebarToActive() {
            const sidebarScroll = document.querySelector('#layout-menu-v2 .sidebar-scroll-container');
            if (!sidebarScroll) return;

            // Find all active menu items and pick the deepest/innermost active element
            const activeItems = sidebarScroll.querySelectorAll('.menu-item.active');
            let activeItem = null;
            if (activeItems.length > 0) {
                activeItem = activeItems[activeItems.length - 1];
            } else {
                activeItem = sidebarScroll.querySelector('.menu-link.active');
            }

            if (activeItem) {
                // Ensure all ancestor menu-items (accordions) are open
                let ancestor = activeItem.parentElement;
                while (ancestor && ancestor !== sidebarScroll) {
                    if (ancestor.classList && ancestor.classList.contains('menu-item')) {
                        ancestor.classList.add('open');
                    }
                    ancestor = ancestor.parentElement;
                }

                // Calculate exact target scrollTop to center the active menu item in the sidebar
                const containerRect = sidebarScroll.getBoundingClientRect();
                const itemRect = activeItem.getBoundingClientRect();
                const itemTopRelativeToScrollContent = itemRect.top - containerRect.top + sidebarScroll.scrollTop;
                const targetScroll = itemTopRelativeToScrollContent - (containerRect.height / 2) + (itemRect.height / 2);

                sidebarScroll.scrollTop = Math.max(0, targetScroll);
            } else {
                // If on a page without an active sidebar menu, restore last saved position
                const savedScroll = sessionStorage.getItem('sidebar_scroll_pos');
                if (savedScroll !== null) {
                    sidebarScroll.scrollTop = parseInt(savedScroll, 10);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sidebarScroll = document.querySelector('#layout-menu-v2 .sidebar-scroll-container');

            // Toggle submenu open/close
            const menuToggles = document.querySelectorAll('#layout-menu-v2 .menu-toggle');
            menuToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    const parentItem = this.closest('.menu-item');
                    if (parentItem) {
                        parentItem.classList.toggle('open');
                        if (sidebarScroll) {
                            sessionStorage.setItem('sidebar_scroll_pos', sidebarScroll.scrollTop);
                        }
                    }
                });
            });

            // Save scroll position on user scroll
            if (sidebarScroll) {
                sidebarScroll.addEventListener('scroll', function() {
                    sessionStorage.setItem('sidebar_scroll_pos', sidebarScroll.scrollTop);
                }, { passive: true });
            }

            // Execute scroll alignment immediately and once more after layout settles
            scrollSidebarToActive();
            requestAnimationFrame(scrollSidebarToActive);
            setTimeout(scrollSidebarToActive, 100);
        });
    })();
</script>
