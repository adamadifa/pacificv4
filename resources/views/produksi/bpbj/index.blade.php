@extends('layouts.app')
@section('titlepage', 'Bukti Penyerahan Barang Jadi (BPBJ)')

@section('content')
@section('navigasi')
    <span>BPBJ</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-md-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasiproduksi')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('bpbj.create')
                        <a href="#" class="btn btn-primary" id="btncreateBpbj"><i class="fa fa-plus me-2"></i>
                            Tambah BPBJ</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('bpbj.index') }}">
                                <div class="row">
                                    <div class="col-lg-10 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Tanggal Mutasi"
                                            value="{{ Request('tanggal_mutasi_search') }}" name="tanggal_mutasi_search"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>

                                    <div class="col-lg-2 col-sm-12 col-md-12">
                                        <button class="btn btn-primary"><i
                                                class="ti ti-icons ti-search me-1"></i></button>
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
                                                        @can('bpbj.show')
                                                            <div>
                                                                <a href="#" class="me-2 showBpbj"
                                                                    no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                                    <i class="ti ti-file-description text-info"></i>
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
</div>

<x-modal-form id="mdlcreateBpbj" size="modal-lg" show="loadcreateBpbj" title="Tambah BPBJ " />
<x-modal-form id="mdldetailBpbj" size="modal-lg" show="loaddetailBpbj" title="Detail BPBJ " />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $("#btncreateBpbj").click(function(e) {
            $('#mdlcreateBpbj').modal("show");
            $("#loadcreateBpbj").load('/bpbj/create');
        });

        $(".showBpbj").click(function(e) {
            var no_mutasi = $(this).attr("no_mutasi");
            e.preventDefault();
            $('#mdldetailBpbj').modal("show");
            $("#loaddetailBpbj").load('/bpbj/' + no_mutasi + '/show');
        });
    });
</script>
@endpush
