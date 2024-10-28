@extends('layouts.app')
@section('titlepage', 'Pembayaran Giro')

@section('content')
@section('navigasi')
    <span>Pembayaran Giro</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_girotransfer')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('pembayarangiro.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Input Pembayaran Giro
                        </a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('pembayarangiro.index') }}">
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
                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                                textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                                select2="select2Kodecabangsearch" />
                                        </div>
                                    </div>
                                @endrole
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <x-input-with-icon label="No. Giro" icon="ti ti-barcode" name="no_giro"
                                                value="{{ Request('no_giro') }}" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan_search') }}" icon="ti ti-user"
                                                name="nama_pelanggan_search" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <select name="kode_salesman_search" id="kode_salesman_search"
                                                class="form-select select2Kodesalesmansearch">
                                                <option value="">Semua Salesman</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group mb-3">
                                            <select name="status" id="status" class="form-select">
                                                <option value="">Status</option>
                                                <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>
                                                    Disetujui</option>
                                                <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>
                                                    Ditolak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-2"></i>Cari
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
                                            <th>No. Giro</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Jumlah</th>
                                            <th>Bank Pengirim</th>
                                            <th>Bank Penerima</th>
                                            <th>Jatuh Tempo</th>
                                            <th class="text-center">Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($giro as $d)
                                            <tr>
                                                <td>{{ $d->no_giro }}</td>
                                                <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                                <td style="width: 20%">{{ $d->nama_pelanggan }}</td>
                                                <td class="text-end fw-bold">{{ formatAngka($d->total) }}</td>
                                                <td>{{ textUpperCase($d->bank_pengirim) }}</td>
                                                <td>{{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank }}
                                                </td>
                                                <td>{{ date('d-m-y', strtotime($d->jatuh_tempo)) }}</td>
                                                <td class="text-center">
                                                    @if ($d->status == '1')
                                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            data-bs-custom-class="tooltip-primary"
                                                            data-bs-original-title="{{ $d->no_bukti }}">{{ date('d-m-y', strtotime($d->tanggal_diterima)) }}</span>
                                                    @elseif($d->status == '2')
                                                        <i class="ti ti-square-rounded-x text-danger"></i>
                                                    @else
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('pembayarangiro.approve')
                                                            @if (auth()->user()->kode_cabang != 'PST')
                                                                @if ($d->status === '0')
                                                                    <div>
                                                                        <a href="#" class="btnApprove me-2"
                                                                            kode_giro="{{ Crypt::encrypt($d->kode_giro) }}"><i
                                                                                class="ti ti-external-link text-success"></i></a>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <div>
                                                                    <a href="#" class="btnApprove me-2"
                                                                        kode_giro="{{ Crypt::encrypt($d->kode_giro) }}"><i
                                                                            class="ti ti-external-link text-success"></i></a>
                                                                </div>
                                                            @endif
                                                        @endcan
                                                        @can('pembayarangiro.show')
                                                            <div>
                                                                <a href="#" class="btnShow" kode_giro="{{ Crypt::encrypt($d->kode_giro) }}"><i
                                                                        class="ti ti-file-description text-info"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('pembayarangiro.delete')
                                                            @if ($d->status === '0')
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="/pembayarangiro/{{ Crypt::encrypt($d->kode_giro) }}/deletegiro">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm me-1">
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
                                {{ $giro->links() }}
                            </div>
                        </div>
                    </div>
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
            const kode_giro = $(this).attr("kode_giro");
            $('#modal').modal("show");
            $("#loadmodal").load(`/pembayarangiro/${kode_giro}/show`);
            $("#modal").find(".modal-title").text("Detail Giro");
        });


        $(".btnApprove").click(function(e) {
            e.preventDefault();
            loading();
            const kode_giro = $(this).attr("kode_giro");
            $('#modal').modal("show");
            $("#loadmodal").load(`/pembayarangiro/${kode_giro}/approve`);
            $("#modal").find(".modal-title").text("Detail Giro");
        });

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $('#modal').modal("show");
            $("#loadmodal").load(`/pembayarangiro/creategroup`);
            $("#modal").find(".modal-title").text("Input Pembayaran Giro Pelanggan");
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
