@extends('layouts.app')
@section('titlepage', 'Dashboard Grafik Qty Produk')

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
                            <form id="formFilterGrafikQty" class="row g-3 align-items-end">
                                <!-- Mode Tampilan -->
                                <div class="col-lg-2 col-md-4 col-12">
                                    <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                        <i class="ti ti-layout-grid me-1"></i> Mode
                                    </label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="mode" id="modeBulan" value="bulan" checked>
                                        <label class="btn btn-outline-primary btn-sm py-2" for="modeBulan">
                                            By Bulan
                                        </label>

                                        <input type="radio" class="btn-check" name="mode" id="modeTahun" value="tahun">
                                        <label class="btn btn-outline-primary btn-sm py-2" for="modeTahun">
                                            By Tahun
                                        </label>
                                    </div>
                                </div>

                                <!-- Filter Produk -->
                                <div class="col-lg-3 col-md-4 col-12">
                                    <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                        <i class="ti ti-box me-1"></i> Produk
                                    </label>
                                    <select name="kode_produk" id="kode_produk" class="form-select form-select-sm py-2 select2">
                                        <option value="">Semua Produk</option>
                                        @foreach ($produk as $p)
                                            <option value="{{ $p->kode_produk }}">{{ $p->nama_produk }} ({{ $p->satuan }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Tahun -->
                                <div class="col-lg-2 col-md-4 col-6" id="wrapTahun">
                                    <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                        <i class="ti ti-calendar me-1"></i> Tahun
                                    </label>
                                    <select name="tahun" id="tahun" class="form-select form-select-sm py-2">
                                        @for ($t = date('Y'); $t >= $start_year; $t--)
                                            <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Filter Bulan -->
                                <div class="col-lg-2 col-md-4 col-6" id="wrapBulan">
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

                                <!-- Filter Cabang -->
                                <div class="col-lg-2 col-md-4 col-6">
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
                                <div class="col-lg-1 col-md-4 col-6">
                                    <button type="button" id="btnTampilkan" class="btn btn-primary btn-sm py-2 w-100 fw-bold">
                                        <i class="ti ti-refresh"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- KPI Metric Cards -->
                    <div class="row g-3 mb-4">
                        <!-- Card 1: Total Qty -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="card metric-card border-0 shadow-sm h-100 bg-primary text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-white text-primary fw-bold filter-badge" id="kpiPeriodeBadge">Tahun {{ date('Y') }}</span>
                                        <div class="metric-icon-box bg-white bg-opacity-25 text-white">
                                            <i class="ti ti-packages"></i>
                                        </div>
                                    </div>
                                    <div class="text-white text-opacity-75 small fw-semibold">Total Qty Penjualan</div>
                                    <h4 class="text-white fw-bold my-1" id="kpiTotalQty">0 Dus</h4>
                                    <div class="small text-white text-opacity-75 text-truncate" id="kpiNamaProduk">
                                        <i class="ti ti-box me-1"></i> Semua Produk
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Rata-rata Qty -->
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="card metric-card border-0 shadow-sm h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-label-info fw-bold filter-badge" id="kpiRataRataLabel">Rata-rata / Bulan</span>
                                        <div class="metric-icon-box bg-label-info">
                                            <i class="ti ti-calculator"></i>
                                        </div>
                                    </div>
                                    <div class="text-muted small fw-semibold">Rata-rata Penjualan</div>
                                    <h4 class="text-info fw-bold my-1" id="kpiRataRata">0 Dus</h4>
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
                                    <h4 class="text-success fw-bold my-1" id="kpiTopCabangTotal">0 Dus</h4>
                                    <div class="small text-muted">
                                        <i class="ti ti-award me-1"></i> Volume penjualan tertinggi
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Top Salesman -->
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
                                    <h4 class="text-warning fw-bold my-1" id="kpiTopSalesTotal">0 Dus</h4>
                                    <div class="small text-muted text-truncate" id="kpiTopSalesCabang">
                                        <i class="ti ti-user-star me-1"></i> Penjualan Qty terbanyak
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 1: Tren Qty Penjualan -->
                    <div class="card border-0 shadow-sm mb-4 position-relative" id="cardTrenChart">
                        <div class="loading-overlay d-none" id="loadingTren">
                            <div class="spinner-border text-primary" role="status"></div>
                            <span class="small fw-semibold mt-2 text-muted">Memuat grafik tren...</span>
                        </div>
                        <div class="card-header d-flex justify-content-between align-items-center pb-0">
                            <div>
                                <h5 class="card-title mb-1 fw-bold" id="titleTrenChart">Tren Qty Produk</h5>
                                <p class="text-muted small mb-0">Perkembangan realisasi volume penjualan produk (satuan Dus)</p>
                            </div>
                            <span class="badge bg-label-primary px-3 py-2 fw-semibold" id="badgeSubTitleTren">
                                <i class="ti ti-box me-1"></i> Real-time Satuan Dus
                            </span>
                        </div>
                        <div class="card-body pt-2">
                            <div id="chartTrenQty" style="min-height: 340px;"></div>
                        </div>
                    </div>

                    <!-- Row: Chart Cabang & Chart Sales -->
                    <div class="row g-4 mb-4">
                        <!-- Chart 2: Qty per Cabang -->
                        <div class="col-lg-6 col-12">
                            <div class="card border-0 shadow-sm h-100 position-relative" id="cardCabang">
                                <div class="loading-overlay d-none" id="loadingCabang">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <span class="small fw-semibold mt-2 text-muted">Memuat data cabang...</span>
                                </div>
                                <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold">
                                            <i class="ti ti-building me-1 text-primary"></i> Qty per Cabang
                                        </h5>
                                        <small class="text-muted">Perbandingan volume penjualan produk antar cabang</small>
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
                                            <div id="chartCabangQty" style="min-height: 380px;"></div>
                                        </div>
                                        <div class="tab-pane fade" id="tabCabangTabel">
                                            <div class="table-responsive" style="max-height: 480px;">
                                                <table class="table table-hover table-sm align-middle mb-0">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th style="width: 40px;">#</th>
                                                            <th>Cabang</th>
                                                            <th class="text-end">Total Qty (Dus)</th>
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

                        <!-- Chart 3: Qty Per Sales -->
                        <div class="col-lg-6 col-12">
                            <div class="card border-0 shadow-sm h-100 position-relative" id="cardSales">
                                <div class="loading-overlay d-none" id="loadingSales">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <span class="small fw-semibold mt-2 text-muted">Memuat data sales...</span>
                                </div>
                                <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold">
                                            <i class="ti ti-user-check me-1 text-primary"></i> Qty Per Sales
                                        </h5>
                                        <small class="text-muted" id="subTitleSales">Salesman volume penjualan</small>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <select id="limit_sales" class="form-select form-select-sm py-1" style="width: 125px; font-size: 12px;">
                                            <option value="15" selected>Top 15 Sales</option>
                                            <option value="30">Top 30 Sales</option>
                                            <option value="50">Top 50 Sales</option>
                                            <option value="all">Semua Sales</option>
                                        </select>
                                        <ul class="nav nav-pills nav-pills-sm" role="tablist">
                                            <li class="nav-item">
                                                <button class="nav-link active btn-sm py-1 px-2" data-bs-toggle="pill" data-bs-target="#tabSalesGrafik">
                                                    <i class="ti ti-chart-bar-popular me-1"></i> Grafik
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link btn-sm py-1 px-2" data-bs-toggle="pill" data-bs-target="#tabSalesTabel">
                                                    <i class="ti ti-table me-1"></i> Tabel
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="tab-content p-0 border-0 shadow-none">
                                        <div class="tab-pane fade show active" id="tabSalesGrafik">
                                            <div style="max-height: 480px; overflow-y: auto; overflow-x: hidden;">
                                                <div id="chartSalesQty"></div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tabSalesTabel">
                                            <div class="table-responsive" style="max-height: 480px;">
                                                <table class="table table-hover table-sm align-middle mb-0">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th style="width: 40px;">#</th>
                                                            <th>Salesman</th>
                                                            <th>Cabang</th>
                                                            <th class="text-end">Total Qty (Dus)</th>
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

                    <!-- Row: Breakdown Produk (Ranking Produk Terlaris) -->
                    <div class="card border-0 shadow-sm" id="cardProdukBreakdown">
                        <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                            <div>
                                <h5 class="card-title mb-0 fw-bold">
                                    <i class="ti ti-box-seam me-1 text-primary"></i> Kontribusi Penjualan per Produk
                                </h5>
                                <small class="text-muted">Daftar produk terlaris berdasarkan total quantity (satuan Dus)</small>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-lg-6 col-12 mb-3 mb-lg-0">
                                    <div id="chartProdukQty" style="min-height: 350px;"></div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="table-responsive" style="max-height: 350px;">
                                        <table class="table table-hover table-sm align-middle mb-0">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 40px;">#</th>
                                                    <th>Nama Produk</th>
                                                    <th class="text-end">Total Qty (Dus)</th>
                                                    <th style="width: 120px;" class="text-center">Persentase</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyProduk">
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
    function formatNumber(val, decimals = 1) {
        if (!val || isNaN(val)) return '0';
        return parseFloat(val).toLocaleString('id-ID', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
    }

    function formatShortQty(val) {
        if (Math.abs(val) >= 1.0e+6) {
            return (val / 1.0e+6).toFixed(1) + " Jt Dus";
        } else if (Math.abs(val) >= 1.0e+3) {
            return (val / 1.0e+3).toFixed(1) + " Rb Dus";
        }
        return Math.round(val) + " Dus";
    }

    // Toggle wrap filter when switching mode
    $('input[name="mode"]').change(function() {
        const mode = $(this).val();
        if (mode === 'tahun') {
            $('#wrapTahun').addClass('d-none');
            $('#wrapBulan').addClass('d-none');
        } else {
            $('#wrapTahun').removeClass('d-none');
            $('#wrapBulan').removeClass('d-none');
        }
        loadDataGrafikQty();
    });

    // Chart instances
    let chartTren = null;
    let chartCabang = null;
    let chartSales = null;
    let chartProduk = null;

    // Load data from server
    function loadDataGrafikQty() {
        const mode = $('input[name="mode"]:checked').val();
        const tahun = $('#tahun').val();
        const bulan = $('#bulan').val();
        const kode_cabang = $('#kode_cabang').val();
        const kode_produk = $('#kode_produk').val();
        const limit_sales = $('#limit_sales').val() || '15';

        // Show loading overlays
        $('#loadingTren, #loadingCabang, #loadingSales').removeClass('d-none');
        $('#btnTampilkan').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            type: "POST",
            url: "{{ route('dashboard.grafik.getqty') }}",
            data: {
                _token: "{{ csrf_token() }}",
                mode: mode,
                tahun: tahun,
                bulan: bulan,
                kode_cabang: kode_cabang,
                kode_produk: kode_produk,
                limit_sales: limit_sales
            },
            dataType: "json",
            success: function(res) {
                if (res.status) {
                    renderKPI(res.kpi);
                    renderChartTren(res.tren);
                    renderChartCabang(res.cabang);
                    renderChartSales(res.sales);
                    renderChartProduk(res.produk);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading grafik qty data:", error);
                toastr.error("Gagal memuat data grafik Qty produk.");
            },
            complete: function() {
                $('#loadingTren, #loadingCabang, #loadingSales').addClass('d-none');
                $('#btnTampilkan').prop('disabled', false).html('<i class="ti ti-refresh"></i>');
            }
        });
    }

    // Render KPI metrics
    function renderKPI(kpi) {
        $('#kpiPeriodeBadge').text(kpi.periode_title);
        $('#kpiTotalQty').text(kpi.total_qty_formatted);
        $('#kpiRataRataLabel').text(kpi.rata_rata_label);
        $('#kpiRataRata').text(kpi.rata_rata_formatted);
        $('#kpiNamaProduk').html('<i class="ti ti-box me-1"></i>' + kpi.nama_produk_terpilih);

        if (kpi.top_cabang) {
            $('#kpiTopCabangNama').text(kpi.top_cabang.nama_cabang);
            $('#kpiTopCabangTotal').text(kpi.top_cabang.total_formatted);
        } else {
            $('#kpiTopCabangNama').text('-');
            $('#kpiTopCabangTotal').text('0 Dus');
        }

        if (kpi.top_sales) {
            $('#kpiTopSalesNama').text(kpi.top_sales.nama_salesman);
            $('#kpiTopSalesCabang').html('<i class="ti ti-map-pin me-1"></i>' + kpi.top_sales.nama_cabang);
            $('#kpiTopSalesTotal').text(kpi.top_sales.total_formatted);
        } else {
            $('#kpiTopSalesNama').text('-');
            $('#kpiTopSalesCabang').text('Salesman berprestasi');
            $('#kpiTopSalesTotal').text('0 Dus');
        }
    }

    // Render Chart 1: Tren Qty
    function renderChartTren(data) {
        $('#titleTrenChart').text(data.title);

        const options = {
            series: data.series,
            chart: {
                type: 'line',
                height: 380,
                fontFamily: 'inherit',
                toolbar: {
                    show: true,
                    tools: { download: true, selection: false, zoom: true, zoomin: true, zoomout: true, pan: true, reset: true }
                },
                animations: { enabled: true, easing: 'easeinout', speed: 600 }
            },
            colors: [
                '#7367f0', '#28c76f', '#ea5455', '#ff9f43', '#00cfe8',
                '#6c757d', '#5a45b8', '#00b4d8', '#f72585', '#4361ee',
                '#4cc9f0', '#06d6a0', '#ffd166', '#ef476f', '#118ab2', '#073b4c'
            ],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2.5 },
            markers: {
                size: 3,
                strokeWidth: 2,
                hover: { size: 6 }
            },
            xaxis: {
                categories: data.categories,
                labels: { style: { colors: '#a1acb8', fontSize: '12px' } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) { return formatShortQty(val); },
                    style: { colors: '#a1acb8', fontSize: '12px' }
                }
            },
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '12px',
                itemMargin: { horizontal: 8, vertical: 4 }
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
                    formatter: function(val) { return formatNumber(val, 1) + ' Dus'; }
                }
            }
        };

        if (chartTren) {
            chartTren.updateOptions(options);
        } else {
            chartTren = new ApexCharts(document.querySelector("#chartTrenQty"), options);
            chartTren.render();
        }
    }

    // Render Chart 2: Cabang Qty
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
                name: 'Qty (Dus)',
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
                    distributed: false
                }
            },
            colors: ['#28c76f'],
            dataLabels: { enabled: false },
            xaxis: {
                categories: data.categories,
                labels: {
                    rotate: -45,
                    style: { colors: '#a1acb8', fontSize: '11px' }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) { return formatShortQty(val); },
                    style: { colors: '#a1acb8', fontSize: '12px' }
                }
            },
            grid: { borderColor: '#e7eaf3', strokeDashArray: 4 },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) { return formatNumber(val, 1) + ' Dus'; }
                }
            }
        };

        if (chartCabang) {
            chartCabang.updateOptions(options);
        } else {
            chartCabang = new ApexCharts(document.querySelector("#chartCabangQty"), options);
            chartCabang.render();
        }
    }

    // Render Chart 3: Sales Qty
    function renderChartSales(data) {
        if (data.is_limited) {
            $('#subTitleSales').text('Top Salesman (Pilih Cabang untuk semua sales)');
        } else {
            $('#subTitleSales').text('Semua Salesman pada filter cabang');
        }

        // Render Tabel
        let tbody = '';
        if (data.list && data.list.length > 0) {
            data.list.forEach((item, idx) => {
                tbody += `
                    <tr>
                        <td class="fw-bold text-muted">${idx + 1}</td>
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

        // Render ApexChart Horizontal
        const dynamicHeight = Math.max(380, data.categories.length * 28);
        const options = {
            series: [{
                name: 'Qty (Dus)',
                data: data.series
            }],
            chart: {
                type: 'bar',
                height: dynamicHeight,
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4,
                    barHeight: '65%'
                }
            },
            colors: ['#ff9f43'],
            dataLabels: { enabled: false },
            xaxis: {
                categories: data.categories,
                labels: {
                    formatter: function(val) { return formatShortQty(val); },
                    style: { colors: '#a1acb8', fontSize: '11px' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#566a7f', fontSize: '12px' }
                }
            },
            grid: { borderColor: '#e7eaf3', strokeDashArray: 4 },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) { return formatNumber(val, 1) + ' Dus'; }
                }
            }
        };

        if (chartSales) {
            chartSales.updateOptions(options);
        } else {
            chartSales = new ApexCharts(document.querySelector("#chartSalesQty"), options);
            chartSales.render();
        }
    }

    // Render Chart 4: Breakdown Produk
    function renderChartProduk(data) {
        let tbody = '';
        if (data.list && data.list.length > 0) {
            data.list.forEach((item, idx) => {
                tbody += `
                    <tr>
                        <td class="fw-bold text-muted">${idx + 1}</td>
                        <td class="fw-semibold text-heading">${item.nama_produk}</td>
                        <td class="text-end fw-bold text-info">${item.formatted}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small fw-semibold">${item.persentase}%</span>
                            </div>
                            <div class="progress progress-slim">
                                <div class="progress-bar bg-info" role="progressbar" style="width: ${item.persentase}%"></div>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            tbody = '<tr><td colspan="4" class="text-center py-4 text-muted">Tidak ada data ditemukan</td></tr>';
        }
        $('#tbodyProduk').html(tbody);

        // Donut / Bar chart for Produk Breakdown
        const top5 = data.list.slice(0, 6);
        const donutSeries = top5.map(i => i.total);
        const donutLabels = top5.map(i => i.nama_produk);

        const options = {
            series: donutSeries,
            labels: donutLabels,
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: 'inherit'
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + "%";
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '12px'
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return formatNumber(val, 1) + " Dus";
                    }
                }
            }
        };

        if (chartProduk) {
            chartProduk.updateOptions(options);
        } else {
            chartProduk = new ApexCharts(document.querySelector("#chartProdukQty"), options);
            chartProduk.render();
        }
    }

    // Event listener tombol & input change
    $('#btnTampilkan').click(function() {
        loadDataGrafikQty();
    });

    $('#tahun, #bulan, #kode_cabang, #kode_produk, #limit_sales').change(function() {
        loadDataGrafikQty();
    });

    // Inisialisasi awal
    loadDataGrafikQty();
});
</script>
@endpush
