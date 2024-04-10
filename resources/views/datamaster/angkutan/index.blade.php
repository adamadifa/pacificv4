@extends('layouts.app')
@section('titlepage', 'Angkutan')

@section('content')
@section('navigasi')
    <span>Angkutan</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('angkutan.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                        Angkutan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('angkutan.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon icon="ti ti-search" label="Cari Nama Angkutan"
                                        name="nama_angkutan_search" value="{{ Request('nama_angkutan_search') }}" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i
                                            class="ti ti-icons ti-search me-1 w-100"></i>Cari</button>
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
                                        <th>Kode</th>
                                        <th>Nama Angkutan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($angkutan as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $angkutan->firstItem() - 1 }}</td>
                                            <td>{{ $d->kode_angkutan }}</td>
                                            <td>{{ $d->nama_angkutan }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('angkutan.edit')
                                                        <div>
                                                            <a href="#" class="me-2 btnEdit"
                                                                kode_angkutan="{{ Crypt::encrypt($d->kode_angkutan) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('angkutan.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('angkutan.delete', Crypt::encrypt($d->kode_angkutan)) }}">
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
                            {{ $angkutan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Tambah Angkutan" />
<x-modal-form id="mdlEdit" size="" show="loadEdit" title="Edit Angkutan" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#mdlCreate').modal("show");
            $("#loadCreate").load('/angkutan/create');
        });

        $(".btnEdit").click(function(e) {
            var kode_angkutan = $(this).attr("kode_angkutan");
            e.preventDefault();
            $('#mdlEdit').modal("show");
            $("#loadEdit").load('/angkutan/' + kode_angkutan + '/edit');
        });
    });
</script>
@endpush
