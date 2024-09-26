@extends('layouts.app')
@section('titlepage', 'kirimlhp')

@section('content')
@section('navigasi')
    <span>Kirim LHP</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Kirim LHP</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('kirimlhp.index') }}">
                            <div class="row">

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
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kirim_lhp as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                            <td>{{ $d->bulan }}</td>
                                            <td>{{ $d->tahun }}</td>
                                            <td>{{ formatIndo($d->tanggal) }} {{ $d->jam }}</td>
                                            <td>
                                                @if (!empty($d->foto))
                                                    <a href="{{ $d->foto }}" target="_blank">
                                                        <i class="ti ti-paperclip"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td></td>
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

        $(".editRole").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditRole').modal("show");
            $("#loadeditRole").load('/kirimlhp/' + id + '/edit');
        });
    });
</script>
@endpush
