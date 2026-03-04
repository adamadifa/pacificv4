@extends('layouts.app')
@section('titlepage', 'Produk')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Produk</h4>
            <small class="text-muted">Mengelola daftar produk, kategori, dan informasi stok satuan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-box me-1"></i>Produk</li>
            </ol>
        </nav>
    </div>
@endsection
<style>
    .freeze-1 {
        position: sticky;
        left: 0;
        z-index: 2;
    }

    .freeze-2 {
        position: sticky;
        left: 45px;
        z-index: 2;
    }

    .freeze-3 {
        position: sticky;
        left: 110px;
        z-index: 2;
    }

    .freeze-last {
        position: sticky;
        right: 0;
        z-index: 2;
        border-left: 1px solid #dee2e6;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05);
    }

    /* background color for body cells to avoid transparency */
    tbody td.freeze-1,
    tbody td.freeze-2,
    tbody td.freeze-3,
    tbody td.freeze-last {
        background-color: #fff !important;
    }

    /* background and z-index for headers */
    thead th {
        position: sticky;
        top: 0;
        z-index: 4 !important;
        background-color: #002e65 !important;
    }

    thead th.freeze-1,
    thead th.freeze-2,
    thead th.freeze-3,
    thead th.freeze-last {
        z-index: 5 !important;
        background-color: #002e65 !important;
    }

    /* Handle striped rows background color for frozen cells */
    .table-striped tbody tr:nth-of-type(odd) td.freeze-1,
    .table-striped tbody tr:nth-of-type(odd) td.freeze-2,
    .table-striped tbody tr:nth-of-type(odd) td.freeze-3,
    .table-striped tbody tr:nth-of-type(odd) td.freeze-last {
        background-color: #f9f9f9 !important;
    }

    /* Handle hover background color for frozen cells */
    .table-hover tbody tr:hover td.freeze-1,
    .table-hover tbody tr:hover td.freeze-2,
    .table-hover tbody tr:hover td.freeze-3,
    .table-hover tbody tr:hover td.freeze-last {
        background-color: #f5f5f5 !important;
    }

    .table-container {
        max-height: 450px;
        overflow-y: auto;
    }
</style>
<div class="row">
    <div class="col-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('produk.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari Nama Produk" value="{{ Request('nama_produk') }}" name="nama_produk"
                        icon="ti ti-search" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-box me-2"></i>Data Produk</h6>
                    @can('produk.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateProduk"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-container table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr>
                            <th class="text-white freeze-1">NO.</th>
                            <th class="text-white freeze-2">KODE</th>
                            <th class="text-white freeze-3">NAMA PRODUK</th>
                            <th class="text-white">SATUAN</th>
                            <th class="text-white text-center">PCS / DUS</th>
                            <th class="text-white text-center">PACK / DUS</th>
                            <th class="text-white text-center">PCS / PACK</th>
                            <th class="text-white">JENIS PRODUK</th>
                            <th class="text-white">KATEGORI</th>
                            <th class="text-white">SKU</th>
                            <th class="text-white">DISKON</th>
                            <th class="text-white text-center">STATUS</th>
                            <th class="text-white text-center freeze-last">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($produk as $d)
                            <tr>
                                <td class="freeze-1">{{ $loop->iteration }}</td>
                                <td class="freeze-2"><span class="fw-semibold">{{ $d->kode_produk }}</span></td>
                                <td class="freeze-3">{{ $d->nama_produk }}</td>
                                <td>{{ $d->satuan }}</td>
                                <td class="text-center">{{ $d->isi_pcs_dus }}</td>
                                <td class="text-center">{{ $d->isi_pack_dus }}</td>
                                <td class="text-center">{{ $d->isi_pcs_pack }}</td>
                                <td>{{ $d->nama_jenis_produk }}</td>
                                <td>{{ $d->nama_kategori_produk }}</td>
                                <td>{{ $d->kode_sku }}</td>
                                <td>{{ $d->nama_kategori }}</td>
                                <td class="text-center">
                                    @if ($d->status_aktif_produk == 1)
                                        <span class="badge bg-success" style="font-size: 0.7rem;">Aktif</span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 0.7rem;">Non Aktif</span>
                                    @endif
                                </td>
                                <td class="freeze-last">
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('produk.edit')
                                            <a href="#" class="editProduk text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_produk="{{ Crypt::encrypt($d->kode_produk) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('produk.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('produk.delete', Crypt::encrypt($d->kode_produk)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash"></i>
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
                    {{-- {{ $produk->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateProduk" size="" show="loadcreateProduk" title="Tambah Produk" />
<x-modal-form id="mdleditProduk" size="" show="loadeditProduk" title="Edit Produk" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateProduk").click(function(e) {
            $('#mdlcreateProduk').modal("show");
            $("#loadcreateProduk").load('/produk/create');
        });

        $(".editProduk").click(function(e) {
            var kode_produk = $(this).attr("kode_produk");
            e.preventDefault();
            $('#mdleditProduk').modal("show");
            $("#loadeditProduk").load('/produk/' + kode_produk + '/edit');
        });
    });
</script>
@endpush
