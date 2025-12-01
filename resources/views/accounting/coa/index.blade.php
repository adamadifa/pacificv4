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
                        <style>
                            .deleteform {
                                display: inline-block;
                                margin: 0;
                                padding: 0;
                            }
                        </style>
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Akun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Memulai perulangan untuk menampilkan setiap baris akun --}}
                                {{-- Loop semua akun yang dikirim dari controller --}}
                                @foreach ($allAccounts as $account)
                                    <tr>
                                        <td>
                                            <span style="padding-left: {{ ($account->level ?? 0) * 25 }}px;">
                                                {{ $account->kode_akun }} - {{ $account->nama_akun }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @can('coa.edit')
                                                    <div>
                                                        <a href="#" class="me-2 btnEdit"
                                                            kode_akun="{{ Crypt::encrypt($account->kode_akun) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('coa.delete')
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('coa.delete', Crypt::encrypt($account->kode_akun)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm">
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
