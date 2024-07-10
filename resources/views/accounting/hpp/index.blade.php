@extends('layouts.app')
@section('titlepage', 'Harga HPP')
@section('content')
@section('navigasi')
    <span>Harga HPP</span>
@endsection
<div class="row">
    <div class="col-lg-6">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_hpp')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('hpp.create')
                        <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-plus me-2"></i>
                            Input Harga HPP
                        </a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ URL::current() }}">
                                <div class="row">
                                    <div class="col-lg-5 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="bulan" id="bulan" class="form-select">
                                                <option value="">Bulan</option>
                                                @foreach ($list_bulan as $d)
                                                    <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                                        value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="tahun" id="tahun" class="form-select">
                                                <option value="">Tahun</option>
                                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                                    <option
                                                        @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                                        @else
                                                        {{ date('Y') == $t ? 'selected' : '' }} @endif
                                                        value="{{ $t }}">{{ $t }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-12 col-md-12">
                                        <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hpp as $d)
                                            <tr>
                                                <td>{{ $d->kode_hpp }}</td>
                                                <td>{{ $namabulan[$d->bulan] }}</td>
                                                <td>{{ $d->tahun }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('hpp.edit')
                                                            <a href="#" class="btnEdit" kode_hpp="{{ Crypt::encrypt($d->kode_hpp) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endcan
                                                        @can('hpp.show')
                                                            <a href="#" class="btnShow" kode_hpp="{{ Crypt::encrypt($d->kode_hpp) }}">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        @endcan
                                                        @can('hpp.delete')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('hpp.delete', Crypt::encrypt($d->kode_hpp)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm me-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endcan
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
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

<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $('#modal').modal("show");
            $("#modal").find(".modal-title").text("Input Harga HPP");
            $("#loadmodal").load('/hpp/create');
        });

        $(".btnShow").click(function(e) {
            var kode_hpp = $(this).attr("kode_hpp");
            e.preventDefault();
            loading();
            $('#modal').modal("show");
            $("#modal").find(".modal-title").text("Detail Harga HPP");
            $("#loadmodal").load('/hpp/' + kode_hpp + '/show');
        });

        $(".btnEdit").click(function(e) {
            var kode_hpp = $(this).attr("kode_hpp");
            e.preventDefault();
            loading();
            $('#modal').modal("show");
            $("#modal").find(".modal-title").text("Edit Harga HPP");
            $("#loadmodal").load('/hpp/' + kode_hpp + '/edit');
        });

    });
</script>
@endpush
