@extends('layouts.app')
@section('titlepage', 'Ticket')

@section('content')
@section('navigasi')
    <span>Ticket</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat Ticket</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('ticket.index') }}">

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. Ticket</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                        <th>GM</th>
                                        <th>Direktur</th>
                                        <th>Admin</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Buat Ticket" />
<x-modal-form id="mdleditRole" size="" show="loadeditRole" title="Edit Role" />

@endsection



@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/kirimlhp/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#mdlCreate').modal("show");
            $("#loadCreate").load('/ticket/create');
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
