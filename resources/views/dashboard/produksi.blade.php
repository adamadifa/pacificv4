@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
@section('navigasi')
    <span>Dashboard</span>
@endsection
<div class="row">
    <div class="col-xl-12">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                @include('layouts.navigation_dashboard')
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                    <div class="row">
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
                                                        <option {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}
                                                            value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <select name="tahun_realiasi" id="tahun_realisasi" class="form-select">
                                                    <option value="">Tahun</option>
                                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                                        <option {{ date('Y') == $t ? 'selected' : '' }}
                                                            value="{{ $t }}">{{ $t }}</option>
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

        loadrealisasipermintaanproduksi();

        $("#bulan_realisasi,#tahun_realisasi").change(function() {
            loadrealisasipermintaanproduksi();
        });
    });
</script>
@endpush
