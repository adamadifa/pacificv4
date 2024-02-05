@extends('layouts.app')
@section('titlepage', 'Wilayah')

@section('content')
@section('navigasi')
    <span>Wilayah</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btncreateWilayah"><i class="fa fa-plus me-2"></i> Tambah
                    Wilayah</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('wilayah.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Search Name" value="{{ Request('name') }}" name="name"
                                        icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary">Cari</button>
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
                                        <th>No.</th>
                                        <th>Kode Wilayah</th>
                                        <th>Nama Wilayah</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wilayah as $d)
                                        <tr>
                                            <td> {{ $loop->iteration }}</td>
                                            <td>{{ $d->kode_wilayah }}</td>
                                            <td>{{ $d->nama_wilayah }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <div>
                                                        <a href="#" class="me-2 editWilayah"
                                                            kode_wilayah="{{ Crypt::encrypt($d->kode_wilayah) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('wilayah.delete', Crypt::encrypt($d->kode_wilayah)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm ml-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{-- {{ $Wilayahs->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateWilayah" size="" show="loadcreateWilayah" title="Tambah Wilayah" />
<x-modal-form id="mdleditWilayah" size="" show="loadeditWilayah" title="Edit Wilayah" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateWilayah").click(function(e) {
            $('#mdlcreateWilayah').modal("show");
            $("#loadcreateWilayah").load('/wilayah/create');
        });

        $(".editWilayah").click(function(e) {
            var kode_wilayah = $(this).attr("kode_wilayah");
            e.preventDefault();
            $('#mdleditWilayah').modal("show");
            $("#loadeditWilayah").load('/wilayah/' + kode_wilayah + '/edit');
        });
    });
</script>
@endpush
