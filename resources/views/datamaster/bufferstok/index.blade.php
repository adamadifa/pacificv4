@extends('layouts.app')
@section('titlepage', 'Buffer & Max Stok')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Buffer & Max Stok</h4>
            <small class="text-muted">Mengelola batas stok minimum dan maksimum produk per cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-layers-intersect me-1"></i>Buffer Stok</li>
            </ol>
        </nav>
    </div>
@endsection

<form action="{{ route('bufferstok.update') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row mb-3">
        <div class="col-lg-6 col-md-12 col-sm-12">
            {{-- Filter Section (No Card) --}}
            @hasanyrole($roles_show_cabang)
                <x-select label="Cabang" name="kode_cabang" id="kode_cabang" :data="$cabang" key="kode_cabang"
                    textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" hideLabel="true" />
            @endhasanyrole
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-sm-12 col-xs-12">
            {{-- Data Card --}}
            <div class="card shadow-sm border mt-2">
                <div class="card-header border-bottom py-3"
                    style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-white"><i class="ti ti-layers-intersect me-2"></i>Data Buffer Stok</h6>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-white" style="background-color: #002e65 !important;">KODE PRODUK</th>
                                <th class="text-white" style="background-color: #002e65 !important;">NAMA PRODUK</th>
                                <th class="text-white text-center" style="background-color: #002e65 !important;">BUFFER STOK</th>
                                <th class="text-white text-center" style="background-color: #002e65 !important;">MAX. STOK</th>
                            </tr>
                        </thead>
                        <tbody id="loadbufferstok">
                            {{-- Content loaded via AJAX --}}
                        </tbody>
                    </table>
                </div>
                <div class="card-footer border-top py-3">
                    <button class="btn btn-primary w-100 shadow-sm" type="submit">
                        <i class="ti ti-device-floppy me-1"></i>
                        Update Buffer Stok
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@push('myscript')
<script>
    $(function() {
        function loadbufferstok(kode_cabang = "PST") {
            $("#loadbufferstok").load('/bufferstok/' + kode_cabang + '/getbufferstok');
        }

        loadbufferstok();

        $("#kode_cabang").change(function() {
            const kode_cabang = $(this).val();
            loadbufferstok(kode_cabang);
        });
    });
</script>
@endpush
