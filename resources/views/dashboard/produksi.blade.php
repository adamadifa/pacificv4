@extends('layouts.app')
@section('titlepage', 'Dashboard Produksi')
@section('content')
    <style>
        .tab-content {
            box-shadow: none !important;
            background: none !important;
        }

        .premium-card {
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .card-icon-wrapper {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-right: 12px;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #7367f0 0%, #a8a1f4 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #00cfe8 0%, #73e1f0 100%);
        }

        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #e6e6e6;
            border-radius: 10px;
        }

        /* Sticky Column Styles */
        .table-sticky-first {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: white !important; /* Default for body rows */
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        thead .table-sticky-first {
            z-index: 3;
            background-color: #002e65 !important; /* Matches unified header */
        }
    </style>
@section('navigasi')
    @include('dashboard.navigasi')
@endsection
<div class="row">
    <div class="col-xl-12">
        @include('dashboard.welcome')
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                @include('layouts.navigation_dashboard')
            </ul>

            <div class="tab-content p-0" id="tab-content-main" style="overflow-x: hidden;">
                <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                    <div class="row mb-4 mx-0">
                        {{-- Left Column: Permintaan Produksi --}}
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="card premium-card shadow-sm h-100">
                                <div class="card-header border-bottom py-3 d-flex align-items-center" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                    <i class="ti ti-clipboard-list fs-3 text-white me-2"></i>
                                    <h5 class="card-title mb-0 fw-bold text-white">Permintaan Produksi</h5>
                                </div>
                                <div class="card-body pt-4">
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            @php
                                                $list_bulan_obj = [];
                                                foreach ($list_bulan as $b) {
                                                    $list_bulan_obj[] = (object) $b;
                                                }
                                            @endphp
                                            <x-select label="Bulan" name="bulan_realisasi" id="bulan_realisasi" 
                                                :data="$list_bulan_obj" key="kode_bulan" textShow="nama_bulan" :selected="date('m')" />
                                        </div>
                                        <div class="col-md-6">
                                            @php
                                                $list_tahun = [];
                                                for ($t = $start_year; $t <= date('Y'); $t++) {
                                                    $list_tahun[] = (object) ['tahun' => $t];
                                                }
                                            @endphp
                                            <x-select label="Tahun" name="tahun_realisasi" id="tahun_realisasi"
                                                :data="$list_tahun" key="tahun" textShow="tahun" :selected="date('Y')" />
                                        </div>
                                    </div>
                                    <div id="loadrealisasipermintaanproduksi">
                                        <div class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Rekap Hasil Produksi --}}
                        <div class="col-lg-8 col-md-12 col-sm-12">
                            <div class="card premium-card shadow-sm h-100">
                                <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-chart-bar fs-3 text-white me-2"></i>
                                        <h5 class="card-title mb-0 fw-bold text-white">Rekap Hasil Produksi</h5>
                                    </div>
                                    <div style="width: 150px;">
                                        <x-select name="tahun_hasil_produksi" id="tahun_hasil_produksi" hideLabel="true"
                                            :data="$list_tahun" key="tahun" textShow="tahun" :selected="date('Y')" />
                                    </div>
                                </div>
                                <div class="card-body px-0 pt-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-borderless mb-0">
                                            <thead style="background-color: #002e65;">
                                                <tr>
                                                    <th rowspan="2" class="ps-4 text-white border-0 table-sticky-first py-2">Produk</th>
                                                    <th colspan="12" class="text-center py-1 text-white border-0 small opacity-75">Bulan Produksi</th>
                                                </tr>
                                                <tr>
                                                    @for ($i = 1; $i <= 12; $i++)
                                                        <th class="text-center text-white border-0 py-1" style="font-size: 0.75rem;">{{ $nama_bulan_singkat[$i] }}</th>
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody id="loadrekaphasilproduksi">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Row: Grafik Hasil Produksi --}}
                    <div class="row mx-0">
                        <div class="col-12">
                            <div class="card premium-card shadow-sm">
                                <div class="card-header border-bottom py-3 d-flex align-items-center">
                                    <div class="card-icon-wrapper bg-label-success">
                                        <i class="ti ti-presentation-analytics fs-3"></i>
                                    </div>
                                    <h5 class="card-title mb-0 fw-bold">Grafik Hasil Produksi</h5>
                                </div>
                                <div class="card-body" id="loadgrafikhasilproduksi">
                                    <div class="text-center py-5">
                                        <div class="spinner-border text-success" role="status">
                                            <span class="visually-hidden">Loading...</span>
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
</div>
@endsection
@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(function() {

        function loadrealisasipermintaanproduksi() {
            const bulan = $("#bulan_realisasi").val();
            const tahun = $("#tahun_realisasi").val();
            $.ajax({
                type: "POST",
                url: "/permintaanproduksi/getrealisasi",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $("#loadrealisasipermintaanproduksi").html(respond);
                }
            });
        }

        function loadrekaphasilproduksi() {
            const tahun = $("#tahun_hasil_produksi").val();
            $.ajax({
                type: "POST",
                url: "/bpbj/getrekaphasilproduksi",
                data: {
                    _token: "{{ csrf_token() }}",
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $("#loadrekaphasilproduksi").html(respond);
                }
            });
        }

        function loadgrafikhasilproduksi() {
            const tahun = $("#tahun_hasil_produksi").val();
            $.ajax({
                type: "POST",
                url: "/bpbj/getgrafikhasilproduksi",
                data: {
                    _token: "{{ csrf_token() }}",
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $("#loadgrafikhasilproduksi").html(respond);
                    //console.log(respond);
                }
            });
        }

        loadrealisasipermintaanproduksi();
        loadrekaphasilproduksi();
        loadgrafikhasilproduksi();
        $("#bulan_realisasi,#tahun_realisasi").change(function() {
            loadrealisasipermintaanproduksi();
        });

        $("#tahun_hasil_produksi").change(function() {
            loadrekaphasilproduksi();
            loadgrafikhasilproduksi();
        });

    });
</script>
@endpush
