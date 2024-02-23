@extends('layouts.app')
@section('titlepage', 'Bukti Penyerahan Barang Jadi (BPBJ)')

@section('content')
@section('navigasi')
    <span>BPBJ</span>
@endsection

<div class="row">
    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('bpjstenagakerja.create')
                    <a href="#" class="btn btn-primary" id="btncreateBpbj"><i class="fa fa-plus me-2"></i>
                        Tambah BPBJ</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('bpbj.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Tanggal Mutasi" value="{{ Request('tanggal_mutasi') }}"
                                        name="tanggal_mutasi" icon="ti ti-calendar" datepicker="flatpickr-date" />
                                </div>

                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i></button>
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
                                        <th>No. BPJB</th>
                                        <th>Tanggal</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bpbj as $d)
                                        <tr>
                                            <td>{{ $d->no_mutasi }}</td>
                                            <td>{{ date('d-m-Y', strtotime($d->tanggal_mutasi)) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('bpbj.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editBpbj"
                                                                no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('bpbj.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('bpbj.delete', Crypt::encrypt($d->no_mutasi)) }}">
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
                            {{ $bpbj->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateBpbj" size="modal-lg" show="loadcreateBpbj" title="Tambah BPBJ " />
<x-modal-form id="mdleditBpbj" size="" show="loadeditBpbj" title="Edit BPBJ " />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $("#btncreateBpbj").click(function(e) {
            $('#mdlcreateBpbj').modal("show");
            $("#loadcreateBpbj").load('/bpbj/create');
        });

        $(".editBpbj").click(function(e) {
            var no_mutasi = $(this).attr("no_mutasi");
            e.preventDefault();
            $('#mdleditBpbj').modal("show");
            $("#loadeditBpbj").load('/bpbj/' + no_mutasi + '/edit');
        });
    });
</script>
@endpush
