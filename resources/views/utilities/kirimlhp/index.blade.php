@extends('layouts.app')
@section('titlepage', 'kirimlhp')

@section('content')
@section('navigasi')
    <span>Kirim LHP</span>
@endsection
<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Kirim LHP</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('kirimlhp.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Bulan</option>
                                            @foreach ($list_bulan as $d)
                                                <option value="{{ $d['kode_bulan'] }}" {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>
                                                    {{ $d['nama_bulan'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option value="{{ $t }}" {{ Request('tahun') == $t ? 'selected' : '' }}>{{ $t }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <button class="btn btn-primary"><i class="ti ti-search"></i></button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Cabang</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Tanggal</th>
                                        <th><i class="ti ti-paperclip"></i></th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kirim_lhp as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                            <td>{{ $namabulan[$d->bulan] }}</td>
                                            <td>{{ $d->tahun }}</td>
                                            <td>{{ formatIndo($d->tanggal) }} {{ $d->jam }}</td>
                                            <td>
                                                @if (!empty($d->foto))
                                                    @php
                                                        $path = Storage::url('lhp/' . $d->foto);
                                                    @endphp
                                                    <a href="{{ url($path) }}" target="_blank">
                                                        <i class="ti ti-paperclip"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status == 0)
                                                    <i class="ti ti-hourglass-empty text-warning"></i>
                                                @else
                                                    <i class="ti ti-checks text-success"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if ($d->status == 0)
                                                        @can('kirimlhp.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('kirimlhp.delete', Crypt::encrypt($d->kode_kirim_lhp)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                                        @can('kirimlhp.approve')
                                                            <div>
                                                                <a href="#" kode_kirim_lhp="{{ Crypt::encrypt($d->kode_kirim_lhp) }}"
                                                                    class="btnApprove"> <i class="ti ti-external-link text-success"></i></a>
                                                            </div>
                                                        @endcan
                                                    @else
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('kirimlhp.cancelapprove', Crypt::encrypt($d->kode_kirim_lhp)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="cancel-confirm ml-1">
                                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endif
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

<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Kirim LHP" />
<x-modal-form id="mdleditRole" size="" show="loadeditRole" title="Edit Role" />

@endsection



@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/kirimlhp/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#mdlCreate').modal("show");
            $("#loadCreate").load('/kirimlhp/create');
        });

        $(".btnApprove").click(function(e) {
            const kode_kirim_lhp = $(this).attr('kode_kirim_lhp');
            e.preventDefault();
            $('#mdlCreate').modal("show");
            $("#loadCreate").load(`/kirimlhp/${kode_kirim_lhp}/approve`);
        });

        $(".editRole").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditRole').modal("show");
            $("#loadeditRole").load('/kirimlhp/' + id + '/edit');
        });
    });
</script>
@endpush