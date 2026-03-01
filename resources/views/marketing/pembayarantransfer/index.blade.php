@extends('layouts.app')
@section('titlepage', 'Pembayaran Transfer')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Pembayaran Transfer</h4>
            <small class="text-muted">Mengelola data pembayaran transfer pelanggan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-file-description me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-arrows-transfer-down me-1"></i>Transfer</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_girotransfer')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('pembayarantransfer.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari"
                                datepicker="flatpickr-date" value="{{ Request('dari') }}" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai"
                                datepicker="flatpickr-date" value="{{ Request('sampai') }}" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" />
                            </div>
                        @endrole
                    </div>
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan_search') }}" icon="ti ti-user"
                                name="nama_pelanggan_search" />
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="kode_salesman_search" id="kode_salesman_search"
                                    class="form-select select2Kodesalesmansearch">
                                    <option value="">Semua Salesman</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-arrows-transfer-down me-2"></i>Data Pembayaran Transfer</h6>
                    @can('pembayarantransfer.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Input Pembayaran Transfer
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">KODE TRANSFER</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">PELANGGAN</th>
                            <th class="text-white text-end" style="background-color: #002e65 !important;">JUMLAH</th>
                            <th class="text-white" style="background-color: #002e65 !important;">PENGIRIM</th>
                            <th class="text-white" style="background-color: #002e65 !important;">PENERIMA</th>
                            <th class="text-white" style="background-color: #002e65 !important;">JATUH TEMPO</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">STATUS</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($transfer as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->kode_transfer }}</span></td>
                                <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                <td style="width: 20%">{{ $d->nama_pelanggan }}</td>
                                <td class="text-end fw-bold">{{ formatAngka($d->total) }}</td>
                                <td>{{ textUpperCase($d->bank_pengirim) }}</td>
                                <td>{{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank }}</td>
                                <td>{{ date('d-m-y', strtotime($d->jatuh_tempo)) }}</td>
                                <td class="text-center">
                                    @if ($d->status == '1')
                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ $d->no_bukti }}">{{ date('d-m-y', strtotime($d->tanggal_diterima)) }}</span>
                                    @elseif($d->status == '2')
                                        <i class="ti ti-square-rounded-x text-danger fs-4"></i>
                                    @else
                                        <i class="ti ti-hourglass-empty text-warning fs-4"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('pembayarantransfer.approve')
                                            @if (auth()->user()->kode_cabang != 'PST')
                                                @if ($d->status === '0')
                                                    <a href="#" class="btnApprove text-success"
                                                        kode_transfer="{{ Crypt::encrypt($d->kode_transfer) }}" data-bs-toggle="tooltip" title="Approve">
                                                        <i class="ti ti-external-link fs-4"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <a href="#" class="btnApprove text-success"
                                                    kode_transfer="{{ Crypt::encrypt($d->kode_transfer) }}" data-bs-toggle="tooltip" title="Approve">
                                                    <i class="ti ti-external-link fs-4"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('pembayarantransfer.show')
                                            <a href="#" class="btnShow text-info"
                                                kode_transfer="{{ Crypt::encrypt($d->kode_transfer) }}" data-bs-toggle="tooltip" title="Detail">
                                                <i class="ti ti-file-description fs-4"></i>
                                            </a>
                                        @endcan
                                        @can('pembayarantransfer.delete')
                                            @if ($d->status === '0')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="/pembayarantransfer/{{ Crypt::encrypt($d->kode_transfer) }}/deletetransfer">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm text-danger">
                                                        <i class="ti ti-trash fs-4"></i>
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
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $transfer->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalApprove" size="modal-xl" show="loadmodalApprove" title="" />
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Pelanggan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelpelanggan" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Pelanggan</th>
                                <th>Salesman</th>
                                <th>Wilayah</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
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

        const select2Kodesalesmansearch = $('.select2Kodesalesmansearch');
        if (select2Kodesalesmansearch.length) {
            select2Kodesalesmansearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Salesman',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#kode_salesman_search").html(respond);
                }
            });
        }

        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            const kode_transfer = $(this).attr("kode_transfer");
            $('#modal').modal("show");
            $("#loadmodal").load(`/pembayarantransfer/${kode_transfer}/show`);
            $("#modal").find(".modal-title").text("Detail Transfer");
        });


        $(".btnApprove").click(function(e) {
            e.preventDefault();
            loading();
            const kode_transfer = $(this).attr("kode_transfer");
            $('#modal').modal("show");
            $("#loadmodal").load(`/pembayarantransfer/${kode_transfer}/approve`);
            $("#modal").find(".modal-title").text("Detail Transfer");
        });

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $('#modal').modal("show");
            $("#loadmodal").load(`/pembayarantransfer/creategroup`);
            $("#modal").find(".modal-title").text("Input Pembayaran Transfer Pelanggan");
        });

        $("#kode_cabang_search").change(function(e) {
            getsalesmanbyCabang();
        });

        getsalesmanbyCabang();


        $(document).on('click', '#kode_pelanggan_search', function(e) {
            $("#modalPelanggan").modal("show");
        });


        $('#tabelpelanggan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [2, 'asc']
            ],
            ajax: "{{ route('pelanggan.getpelangganjson') }}",
            bAutoWidth: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                {
                    data: 'kode_pelanggan',
                    name: 'kode_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '10%'
                },
                {
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'nama_salesman',
                    name: 'nama_salesman',
                    orderable: true,
                    searchable: false,
                    width: '20%'
                },

                {
                    data: 'nama_wilayah',
                    name: 'nama_wilayah',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'status_pelanggan',
                    name: 'status_pelanggan',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                }
            ],

            rowCallback: function(row, data, index) {
                if (data.status_pelanggan == "NonAktif") {
                    $("td", row).addClass("bg-danger text-white");
                }
            }
        });
    });
</script>
@endpush
