@extends('layouts.app')
@section('titlepage', 'PO')

@section('content')
@section('navigasi')
    <span>PO</span>
@endsection
<div class="row">

    <div class="col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('po.create') }}" class="btn btn-primary" id="btnCreate"><i
                            class="fa fa-plus me-2"></i> Input PO</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('po.index') }}" id="formSearch">
                            <div class="row">
                                <div class="col">
                                    <x-input-with-icon label="No. PO" value="{{ Request('no_bukti_search') }}"
                                        name="no_bukti_search" icon="ti ti-barcode" />
                                </div>
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
                            <table class="table table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 10%">No. Bukti</th>
                                        <th style="width: 10%">Tanggal</th>
                                        <th style="width:25%">Supplier</th>
                                        <th style="width: 10%">Jenis PO</th>
                                        <th style="width: 10%">Total</th>
                                        <th style="width: 7%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian as $d)
                                        <tr>
                                            <td>{{ $d->no_bukti }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nama_supplier }}</td>
                                            <td>{{ $d->kategori_perusahaan }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($d->subtotal) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('po.edit', Crypt::encrypt($d->no_bukti)) }}"
                                                        class="btnEdit">
                                                        <i class="ti ti-edit text-success me-1"></i>
                                                    </a>
                                                    <a href="#" class="btnShow"
                                                        no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                        <i class="ti ti-file-description text-info me-1"></i>
                                                    </a>
                                                    <form method="POST" name="deleteform" class="deleteform"
                                                        action="{{ route('po.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm me-1">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </a>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $pembelian->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" size="modal-xl" />
@endsection
@push('myscript')
<script>
    $(function() {
        const select2Kodesupplier = $('.select2Kodesupplier');
        if (select2Kodesupplier.length) {
            select2Kodesupplier.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Supplier',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Detail PO");
            $("#modal").find("#loadmodal").load(`/po/${no_bukti}/show`);
            // $("#modal").find(".modal-dialog").addClass('modal-xl');
        });


        $(".btnApprovegdl").click(function(e) {
            e.preventDefault();
            loading();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Approve Penerimaan Gudang Logistik");
            $("#modal").find("#loadmodal").load(`/pembelian/${no_bukti}/approvegdl`);
            // $("#modal").find(".modal-dialog").addClass('modal-xl');
        });


        $(".btnApprovemtc").click(function(e) {
            e.preventDefault();
            loading();
            var no_bukti = $(this).attr("no_bukti");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Approve Penerimaan Maintenance");
            $("#modal").find("#loadmodal").load(`/pembelian/${no_bukti}/approvemtc`);
            // $("#modal").find(".modal-dialog").addClass('modal-xl');
        });

    });
</script>
@endpush
