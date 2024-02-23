@extends('layouts.app')
@section('titlepage', 'Bukti Penyerahan Barang Jadi (BPBJ)')

@section('content')
@section('navigasi')
    <span>BPBJ</span>
@endsection

<div class="row">
    <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('bpjstenagakerja.create')
                    <a href="#" class="btn btn-primary" id="btncreateBpjsTenagaKerja"><i class="fa fa-plus me-2"></i>
                        Tambah BPBJ</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('bpbj.index') }}">


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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{-- {{ $bpjstenagakerja->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateBpbj" size="" show="loadcreateBpbj" title="Tambah BPBJ " />
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
