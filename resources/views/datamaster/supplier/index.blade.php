@extends('layouts.app')
@section('titlepage', 'Supplier')

@section('content')
@section('navigasi')
    <span>Supplier</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('supplier.create')
                    <a href="#" class="btn btn-primary" id="btncreateSupplier"><i class="fa fa-plus me-2"></i> Tambah
                        Supplier</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('supplier.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Supplier" value="{{ Request('nama_supplier') }}"
                                        name="nama_supplier" icon="ti ti-search" />
                                </div>

                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i
                                            class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
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
                                        <th style="width: 1%">No.</th>
                                        <th>Kode</th>
                                        <th style="width: 20%">Nama Supplier</th>
                                        <th style="width: 20%">Alamat</th>
                                        <th>Contact</th>
                                        <th>No. HP</th>
                                        <th>Email</th>
                                        <th>No. Rek</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplier as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $supplier->firstItem() - 1 }}</td>
                                            <td>{{ $d->kode_supplier }}</td>
                                            <td>{{ textupperCase($d->nama_supplier) }}</td>
                                            <td>{{ textCamelCase($d->alamat_supplier) }}</td>
                                            <td>{!! $d->contact_person !!}</td>
                                            <td>{{ $d->no_hp_supplier }}</td>
                                            <td>{{ $d->email_supplier }}</td>
                                            <td>{{ $d->no_rekening_supplier }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('supplier.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editSupplier"
                                                                kode_supplier="{{ Crypt::encrypt($d->kode_supplier) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('supplier.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('supplier.delete', Crypt::encrypt($d->kode_supplier)) }}">
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
                            {{ $supplier->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateSupplier" size="" show="loadcreateSupplier" title="Tambah Supplier" />
<x-modal-form id="mdleditSupplier" size="" show="loadeditSupplier" title="Edit Supplier" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateSupplier").click(function(e) {
            $('#mdlcreateSupplier').modal("show");
            $("#loadcreateSupplier").load('/supplier/create');
        });

        $(".editSupplier").click(function(e) {
            var kode_supplier = $(this).attr("kode_supplier");
            e.preventDefault();
            $('#mdleditSupplier').modal("show");
            $("#loadeditSupplier").load('/supplier/' + kode_supplier + '/edit');
        });
    });
</script>
@endpush
