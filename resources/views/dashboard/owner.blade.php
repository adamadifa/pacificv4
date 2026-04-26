@extends('layouts.app')
@section('titlepage', 'Dashboard Owner')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Dashboard Owner</h4>
            <small class="text-muted">Ringkasan keuangan harian.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-dashboard me-1"></i>Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Owner</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-12">
        {{-- Filter Tanggal --}}
        <form action="{{ URL::current() }}" method="GET">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-10 col-md-9 col-sm-12">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                <input type="text" name="tanggal" id="tanggal" class="form-control flatpickr-date" 
                                    placeholder="Pilih Tanggal" value="{{ $tanggal }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Widget Summary --}}
        <div class="row">
            {{-- Saldo Awal --}}
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="card cursor-pointer btn-detail-saldoawal">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text text-muted mb-1">Total Saldo</p>
                                <h4 class="card-title mb-1">Rp {{ formatAngka($total_saldo_awal) }}</h4>
                            </div>
                            <div class="avatar bg-label-primary rounded p-2">
                                <i class="ti ti-wallet ti-md"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penerimaan --}}
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="card cursor-pointer btn-detail-mutasi" data-jenis="K">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text text-muted mb-1">Total Penerimaan</p>
                                <h4 class="card-title mb-1 text-success">Rp {{ formatAngka($total_penerimaan) }}</h4>
                            </div>
                            <div class="avatar bg-label-success rounded p-2">
                                <i class="ti ti-arrow-up-right ti-md"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pengeluaran --}}
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="card cursor-pointer btn-detail-mutasi" data-jenis="D">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text text-muted mb-1">Total Pengeluaran</p>
                                <h4 class="card-title mb-1 text-danger">Rp {{ formatAngka($total_pengeluaran) }}</h4>
                            </div>
                            <div class="avatar bg-label-danger rounded p-2">
                                <i class="ti ti-arrow-down-left ti-md"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Net / Saldo Akhir --}}
            <div class="col-xl-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text text-muted mb-1">Total Net</p>
                                <h4 class="card-title mb-1 text-info">Rp {{ formatAngka($total_net) }}</h4>
                            </div>
                            <div class="avatar bg-label-info rounded p-2">
                                <i class="ti ti-layers-subtract ti-md"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detailed Summary (Sketch Style) --}}
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm border">
                    <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                        <h6 class="m-0 fw-bold text-white"><i class="ti ti-report-money me-2"></i>Daily Financial Summary</h6>
                    </div>
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 cursor-pointer btn-detail-saldoawal">
                            <h5 class="mb-0 fw-bold">Saldo</h5>
                            <h5 class="mb-0 fw-bold text-primary">Rp {{ formatAngka($saldo_awal) }}</h5>
                        </div>
                        
                        <div class="ms-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer btn-detail-mutasi" data-jenis="K">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-circle-check text-success me-2"></i>
                                    <span class="text-muted">Penerimaan</span>
                                </div>
                                <span class="fw-semibold text-success">Rp {{ formatAngka($penerimaan) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer btn-detail-mutasi" data-jenis="D">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-circle-check text-danger me-2"></i>
                                    <span class="text-muted">Pengeluaran</span>
                                </div>
                                <span class="fw-semibold text-danger">Rp {{ formatAngka($pengeluaran) }}</span>
                            </div>
                        </div>

                        <div class="border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-label-info rounded-circle p-2 me-3">
                                        <i class="ti ti-sum ti-sm"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold">Net. (Saldo Akhir)</h5>
                                </div>
                                <h4 class="mb-0 fw-bold text-info">Rp {{ formatAngka($net) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm border">
                    <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                        <h6 class="m-0 fw-bold text-white"><i class="ti ti-report-money me-2"></i>Rekap Kas Besar</h6>
                    </div>
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 cursor-pointer btn-detail-saldoawal-kb">
                            <h5 class="mb-0 fw-bold">Saldo</h5>
                            <h5 class="mb-0 fw-bold text-primary">Rp {{ formatAngka($saldo_awal_kb) }}</h5>
                        </div>
                        
                        <div class="ms-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer btn-detail-mutasi-kb" data-jenis="K">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-circle-check text-success me-2"></i>
                                    <span class="text-muted">Penerimaan</span>
                                </div>
                                <span class="fw-semibold text-success">Rp {{ formatAngka($penerimaan_kb) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer btn-detail-mutasi-kb" data-jenis="D">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-circle-check text-danger me-2"></i>
                                    <span class="text-muted">Pengeluaran</span>
                                </div>
                                <span class="fw-semibold text-danger">Rp {{ formatAngka($pengeluaran_kb) }}</span>
                            </div>
                        </div>

                        <div class="border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-label-info rounded-circle p-2 me-3">
                                        <i class="ti ti-sum ti-sm"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold">Net. (Saldo Akhir)</h5>
                                </div>
                                <h4 class="mb-0 fw-bold text-info">Rp {{ formatAngka($net_kb) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Detail Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();

        // Detail Saldo Awal
        $(".btn-detail-saldoawal").click(function() {
            let tanggal = $("#tanggal").val();
            $("#modalTitle").text("Detail Saldo Awal Rekening (" + tanggal + ")");
            $("#modalBody").html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
            $("#modalDetail").modal("show");

            $.ajax({
                url: "{{ route('dashboard.getdetailsaldoawal') }}",
                type: "GET",
                data: { tanggal: tanggal },
                success: function(response) {
                    $("#modalBody").html(response);
                }
            });
        });

        // Detail Mutasi (Penerimaan / Pengeluaran)
        $(".btn-detail-mutasi").click(function() {
            let tanggal = $("#tanggal").val();
            let jenis = $(this).data("jenis");
            let title = jenis == "K" ? "Detail Penerimaan" : "Detail Pengeluaran";
            
            $("#modalTitle").text(title + " (" + tanggal + ")");
            $("#modalBody").html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
            $("#modalDetail").modal("show");

            $.ajax({
                url: "{{ route('dashboard.getdetailmutasi') }}",
                type: "GET",
                data: { 
                    tanggal: tanggal,
                    jenis: jenis
                },
                success: function(response) {
                    $("#modalBody").html(response);
                }
            });
        });

        // Detail Saldo Awal Kas Besar
        $(".btn-detail-saldoawal-kb").click(function() {
            let tanggal = $("#tanggal").val();
            $("#modalTitle").text("Detail Saldo Awal Kas Besar (" + tanggal + ")");
            $("#modalBody").html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
            $("#modalDetail").modal("show");

            $.ajax({
                url: "{{ route('dashboard.getdetailsaldoawalkb') }}",
                type: "GET",
                data: { tanggal: tanggal },
                success: function(response) {
                    $("#modalBody").html(response);
                }
            });
        });

        // Detail Mutasi Kas Besar
        $(".btn-detail-mutasi-kb").click(function() {
            let tanggal = $("#tanggal").val();
            let jenis = $(this).data("jenis");
            let title = jenis == "K" ? "Detail Penerimaan Kas Besar" : "Detail Pengeluaran Kas Besar";
            
            $("#modalTitle").text(title + " (" + tanggal + ")");
            $("#modalBody").html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
            $("#modalDetail").modal("show");

            $.ajax({
                url: "{{ route('dashboard.getdetailmutasikb') }}",
                type: "GET",
                data: { 
                    tanggal: tanggal,
                    jenis: jenis
                },
                success: function(response) {
                    $("#modalBody").html(response);
                }
            });
        });
    });
</script>
@endpush
