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
                            @hasanyrole($roles_show_cabang)
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                            textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                            select2="select2Kodecabangsearch" />
                                    </div>
                                </div>
                            @endrole
                            <div class="form-group">
                                <select name="status_search" id="status_search" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ Request('status_search') == 'pending' ? 'selected' : '' }}>Belum Selesai</option>
                                    <option value="selesai" {{ Request('status_search') == 'selessai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                    Data</button>
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
                                        <th>No. Ticket</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                        <th>Cabang</th>
                                        <th class="text-center">GM</th>
                                        {{-- <th class="text-center">Direktur</th> --}}
                                        <th>Status</th>
                                        <th>Admin</th>
                                        <th class="text-center">Selesai</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ticket as $d)
                                        <tr>
                                            <td style="width: 5%">{{ $d->kode_pengajuan }}</td>
                                            <td style="width: 10%">{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->keterangan }}</td>
                                            <td style="width: 10%">{{ formatName2($d->name) }}</td>
                                            <td style="width: 5%">{{ $d->kode_cabang }}</td>
                                            <td class="text-center" style="width: 3%">
                                                @if ($d->gm == null)
                                                    <i class="ti ti-hourglass-low  text-warning"></i>
                                                @elseif($d->gm != null && $d->direktur == null && $d->status == '2')
                                                    <i class="ti ti-square-x  text-danger"></i>
                                                @elseif($d->gm != null && $d->status != '2')
                                                    <i class="ti ti-check text-success"></i>
                                                @endif
                                            </td>
                                            {{-- <td class="text-center">
                                                @if ($d->direktur == null)
                                                    <i class="ti ti-hourglass-low  text-warning"></i>
                                                @elseif($d->direktur != null && $d->status == '2')
                                                    <i class="ti ti-square-x  text-danger"></i>
                                                @elseif($d->direktur != null)
                                                    <i class="ti ti-check text-success"></i>
                                                @endif
                                            </td> --}}
                                            <td class="text-center" style="width: 3%">
                                                @if ($d->status == '2')
                                                    <i class="ti ti-square-x  text-danger"></i>
                                                @elseif($d->status == '1')
                                                    <i class="ti ti-check text-success"></i>
                                                @elseif($d->status == '0')
                                                    <i class="ti ti-hourglass-low  text-warning"></i>
                                                @endif
                                            </td>
                                            <td style="width: 10%">
                                                @if ($d->admin == null)
                                                    <i class="ti ti-hourglass-low  text-warning"></i>
                                                @else
                                                    {{ formatName2($d->admin) }}
                                                @endif
                                            </td>
                                            <td style="width: 10%" class="text-center">
                                                @if (!empty($d->tanggal_selesai))
                                                    {{ date('d-m-Y', strtotime($d->tanggal_selesai)) }}
                                                @else
                                                    <i class="ti ti-hourglass-low  text-warning"></i>
                                                @endif
                                            </td>
                                            <td style="width: 5%">
                                                <div class="d-flex">
                                                    @can('ticket.edit')
                                                        @if ($d->gm == null)
                                                            <a href="#" class="btnEdit me-1" kode_pengajuan="{{ $d->kode_pengajuan }}"><i
                                                                    class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('ticket.approve')
                                                        @if ($level_user == 'gm administrasi' && $d->status == '0')
                                                            <a href="#" class="btnApprove me-1" kode_pengajuan="{{ $d->kode_pengajuan }}">
                                                                <i class="ti ti-external-link text-primary"></i>
                                                            </a>
                                                        @elseif($level_user == 'super admin')
                                                            <a href="#" class="btnApprove me-1" kode_pengajuan="{{ $d->kode_pengajuan }}">
                                                                <i class="ti ti-external-link text-primary"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('ticket.delete')
                                                        @if ($d->gm == null)
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ticket.delete', Crypt::encrypt($d->kode_pengajuan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
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


        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
@endpush
