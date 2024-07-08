@extends('layouts.app')
@section('titlepage', 'Barang Masuk Gudang Logistik')

@section('content')
@section('navigasi')
    <span>Barang Masuk Gudang Logistik</span>
@endsection
<div class="row">
    <div class="col-lg-7 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasigudanglogistik')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('barangmasukgl.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('barangmasukgudanglogistik.index') }}">
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
                                    <div class="col-12">
                                        <x-input-with-icon icon="ti ti-barcode" label="No. Bukti" name="no_bukti_search"
                                            value="{{ Request('no_bukti_search') }}" />
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
                                            <th>No. Bukti</th>
                                            <th>Supplier</th>
                                            <th>Tanggal Pembelian</th>
                                            <th>Tanggal Diterima</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barangmasuk as $d)
                                            <tr>
                                                <td>{{ $d->no_bukti }}</td>
                                                <td>{{ $d->nama_supplier }}</td>
                                                <td>{{ !empty($d->tanggal_pembelian) ? DateToIndo($d->tanggal_pembelian) : '' }}
                                                </td>
                                                <td>{{ DateToIndo($d->tanggal) }}</td>

                                                <td>
                                                    <div class="d-flex">
                                                        @can('barangmasukgl.edit')
                                                            @if (empty($d->tanggal_pembelian))
                                                                <div>
                                                                    <a href="#" class="me-2 btnEdit" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                        <i class="ti ti-edit text-success"></i>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endcan
                                                        @can('barangmasukgl.show')
                                                            <div>
                                                                <a href="#" class="me-2 btnShow" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                    <i class="ti ti-file-description text-info"></i>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('barangmasukgl.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('barangmasukgudanglogistik.delete', Crypt::encrypt($d->no_bukti)) }}">
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
                            <div style="float: right;">
                                {{ $barangmasuk->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        function loadingElement() {
            const loading = `<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`;

            return loading;
        };

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Data Barang Masuk");
            $("#loadmodal").html(loadingElement());
            $("#loadmodal").load(`/barangmasukgudanglogistik/create`);
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            var no_bukti = $(this).attr("no_bukti");
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Detail Barang Masuk");
            $("#loadmodal").html(loadingElement());
            $("#loadmodal").load(`/barangmasukgudanglogistik/${no_bukti}/show`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var no_bukti = $(this).attr("no_bukti");
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Barang Masuk");
            $("#loadmodal").html(loadingElement());
            $("#loadmodal").load(`/barangmasukgudanglogistik/${no_bukti}/edit`);
        });
    });
</script>
@endpush
