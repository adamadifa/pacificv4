@extends('layouts.app')
@section('titlepage', 'Dashboard Gudang')
@section('content')
    <style>
        #tab-content-main {
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
            <div class="tab-content" id="tab-content-main">
                <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-lg-9">
                            @include('dashboard.gudang.rekappersediaan')
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td></td>
                                        <td>STOK AMAN</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-danger"></td>
                                        <td>Stok Kurang Dari Buffer</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-info"></td>
                                        <td>Stok Lebih dari Max. Stok</td>
                                    </tr>
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
@endsection
@push('myscript')
<script>
    $("#report tr:not(.master)").hide();
    $("#report tr:first-child").show();
    $("#report tr.master").click(function() {
        $(this).next("tr").toggle();
        $(this).next("tr").next("tr").toggle();
        $(this).next("tr").next("tr").next("tr").toggle();

    });
</script>
@endpush
