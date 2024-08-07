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
                        <div class="col" id="loadrekappersediaan">
                            {{-- @include('dashboard.gudang.rekappersediaan') --}}
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


        function loadrekappersediaan() {
            $("#loadrekappersediaan").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadrekappersediaan").load('/dashboard/rekappersediaan');
        }

        loadrekappersediaan();
    });
</script>
@endpush
