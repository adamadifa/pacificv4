@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    <style>
        .tab-content {
            box-shadow: none !important;
            background: none !important;
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

            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Permintaan Produksi</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <select name="bulan_realisasi" id="bulan_realisasi" class="form-select">
                                                    <option value="">Bulan</option>
                                                    @foreach ($list_bulan as $d)
                                                        <option {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                                                            {{ $d['nama_bulan'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <select name="tahun_realiasi" id="tahun_realisasi" class="form-select">
                                                    <option value="">Tahun</option>
                                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                                        <option {{ date('Y') == $t ? 'selected' : '' }} value="{{ $t }}">
                                                            {{ $t }}</option>
                                                    @endfor
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col" id="loadrealisasipermintaanproduksi"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Rekap Hasil Produksi</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <select name="tahun_hasil_produksi" id="tahun_hasil_produksi" class="form-select">
                                                                <option value="">Tahun</option>
                                                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                                                    <option {{ date('Y') == $t ? 'selected' : '' }} value="{{ $t }}">
                                                                        {{ $t }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover table-bordered">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th rowspan="2">Produk</th>
                                                                        <th colspan="12">Bulan</th>
                                                                    </tr>
                                                                    <tr>
                                                                        @for ($i = 1; $i <= 12; $i++)
                                                                            <th>{{ $nama_bulan_singkat[$i] }}</th>
                                                                        @endfor
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="loadrekaphasilproduksi" style="font-size: 12px">
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
                            <div class="row mt-2">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Grafik Hasil Produksi</h4>
                                        </div>
                                        <div class="card-body" id="loadgrafikhasilproduksi">

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
