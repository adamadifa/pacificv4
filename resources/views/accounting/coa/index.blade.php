@extends('layouts.app')
@section('titlepage', 'Chart of Account (COA)')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Chart of Account (COA)</h4>
            <small class="text-muted">Manajemen kode akun dan struktur laporan keuangan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Accounting</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-settings me-1"></i>COA</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-12 col-12">
        <div class="card shadow-none border">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-list me-2"></i>Daftar Akun</h6>
                <div>
                    @can('coa.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i>Tambah</a>
                    @endcan
                    <a href="{{ route('coa.export') }}" class="btn btn-success btn-sm"><i class="ti ti-download me-1"></i>Excel</a>
                </div>
            </div>
            <div class="card-body p-0">
                <style>
                    .deleteform {
                        display: inline-block;
                        margin: 0;
                        padding: 0;
                    }
                </style>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="py-3">KODE & NAMA AKUN</th>
                                <th class="py-3 text-center" style="width: 100px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allAccounts as $account)
                                <tr>
                                    <td>
                                        <span style="padding-left: {{ ($account->level ?? 0) * 25 }}px;" class="fw-medium">
                                            {{ $account->kode_akun }} - {{ $account->nama_akun }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            @can('coa.edit')
                                                <a href="#" class="me-2 btnEdit"
                                                    kode_akun="{{ Crypt::encrypt($account->kode_akun) }}">
                                                    <i class="ti ti-edit text-success fs-5"></i>
                                                </a>
                                            @endcan
                                            @can('coa.delete')
                                                <form method="POST" name="deleteform" class="deleteform"
                                                    action="{{ route('coa.delete', Crypt::encrypt($account->kode_akun)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm">
                                                        <i class="ti ti-trash text-danger fs-5"></i>
                                                    </a>
                                                </form>
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
