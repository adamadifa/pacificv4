@extends('layouts.app')
@section('titlepage', 'Dashboard Grafik')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }

        .metric-card {
            border-radius: 0.75rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(149, 157, 165, 0.15) !important;
        }

        .metric-icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            font-size: 1.5rem;
        }

        .filter-badge {
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(2px);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: inherit;
        }

        .progress-slim {
            height: 6px;
            border-radius: 3px;
        }

        .apexcharts-tooltip {
            box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
            border-radius: 8px !important;
        }
    </style>
@endsection

@section('navigasi')
    @include('dashboard.navigasi')
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        @include('dashboard.welcome')
        
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                @include('layouts.navigation_dashboard')
            </ul>

            <div class="tab-content p-0" id="tab-content-main">
                <div class="tab-pane fade show active" role="tabpanel">

                    <!-- Filter Control Card -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <form id="formFilterGrafik" class="row g-3 align-items-end">
                                <!-- Mode Tampilan -->
                                <div class="col-lg-4 col-md-12 col-12">
                                    <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                        <i class="ti ti-layout-grid me-1"></i> Mode Tampilan
                                    </label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="mode" id="modeBulan" value="bulan" checked>
                                        <label class="btn btn-outline-primary btn-sm py-2" for="modeBulan">
                                            <i class="ti ti-calendar-event me-1"></i> By Bulan
                                        </label>

                                        <input type="radio" class="btn-check" name="mode" id="modeRangeBulan" value="range_bulan">
                                        <label class="btn btn-outline-primary btn-sm py-2" for="modeRangeBulan">
                                            <i class="ti ti-calendar-stats me-1"></i> Range Bulan
                                        </label>

                                        <input type="radio" class="btn-check" name="mode" id="modeTahun" value="tahun">
                                        <label class="btn btn-outline-primary btn-sm py-2" for="modeTahun">
                                            <i class="ti ti-chart-line me-1"></i> By Tahun
                                        </label>
                                    </div>
                                </div>

                                <!-- Filter Tahun (Mode: By Bulan) -->
                                <div class="col-lg-2 col-md-3 col-6" id="wrapTahun">
                                    <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                        <i class="ti ti-calendar me-1"></i> Tahun
                                    </label>
                                    <select name="tahun" id="tahun" class="form-select form-select-sm py-2">
                                        @for ($t = date('Y'); $t >= $start_year; $t--)
                                            <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Filter Bulan (Mode: By Bulan) -->
                                <div class="col-lg-2 col-md-3 col-6" id="wrapBulan">
                                    <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                        <i class="ti ti-calendar-stats me-1"></i> Bulan
                                    </label>
                                    <select name="bulan" id="bulan" class="form-select form-select-sm py-2">
                                        <option value="">Semua Bulan (Tren 12 Bulan)</option>
                                        @foreach ($list_bulan as $b)
                                            <option value="{{ $b['kode_bulan'] }}">{{ $b['nama_bulan'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Range Bulan (Mode: Range Bulan) -->
                                <div class="col-lg-4 col-md-6 col-12 d-none" id="wrapRangeBulan">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                                <i class="ti ti-calendar-down me-1"></i> Dari
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <select name="dari_bulan" id="dari_bulan" class="form-select py-2">
                                                    @foreach ($list_bulan as $b)
                                                        <option value="{{ $b['kode_bulan'] }}" {{ $b['kode_bulan'] == 1 ? 'selected' : '' }}>
                                                            {{ substr($b['nama_bulan'], 0, 3) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <select name="dari_tahun" id="dari_tahun" class="form-select py-2">
                                                    @for ($t = date('Y'); $t >= $start_year; $t--)
                                                        <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                                <i class="ti ti-calendar-up me-1"></i> Sampai
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <select name="sampai_bulan" id="sampai_bulan" class="form-select py-2">
                                                    @foreach ($list_bulan as $b)
                                                        <option value="{{ $b['kode_bulan'] }}" {{ $b['kode_bulan'] == (int) date('m') ? 'selected' : '' }}>
                                                            {{ substr($b['nama_bulan'], 0, 3) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <select name="sampai_tahun" id="sampai_tahun" class="form-select py-2">
                                                    @for ($t = date('Y'); $t >= $start_year; $t--)
                                                        <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter Cabang -->
                                <div class="col-lg-2 col-md-6 col-12">
                                    <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                        <i class="ti ti-building me-1"></i> Cabang
                                    </label>
                                    <select name="kode_cabang" id="kode_cabang" class="form-select form-select-sm py-2 select2">
                                        @hasanyrole($roles_show_cabang)
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $c)
                                                <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                                            @endforeach
                                        @else
                                            <option value="{{ auth()->user()->kode_cabang }}">{{ auth()->user()->kode_cabang }}</option>
                                        @endhasanyrole
                                    </select>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="col-lg-2 col-md-6 col-12 d-flex gap-2">
                                    <button type="button" id="btnTampilkan" class="btn btn-primary btn-sm py-2 w-100 fw-bold">
                                        <i class="ti ti-refresh me-1"></i> Muat Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- KPI Metric Cards -->
                    <div class="row g-3 mb-4">
                        <!-- Card 1: Total Cash In -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="card metric-card border-0 shadow-sm h-100 bg-primary text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-white text-primary fw-bold filter-badge" id="kpiPeriodeBadge">Tahun {{ date('Y') }}</span>
                                        <div class="metric-icon-box bg-white bg-opacity-25 text-white">
                                            <i class="ti ti-wallet"></i>
                                        </div>
                                    </div>
                                    <div class="text-white text-opacity-75 small fw-semibold">Total Cash In</div>
                                    <h4 class="text-white fw-bold my-1" id="kpiTotalCashIn">Rp 0</h4>
                                    <div class="small text-white text-opacity-75">
                                        <i class="ti ti-circle-check me-1"></i> Histori Bayar Penjualan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Rata-rata Cash In -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="card metric-card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-label-info fw-bold filter-badge" id="kpiRataRataLabel">Rata-rata / Bulan</span>
                                        <div class="metric-icon-box bg-label-info">
                                            <i class="ti ti-calculator"></i>
                                        </div>
                                    </div>
                                    <div class="text-muted small fw-semibold">Rata-rata Cash In</div>
                                    <h4 class="text-info fw-bold my-1" id="kpiRataRata">Rp 0</h4>
                                    <div class="small text-muted">
                                        <i class="ti ti-trending-up me-1"></i> Rerata periode aktif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Top Cabang -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="card metric-card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-label-success fw-bold filter-badge">Top Cabang</span>
                                        <div class="metric-icon-box bg-label-success">
                                            <i class="ti ti-building-skyscraper"></i>
                                        </div>
                                    </div>
                                    <div class="text-muted small fw-semibold" id="kpiTopCabangNama">-</div>
                                    <h4 class="text-success fw-bold my-1" id="kpiTopCabangTotal">Rp 0</h4>
                                    <div class="small text-muted">
                                        <i class="ti ti-award me-1"></i> Kontributor cabang terbesar
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Top Sales -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="card metric-card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-label-warning fw-bold filter-badge">Top Salesman</span>
                                        <div class="metric-icon-box bg-label-warning">
                                            <i class="ti ti-trophy"></i>
                                        </div>
                                    </div>
                                    <div class="text-muted small fw-semibold text-truncate" id="kpiTopSalesNama">-</div>
                                    <h4 class="text-warning fw-bold my-1" id="kpiTopSalesTotal">Rp 0</h4>
                                    <div class="small text-muted text-truncate" id="kpiTopSalesCabang">
                                        <i class="ti ti-user-star me-1"></i> Salesman berprestasi
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 1: Tren Cash In Keseluruhan -->
                    <div class="card border-0 shadow-sm mb-4 position-relative" id="cardTrenChart">
                        <div class="loading-overlay d-none" id="loadingTren">
                            <div class="spinner-border text-primary" role="status"></div>
                            <span class="small fw-semibold mt-2 text-muted">Memuat grafik tren...</span>
                        </div>
                        <div class="card-header d-flex justify-content-between align-items-center pb-0">
                            <div>
                                <h5 class="card-title mb-1 fw-bold" id="titleTrenChart">
                                    <i class="ti ti-chart-area-line me-1 text-primary"></i> Tren Cash In Keseluruhan
                                </h5>
                                <p class="text-muted small mb-0">Perkembangan realisasi Cash In berdasarkan pembayaran penjualan</p>
                            </div>
                            <span class="badge bg-label-primary px-3 py-2 fw-semibold" id="badgeSubTitleTren">
                                <i class="ti ti-calendar me-1"></i> Real-time
                            </span>
                        </div>
                        <div class="card-body pt-2">
                            <div id="chartTrenCashIn" style="min-height: 340px;"></div>
                        </div>
                    </div>

                    <!-- Chart 2: Tren Cash In Per Salesman (Line Chart) -->
                    <div class="card border-0 shadow-sm mb-4 position-relative" id="cardSalesLine">
                        <div class="loading-overlay d-none" id="loadingSalesLine">
                            <div class="spinner-border text-warning" role="status"></div>
                            <span class="small fw-semibold mt-2 text-muted">Memuat grafik tren per sales...</span>
                        </div>
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 pb-2">
                            <div>
                                <h5 class="card-title mb-1 fw-bold">
                                    <i class="ti ti-chart-line me-1 text-warning"></i> Tren Cash In Per Salesman
                                </h5>
                                <p class="text-muted small mb-0" id="subTitleSalesLine">Perbandingan tren realisasi Cash In antar salesman sepanjang periode</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="small text-muted fw-semibold me-1 d-none d-sm-inline">Tampilkan:</label>
                                <select id="limit_sales" class="form-select form-select-sm py-1 fw-semibold" style="width: 140px; font-size: 12px;">
                                    <option value="5">Top 5 Sales</option>
                                    <option value="10" selected>Top 10 Sales</option>
                                    <option value="15">Top 15 Sales</option>
                                    <option value="all">Semua Sales</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex align-items-center mb-2 text-muted small">
                                <i class="ti ti-info-circle me-1 text-primary"></i>
                                <span>Tip: Klik nama salesman pada legenda grafik di bawah untuk menyembunyikan atau menampilkan garis.</span>
                            </div>
                            <div id="chartSalesCashIn" style="min-height: 380px;"></div>
                        </div>
                    </div>

                    <!-- Row: Detail & Breakdown (2 Kolom Berdampingan) -->
                    <div class="row g-4">
                        <!-- Col-lg-6: Cash In Cabang -->
                        <div class="col-lg-6 col-12">
                            <div class="card border-0 shadow-sm h-100 position-relative" id="cardCabang">
                                <div class="loading-overlay d-none" id="loadingCabang">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <span class="small fw-semibold mt-2 text-muted">Memuat data cabang...</span>
                                </div>
                                <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold">
                                            <i class="ti ti-building me-1 text-success"></i> Cash In Cabang
                                        </h5>
                                        <small class="text-muted">Perbandingan Cash In per wilayah cabang</small>
                                    </div>
                                    <ul class="nav nav-pills nav-pills-sm" role="tablist">
                                        <li class="nav-item">
                                            <button class="nav-link active btn-sm py-1 px-2" data-bs-toggle="pill" data-bs-target="#tabCabangGrafik">
                                                <i class="ti ti-chart-bar me-1"></i> Grafik
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link btn-sm py-1 px-2" data-bs-toggle="pill" data-bs-target="#tabCabangTabel">
                                                <i class="ti ti-table me-1"></i> Tabel
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body p-3">
                                    <div class="tab-content p-0 border-0 shadow-none">
                                        <div class="tab-pane fade show active" id="tabCabangGrafik">
                                            <div id="chartCabangCashIn" style="min-height: 380px;"></div>
                                        </div>
                                        <div class="tab-pane fade" id="tabCabangTabel">
                                            <div class="table-responsive" style="max-height: 380px;">
                                                <table class="table table-hover table-sm align-middle mb-0">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th style="width: 40px;">#</th>
                                                            <th>Cabang</th>
                                                            <th class="text-end">Total Cash In</th>
                                                            <th style="width: 120px;" class="text-center">Kontribusi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbodyCabang">
                                                        <!-- Injected via AJAX -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Col-lg-6: Leaderboard Salesman -->
                        <div class="col-lg-6 col-12">
                            <div class="card border-0 shadow-sm h-100 position-relative" id="cardSalesTable">
                                <div class="loading-overlay d-none" id="loadingSalesTable">
                                    <div class="spinner-border text-warning" role="status"></div>
                                    <span class="small fw-semibold mt-2 text-muted">Memuat data ranking sales...</span>
                                </div>
                                <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold">
                                            <i class="ti ti-trophy me-1 text-warning"></i> Leaderboard Salesman
                                        </h5>
                                        <small class="text-muted">Peringkat kontribusi total perolehan salesman</small>
                                    </div>
                                    <span class="badge bg-label-warning px-2 py-1 small fw-semibold">
                                        <i class="ti ti-award me-1"></i> Ranking Total
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive" style="max-height: 420px;">
                                        <table class="table table-hover table-sm align-middle mb-0">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 45px;" class="text-center">Rank</th>
                                                    <th>Salesman</th>
                                                    <th>Cabang</th>
                                                    <th class="text-end">Total Cash In</th>
                                                    <th style="width: 100px;" class="text-center">Kontribusi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodySales">
                                                <!-- Injected via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
$(function() {
    // Format helper function
    function formatRupiah(val) {
        if (!val || isNaN(val)) return 'Rp 0';
        return 'Rp ' + Math.round(val).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function formatShortRupiah(val) {
        if (Math.abs(val) >= 1.0e+12) {
            return (val / 1.0e+12).toFixed(1) + " T";
        } else if (Math.abs(val) >= 1.0e+9) {
            return (val / 1.0e+9).toFixed(1) + " M";
        } else if (Math.abs(val) >= 1.0e+6) {
            return (val / 1.0e+6).toFixed(1) + " Jt";
        } else if (Math.abs(val) >= 1.0e+3) {
            return (val / 1.0e+3).toFixed(1) + " Rb";
        }
        return val;
    }

    // Toggle wrap filter when switching mode
    $('input[name="mode"]').change(function() {
        const mode = $(this).val();
        if (mode === 'tahun') {
            $('#wrapTahun').addClass('d-none');
            $('#wrapBulan').addClass('d-none');
            $('#wrapRangeBulan').addClass('d-none');
        } else if (mode === 'range_bulan') {
            $('#wrapTahun').addClass('d-none');
            $('#wrapBulan').addClass('d-none');
            $('#wrapRangeBulan').removeClass('d-none');
        } else {
            $('#wrapTahun').removeClass('d-none');
            $('#wrapBulan').removeClass('d-none');
            $('#wrapRangeBulan').addClass('d-none');
        }
        loadDataGrafik();
    });

    // Chart instances
    let chartTren = null;
    let chartCabang = null;
    let chartSales = null;

    // Load data from server
    function loadDataGrafik() {
        const mode = $('input[name="mode"]:checked').val();
        const tahun = $('#tahun').val();
        const bulan = $('#bulan').val();
        const dari_bulan = $('#dari_bulan').val();
        const dari_tahun = $('#dari_tahun').val();
        const sampai_bulan = $('#sampai_bulan').val();
        const sampai_tahun = $('#sampai_tahun').val();
        const kode_cabang = $('#kode_cabang').val();
        const limit_sales = $('#limit_sales').val() || '15';

        // Show loading overlays
        $('#loadingTren, #loadingCabang, #loadingSalesLine, #loadingSalesTable').removeClass('d-none');
        $('#btnTampilkan').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Memuat...');

        $.ajax({
            type: "POST",
            url: "{{ route('dashboard.grafik.getcashin') }}",
            data: {
                _token: "{{ csrf_token() }}",
                mode: mode,
                tahun: tahun,
                bulan: bulan,
                dari_bulan: dari_bulan,
                dari_tahun: dari_tahun,
                sampai_bulan: sampai_bulan,
                sampai_tahun: sampai_tahun,
                kode_cabang: kode_cabang,
                limit_sales: limit_sales
            },
            dataType: "json",
            success: function(res) {
                if (res.status) {
                    renderKPI(res.kpi);
                    renderChartTren(res.tren);
                    renderChartCabang(res.cabang);
                    renderChartSales(res.sales);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading grafik data:", error);
                toastr.error("Gagal memuat data grafik. Silakan coba beberapa saat lagi.");
            },
            complete: function() {
                $('#loadingTren, #loadingCabang, #loadingSalesLine, #loadingSalesTable').addClass('d-none');
                $('#btnTampilkan').prop('disabled', false).html('<i class="ti ti-refresh me-1"></i> Muat Data');
            }
        });
    }

    // Render KPI metrics
    function renderKPI(kpi) {
        $('#kpiPeriodeBadge').text(kpi.periode_title);
        $('#kpiTotalCashIn').text(kpi.total_cashin_formatted);
        $('#kpiRataRataLabel').text(kpi.rata_rata_label);
        $('#kpiRataRata').text(kpi.rata_rata_formatted);

        if (kpi.top_cabang) {
            $('#kpiTopCabangNama').text(kpi.top_cabang.nama_cabang);
            $('#kpiTopCabangTotal').text(kpi.top_cabang.total_formatted);
        } else {
            $('#kpiTopCabangNama').text('-');
            $('#kpiTopCabangTotal').text('Rp 0');
        }

        if (kpi.top_sales) {
            $('#kpiTopSalesNama').text(kpi.top_sales.nama_salesman);
            $('#kpiTopSalesCabang').html('<i class="ti ti-map-pin me-1"></i>' + kpi.top_sales.nama_cabang);
            $('#kpiTopSalesTotal').text(kpi.top_sales.total_formatted);
        } else {
            $('#kpiTopSalesNama').text('-');
            $('#kpiTopSalesCabang').text('Salesman berprestasi');
            $('#kpiTopSalesTotal').text('Rp 0');
        }
    }

    // Render Chart 1: Tren
    function renderChartTren(data) {
        $('#titleTrenChart').text(data.title);
        $('#badgeSubTitleTren').html('<i class="ti ti-chart-dots me-1"></i> Realisasi Cash In');

        const options = {
            series: [{
                name: 'Cash In',
                data: data.series
            }],
            chart: {
                type: 'area',
                height: 340,
                fontFamily: 'inherit',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 700
                }
            },
            colors: ['#7367f0'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.05,
                    stops: [0, 95, 100]
                }
            },
            markers: {
                size: 4,
                colors: ['#7367f0'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                categories: data.categories,
                labels: {
                    style: {
                        colors: '#a1acb8',
                        fontSize: '12px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return formatShortRupiah(val);
                    },
                    style: {
                        colors: '#a1acb8',
                        fontSize: '12px'
                    }
                }
            },
            grid: {
                borderColor: '#e7eaf3',
                strokeDashArray: 4,
                padding: {
                    top: 10,
                    right: 20,
                    bottom: 0,
                    left: 10
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return formatRupiah(val);
                    }
                }
            }
        };

        if (chartTren) {
            chartTren.updateOptions(options);
        } else {
            chartTren = new ApexCharts(document.querySelector("#chartTrenCashIn"), options);
            chartTren.render();
        }
    }

    // Render Chart 2: Cabang
    function renderChartCabang(data) {
        // Render Tabel
        let tbody = '';
        if (data.list && data.list.length > 0) {
            data.list.forEach((item, idx) => {
                tbody += `
                    <tr>
                        <td class="fw-bold text-muted">${idx + 1}</td>
                        <td class="fw-semibold text-heading">${item.nama_cabang}</td>
                        <td class="text-end fw-bold text-primary">${item.formatted}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small fw-semibold">${item.persentase}%</span>
                            </div>
                            <div class="progress progress-slim">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: ${item.persentase}%"></div>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            tbody = '<tr><td colspan="4" class="text-center py-4 text-muted">Tidak ada data ditemukan</td></tr>';
        }
        $('#tbodyCabang').html(tbody);

        // Render ApexChart
        const options = {
            series: [{
                name: 'Cash In',
                data: data.series
            }],
            chart: {
                type: 'bar',
                height: 380,
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 5,
                    columnWidth: '55%',
                    distributed: false,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            colors: ['#28c76f'],
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.categories,
                labels: {
                    rotate: -45,
                    style: {
                        colors: '#a1acb8',
                        fontSize: '11px'
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return formatShortRupiah(val);
                    },
                    style: {
                        colors: '#a1acb8',
                        fontSize: '12px'
                    }
                }
            },
            grid: {
                borderColor: '#e7eaf3',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return formatRupiah(val);
                    }
                }
            }
        };

        if (chartCabang) {
            chartCabang.updateOptions(options);
        } else {
            chartCabang = new ApexCharts(document.querySelector("#chartCabangCashIn"), options);
            chartCabang.render();
        }
    }

    // Render Chart 3: Sales (Multi-Line Chart & Leaderboard Table)
    function renderChartSales(data) {
        if (data.is_limited) {
            $('#subTitleSalesLine').text('Tren pergerakan realisasi Cash In top salesman sepanjang periode');
        } else {
            $('#subTitleSalesLine').text('Tren pergerakan realisasi Cash In salesman pada cabang sepanjang periode');
        }

        // Render Tabel Leaderboard
        let tbody = '';
        if (data.list && data.list.length > 0) {
            data.list.forEach((item, idx) => {
                let rankBadge = '';
                if (idx === 0) {
                    rankBadge = '<span class="badge bg-warning text-white rounded-pill px-2">🥇 1</span>';
                } else if (idx === 1) {
                    rankBadge = '<span class="badge bg-secondary text-white rounded-pill px-2">🥈 2</span>';
                } else if (idx === 2) {
                    rankBadge = '<span class="badge bg-label-warning rounded-pill px-2">🥉 3</span>';
                } else {
                    rankBadge = `<span class="fw-bold text-muted">${idx + 1}</span>`;
                }

                tbody += `
                    <tr>
                        <td class="text-center">${rankBadge}</td>
                        <td class="fw-semibold text-heading">${item.nama_salesman}</td>
                        <td class="small text-muted"><span class="badge bg-label-secondary">${item.nama_cabang}</span></td>
                        <td class="text-end fw-bold text-warning">${item.formatted}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small fw-semibold">${item.persentase}%</span>
                            </div>
                            <div class="progress progress-slim">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: ${item.persentase}%"></div>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            tbody = '<tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data ditemukan</td></tr>';
        }
        $('#tbodySales').html(tbody);

        // Render ApexChart Multi-Line untuk Sales
        const lineSeries = (data.line_series && data.line_series.length > 0) ? data.line_series : [];
        const options = {
            series: lineSeries,
            chart: {
                type: 'line',
                height: 380,
                fontFamily: 'inherit',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 700
                }
            },
            colors: [
                '#7367f0', '#28c76f', '#ff9f43', '#00cfe8', '#ea5455',
                '#6610f2', '#e83e8c', '#fd7e14', '#20c997', '#17a2b8',
                '#5a8dee', '#39da8a', '#ff5b5c', '#fdac41', '#475f7b'
            ],
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            markers: {
                size: 4,
                strokeWidth: 2,
                strokeColors: '#fff',
                hover: {
                    size: 7
                }
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'left',
                floating: false,
                fontSize: '12px',
                fontWeight: 500,
                markers: {
                    radius: 12,
                    width: 10,
                    height: 10
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 4
                }
            },
            xaxis: {
                categories: data.categories,
                labels: {
                    style: {
                        colors: '#a1acb8',
                        fontSize: '12px'
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return formatShortRupiah(val);
                    },
                    style: {
                        colors: '#a1acb8',
                        fontSize: '12px'
                    }
                }
            },
            grid: {
                borderColor: '#e7eaf3',
                strokeDashArray: 4,
                padding: { top: 10, right: 20, bottom: 0, left: 10 }
            },
            tooltip: {
                theme: 'light',
                shared: true,
                intersect: false,
                y: {
                    formatter: function(val) {
                        return formatRupiah(val);
                    }
                }
            }
        };

        if (chartSales) {
            chartSales.updateOptions(options);
        } else {
            chartSales = new ApexCharts(document.querySelector("#chartSalesCashIn"), options);
            chartSales.render();
        }
    }

    // Event listener tombol & input change
    $('#btnTampilkan').click(function() {
        loadDataGrafik();
    });

    $('#tahun, #bulan, #dari_bulan, #dari_tahun, #sampai_bulan, #sampai_tahun, #kode_cabang, #limit_sales').change(function() {
        loadDataGrafik();
    });

    // Inisialisasi awal
    loadDataGrafik();
});
</script>
@endpush
