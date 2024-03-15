@extends('layouts.app')
@section('titlepage', 'Barang Masuk Produksi')

@section('content')
@section('navigasi')
    <span>Barang Masuk Produksi</span>
@endsection

<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('bpbj.create')
                    <a href="{{ route('barangmasukproduksi.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>
                        Tambah Data</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('barangmasukproduksi.index') }}">


                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>No. Bukti</th>
                                        <th>Tanggal</th>
                                        <th>Asal Barang</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangmasuk as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $barangmasuk->firstItem() - 1 }}</td>
                                            <td>{{ $d->no_bukti }}</td>
                                            <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                            <td>{{ $asal_barang[$d->kode_asal_barang] }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('barangmasukproduksi.edit')
                                                        <div>
                                                            <a href="{{ route('barangmasukproduksi.edit', Crypt::encrypt($d->no_bukti)) }}"
                                                                class="me-2"
                                                                no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('barangmasukproduksi.show')
                                                        <div>
                                                            <a href="#" class="me-2 show"
                                                                no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('barangmasukproduksi.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('barangmasukproduksi.delete', Crypt::encrypt($d->no_bukti)) }}">
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
<x-modal-form id="mdldetail" size="modal-lg" show="loaddetail" title="Detail" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $(".show").click(function(e) {
            var no_bukti = $(this).attr("no_bukti");
            e.preventDefault();
            $('#mdldetail').modal("show");
            $("#loaddetail").load('/barangmasukproduksi/' + no_bukti + '/show');
        });
    });
</script>
@endpush
