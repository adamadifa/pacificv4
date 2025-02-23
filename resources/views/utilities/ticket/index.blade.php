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
                                        <th>Cabang</th>
                                        <th class="text-center">GM</th>
                                        <th class="text-center">Direktur</th>
                                        <th>Status</th>
                                        <th class="text-center">Admin</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ticket as $d)
                                        <tr>
                                            <td>{{ $d->kode_pengajuan }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->keterangan }}</td>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td class="text-center">
                                                @if ($d->gm == null)
                                                    <i class="ti ti-hourglass-low  text-warning"></i>
                                                @elseif($d->gm != null && $d->direktur == null && $d->status == '2')
                                                    <i class="ti ti-square-x  text-danger"></i>
                                                @elseif($d->gm != null && $d->status != '2')
                                                    <i class="ti ti-check text-success"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($d->direktur == null)
                                                    <i class="ti ti-hourglass-low  text-warning"></i>
                                                @elseif($d->direktur != null && $d->status == '2')
                                                    <i class="ti ti-square-x  text-danger"></i>
                                                @elseif($d->direktur != null)
                                                    <i class="ti ti-check text-success"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->status == '2')
                                                    <i class="ti ti-square-x  text-danger"></i>
                                                @elseif($d->status == '1')
                                                    <i class="ti ti-check text-success"></i>
                                                @elseif($d->status == '0')
                                                    <i class="ti ti-hourglass-low  text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->admin == null)
                                                    <i class="ti ti-hourglass-low  text-warning"></i>
                                                @else
                                                    {{ $d->admin }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('ticket.edit')
                                                        <a href="#" class="btnEdit me-1" kode_pengajuan="{{ $d->kode_pengajuan }}"><i
                                                                class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('ticket.approve')
                                                        <a href="#" class="btnApprove me-1" kode_pengajuan="{{ $d->kode_pengajuan }}">
                                                            <i class="ti ti-external-link text-primary"></i>
                                                        </a>
                                                    @endcan
                                                    @can('ticket.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('ticket.delete', Crypt::encrypt($d->kode_pengajuan)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm ml-1">
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

<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Buat Ticket" />


@endsection



@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/kirimlhp/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#mdlCreate').modal("show");
            $("#loadCreate").load('/ticket/create');
        });

        $(".btnEdit").click(function(e) {
            const kode_pengajuan = $(this).attr('kode_pengajuan');
            e.preventDefault();
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Edit Ticket');
            $("#loadCreate").load(`/ticket/${kode_pengajuan}/edit`);
        });

        $(".btnApprove").click(function(e) {
            const kode_pengajuan = $(this).attr('kode_pengajuan');
            e.preventDefault();
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Approve Ticket');
            $("#loadCreate").load(`/ticket/${kode_pengajuan}/approve`);
        });


    });
</script>
@endpush
