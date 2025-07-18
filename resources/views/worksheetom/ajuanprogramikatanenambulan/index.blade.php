@extends('layouts.app')
@section('titlepage', 'Monitoring Program')

@section('content')
@section('navigasi')
    <span>Monitoring Program</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_monitoringprogram')
            @include('layouts.navigation_program_ikatan_enambulan')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('ajuanprogramenambulan.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('ajuanprogramenambulan.index') }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="form-group mb-3">
                                        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }}
                                                    value="{{ $d->kode_cabang }}">
                                                    {{ textUpperCase($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endrole
                                {{-- <x-input-with-icon label="No. Dokumen" value="{{ Request('nomor_dokumen') }}" name="nomor_dokumen"
                                    icon="ti ti-barcode" /> --}}
                                <x-select label="Semua Program" name="kode_program" :data="$programikatan" key="kode_program"
                                    textShow="nama_program" select2="select2Kodeprogram" upperCase="true"
                                    selected="{{ Request('kode_program') }}" />
                                <div class="form-group mb-3">
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ Request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved"
                                            {{ Request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="rejected"
                                            {{ Request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                                Data</button>
                                        </div>
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
                                            <th rowspan="2">No.</th>
                                            <th rowspan="2">No Pengajuan</th>
                                            {{-- <th rowspan="2">No. Dok</th> --}}
                                            <th rowspan="2">Tanggal</th>
                                            <th rowspan="2">Program</th>
                                            <th rowspan="2">Cabang</th>
                                            <th rowspan="2">Periode</th>
                                            <th rowspan="2">Pencairan</th>
                                            <th colspan="4">Persetujuan</th>
                                            <th rowspan="2">Status</th>
                                            <th rowspan="2">#</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">OM</th>
                                            <th class="text-center">RSM</th>
                                            <th class="text-center">GM</th>
                                            <th class="text-center">Direktur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ajuanprogramikatan as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->no_pengajuan }}</td>
                                                {{-- <td>{{ $d->nomor_dokumen }}</td> --}}
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->nama_program }}</td>
                                                <td>{{ strtoUpper($d->nama_cabang) }}</td>
                                                <td>{{ date('m', strtotime($d->periode_dari)) }}/{{ date('y', strtotime($d->periode_dari)) }}
                                                    -
                                                    {{ date('m', strtotime($d->periode_sampai)) }}/{{ date('y', strtotime($d->periode_sampai)) }}
                                                </td>
                                                <td>{{ $d->periode_pencairan == '1' ? 'Semester' : 'Perbulan' }}</td>
                                                <td class="text-center">
                                                    @if (empty($d->om))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-check text-success"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (empty($d->rsm))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        @if (empty($d->gm) && $d->status == '2')
                                                            <i class="ti ti-square-x text-danger"></i>
                                                        @else
                                                            <i class="ti ti-check text-success"></i>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (empty($d->gm))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        @if (empty($d->direktur) && $d->status == '2')
                                                            <i class="ti ti-square-x text-danger"></i>
                                                        @else
                                                            <i class="ti ti-check text-success"></i>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (empty($d->direktur))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        @if ($d->status == '2')
                                                            <i class="ti ti-square-x text-danger"></i>
                                                        @else
                                                            <i class="ti ti-check text-success"></i>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->status == '0')
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @elseif ($d->status == '1')
                                                        <i class="ti ti-checks text-success"></i>
                                                    @elseif($d->status == '2')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('ajuanprogramikatan.approve')
                                                            {{-- {{ $d->rsm }} {{ auth()->user()->hasRole('operation manager') }} --}}
                                                            {{-- {{ dd($user->hasRole('operation manager')) }} --}}
                                                            @if ($user->hasRole('operation manager') && $d->rsm == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole('regional sales manager') && $d->gm == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole('gm marketing') && $d->direktur == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif($user->hasRole(['super admin', 'direktur']))
                                                                <a href="#" class="btnApprove me-1"
                                                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @endif
                                                        @endcan
                                                        @can('ajuanprogramenambulan.show')
                                                            <a href="{{ route('ajuanprogramenambulan.cetak', Crypt::encrypt($d->no_pengajuan)) }}"
                                                                target="_blank">
                                                                <i class="ti ti-printer text-success"></i>
                                                            </a>
                                                        @endcan
                                                        @can('ajuanprogramenambulan.edit')
                                                            <a href="{{ route('ajuanprogramenambulan.setajuanprogramenambulan', Crypt::encrypt($d->no_pengajuan)) }}"
                                                                class="me-1">
                                                                <i class="ti ti-settings text-primary"></i>
                                                            </a>
                                                        @endcan


                                                        @can('ajuanprogramikatan.delete')
                                                            @if ($user->hasRole(['operation manager', 'sales marketing manager', 'super admin']) && $d->rsm == null)
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('ajuanprogramenambulan.delete', Crypt::encrypt($d->no_pengajuan)) }}">
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
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{ $ajuanprogramikatan->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalApprove" size="modal-xxl" show="loadmodalapprove" title="" />
<x-modal-form id="modalDetailtarget" size="" show="loadmodaldetailtarget" title="" />

@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Ajuan Program");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodal").load("/ajuanprogramenambulan/create");
        });

        const select2Kodecabang = $(".select2Kodecabang");
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $(".btnApprove").click(function(e) {
            const no_pengajuan = $(this).attr('no_pengajuan');
            e.preventDefault();
            $('#modalApprove').modal("show");
            $("#modalApprove").find(".modal-title").text("Approve Ajuan Program");
            $("#loadmodalapprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalapprove").load('/ajuanprogramenambulan/' + no_pengajuan + '/approve');
        });



        $(document).on('click', '.btnDetailTarget', function(e) {
            e.preventDefault();
            let no_pengajuan = $(this).attr('no_pengajuan');
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            $("#modalDetailtarget").modal("show");
            $("#modalDetailtarget").find(".modal-title").text('Detail Target Pelanggan');
            $("#modalDetailtarget").find("#loadmodaldetailtarget").load(
                `/ajuanprogramenambulan/${no_pengajuan}/${kode_pelanggan}/detailtarget`);
        });
    });
</script>
@endpush
