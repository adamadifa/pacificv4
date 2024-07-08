@extends('layouts.app')
@section('titlepage', 'Chart of Account (COA)')

@section('content')
@section('navigasi')
    <span>Chart of Account (COA)</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('coa.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="ti ti-plus me-1"></i>Tambah Akun</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Akun</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <ul class="accordion" id="chartAccordion">
                            @foreach ($accountsHierarchy as $account)
                                @include('accounting.coa.account', ['account' => $account])
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Tambah Akun");
            $("#loadmodal").load(`/coa/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_akun = $(this).attr('kode_akun');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Akun");
            $("#loadmodal").load(`/coa/${kode_akun}/edit`);
        });
    });
</script>
@endpush
