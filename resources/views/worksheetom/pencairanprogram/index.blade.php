@extends('layouts.app')
@section('titlepage', 'Pencairan Program')

@section('content')
@section('navigasi')
    <span>Pencairan Program</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_monitoringprogram')
            @include('layouts.navigation_program_kumulatif')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('pencairanprogram.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('pencairanprogram.index') }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <x-select label="Semua Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                                upperCase="true" selected="{{ Request('kode_cabang') }}" select2="select2Kodecabang" />
                                        </div>
                                    </div>
                                @endrole
                                <div class="form-group">
                                    <select name="kode_program" id="kode_program" class="form-select">
                                        <option value="">Semua Program</option>
                                        <option value="PR001" {{ Request('kode_program') == 'PR001' ? 'selected' : '' }}>BB & DP</option>
                                        <option value="PR002" {{ Request('kode_program') == 'PR002' ? 'selected' : '' }}>AIDA</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ Request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ Request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="rejected" {{ Request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
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
                                            <th rowspan="2" valign="middle">No. Ajuan</th>
                                            <th rowspan="2" valign="middle">Tanggal</th>
                                            <th rowspan="2" valign="middle">Bulan</th>
                                            <th rowspan="2" valign="middle">Tahun</th>
                                            <th rowspan="2" valign="middle">Program</th>
                                            <th rowspan="2" valign="middle">Cabang</th>
                                            <th colspan="4" valign="middle" class="text-center">Persetujuan</th>
                                            {{-- <th rowspan="2" valign="middle">Keuangan</th> --}}
                                            <th rowspan="2" valign="middle">Status</th>
                                            <th rowspan="2" valign="middle">Keuangan</th>
                                            <th rowspan="2" valign="middle"><i class="ti ti-file-description"></i></th>
                                            <th rowspan="2" valign="middle">#</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">OM</th>
                                            <th class="text-center">RSM</th>
                                            <th class="text-center">GM</th>
                                            <th class="text-center">Direktur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pencairanprogram as $d)
                                            <tr>
                                                <td>{{ $d->kode_pencairan }}</td>
                                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                                <td>{{ $namabulan[$d->bulan] }}</td>
                                                <td>{{ $d->tahun }}</td>
                                                <td>{{ $d->kode_program == 'PR001' ? 'BB & DP' : 'AIDA' }}</td>
                                                <td>{{ $d->kode_cabang }}</td>
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
                                                        <i class="ti ti-check text-success"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (empty($d->gm))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-check text-success"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (empty($d->direktur))
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-check text-success"></i>
                                                    @endif
                                                </td>
                                                {{-- <td class="text-center">
                                                    <i class="ti ti-hourglass-empty text-warning"></i>
                                                </td> --}}
                                                <td class="text-center">
                                                    @if ($d->status == '0')
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @elseif ($d->status == '1')
                                                        <i class="ti ti-checks text-success"></i>
                                                    @elseif($d->status == '2')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->keuangan == null)
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-square-check text-success"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!empty($d->bukti_transfer))
                                                        <a href="{{ url($d->bukti_transfer) }}" target="_blank">
                                                            <i class="ti ti-receipt text-success"></i>
                                                        </a>
                                                    @else
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('pencairanprogram.approve')
                                                            @if ($user->hasRole('operation manager') && $d->rsm == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole('regional sales manager') && $d->gm == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole('gm marketing') && $d->direktur == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole(['manager keuangan', 'staff keuangan']) && $d->status == 1)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @elseif ($user->hasRole(['super admin', 'direktur']) && $d->keuangan == null)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                    <i class="ti ti-external-link text-success"></i>
                                                                </a>
                                                            @endif
                                                        @endcan
                                                        @can('pencairanprogram.edit')
                                                            <a href="{{ route('pencairanprogram.setpencairan', Crypt::encrypt($d->kode_pencairan)) }}"
                                                                class="me-1">
                                                                <i class="ti ti-settings text-primary"></i>
                                                            </a>
                                                        @endcan
                                                        @can('pencairanprogram.show')
                                                            <a href="{{ route('pencairanprogram.cetak', Crypt::encrypt($d->kode_pencairan)) }}"
                                                                class="me-1" target="_blank">
                                                                <i class="ti ti-printer text-success"></i>
                                                            </a>
                                                            <a href="{{ route('pencairanprogram.cetak', Crypt::encrypt($d->kode_pencairan)) }}?export=true"
                                                                class="me-1" target="_blank">
                                                                <i class="ti ti-download text-success"></i>
                                                            </a>
                                                        @endcan
                                                        @can('pencairanprogramikt.upload')
                                                            <a href="#"
                                                                kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}"class="btnUpload">
                                                                <i class="ti ti-upload text-primary"></i>
                                                            </a>
                                                        @endcan
                                                        @can('pencairanprogram.delete')
                                                            @if ($user->hasRole('operation manager') && $d->rsm == null)
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('pencairanprogram.delete', Crypt::encrypt($d->kode_pencairan)) }}">
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
                            <div style="float: right;">
                                {{ $pencairanprogram->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalApprove" size="modal-xl" show="loadmodalapprove" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
<x-modal-form id="modalUpload" size="" show="loadmodalupload" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Pencairan Program");
            $("#loadmodal").load("/pencairanprogram/create");
        });
        $(".btnUpload").click(function(e) {
            e.preventDefault();
            let kode_pencairan = $(this).attr("kode_pencairan");
            $("#modalUpload").modal("show");
            $("#modalUpload").find(".modal-title").text("Upload Bukti Transfer");
            $("#loadmodalupload").load("/pencairanprogram/" + kode_pencairan + "/upload");
        });

        $(document).on('click', '.btnDetailfaktur', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let kode_pencairan = $(this).attr('kode_pencairan');
            $("#modalDetailfaktur").modal("show");
            $("#modalDetailfaktur").find(".modal-title").text('Detail Faktur');
            $("#modalDetailfaktur").find("#loadmodaldetailfaktur").load(
                `/pencairanprogram/${kode_pelanggan}/${kode_pencairan}/detailfaktur`);
        });

        const select2Kodecabang = $('.select2Kodecabang');
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
            const kode_pencairan = $(this).attr('kode_pencairan');
            e.preventDefault();
            $('#modalApprove').modal("show");
            $("#modalApprove").find(".modal-title").text("Approve Pencairan Program Ikatan");
            $("#loadmodalapprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalapprove").load('/pencairanprogram/' + kode_pencairan + '/approve');
        });

    });
</script>
@endpush
