@extends('layouts.app')
@section('titlepage', 'Opname Gudang Bahan')

@section('content')
@section('navigasi')
    <span>Opname Gudang Bahan</span>
@endsection
<div class="row">
    <div class="col-lg-7">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasigudangbahan')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('opgudangbahan.create')
                        <a href="{{ route('opgudangbahan.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>
                            Buat Opname</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('opgudangbahan.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
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
                                    <div class="col-lg-4 col-sm-12 col-md-12">
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
                                        <button class="btn btn-primary"><i
                                                class="ti ti-icons ti-search me-1"></i></button>
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
                                            <th>Tanggal</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saldo_awal as $d)
                                            <tr>
                                                <td>{{ $d->kode_opname }}</td>
                                                <td>{{ $nama_bulan[$d->bulan] }}</td>
                                                <td>{{ $d->tahun }}</td>
                                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('opgudangbahan.edit')
                                                            <div>
                                                                <a href="{{ route('opgudangbahan.edit', Crypt::encrypt($d->kode_opname)) }}"
                                                                    class="me-2"
                                                                    kode_opname="{{ Crypt::encrypt($d->kode_opname) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('opgudangbahan.show')
                                                            <div>
                                                                <a href="#" class="me-2 btnShow"
                                                                    kode_opname="{{ Crypt::encrypt($d->kode_opname) }}">
                                                                    <i class="ti ti-file-description text-info"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('opgudangbahan.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('opgudangbahan.delete', Crypt::encrypt($d->kode_opname)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
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

<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="Detail Opname " />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $(".btnShow").click(function(e) {
            var kode_opname = $(this).attr("kode_opname");
            e.preventDefault();
            $('#modal').modal("show");
            $("#loadmodal").load('/opgudangbahan/' + kode_opname + '/show');
        });
    });
</script>
@endpush
