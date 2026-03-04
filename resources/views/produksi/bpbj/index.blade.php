@extends('layouts.app')
@section('titlepage', 'Bukti Penyerahan Barang Jadi (BPBJ)')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Bukti Penyerahan Barang Jadi (BPBJ)</h4>
            <small class="text-muted">Mengelola data penyerahan barang jadi dari produksi ke gudang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Produksi</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-package-import me-1"></i>BPBJ</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-6 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasiproduksi')
        </div>

        {{-- Filter Section (Below Navigation) --}}
        <form action="{{ route('bpbj.index') }}">
            <div class="row g-2 align-items-end mb-3">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Tanggal Mutasi"
                        value="{{ Request('tanggal_mutasi_search') }}" name="tanggal_mutasi_search"
                        icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Tab Content / Data Card Area --}}
        <div class="tab-content p-0 shadow-none bg-transparent">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                {{-- Data Card --}}
                <div class="card shadow-sm border">
                    <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-white"><i class="ti ti-package-import me-2"></i>Data BPBJ</h6>
                            @can('bpbj.create')
                                <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btncreateBpbj">
                                    <i class="ti ti-plus me-1"></i> Tambah BPBJ
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-white" style="background-color: #002e65 !important;">NO. BPJB</th>
                                    <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                                    <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($bpbj as $d)
                                    <tr>
                                        <td><span class="fw-bold text-primary">{{ $d->no_mutasi }}</span></td>
                                        <td>{{ date('d-m-Y', strtotime($d->tanggal_mutasi)) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('bpbj.show')
                                                    <a href="#" class="showBpbj text-info" data-bs-toggle="tooltip" title="Detail"
                                                        no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                        <i class="ti ti-file-description fs-5"></i>
                                                    </a>
                                                @endcan
                                                @can('bpbj.delete')
                                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                                        action="{{ route('bpbj.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                            data-bs-toggle="tooltip" title="Hapus">
                                                            <i class="ti ti-trash fs-5"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-2">
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
