@extends('layouts.app')
@section('titlepage', 'Harga')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Harga</h4>
            <small class="text-muted">Mengelola daftar harga produk per cabang dan kategori.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-currency-dollar me-1"></i>Harga</li>
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
        left: 140px;
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

    /* background and z-index for headers - NO VERTICAL STICKY */
    thead th {
        background-color: #002e65 !important;
    }

    thead th.freeze-1,
    thead th.freeze-2,
    thead th.freeze-3,
    thead th.freeze-last {
        z-index: 3 !important;
        background-color: #002e65 !important;
        position: sticky;
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
</style>
<div class="row">
    <div class="col-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('harga.index') }}">
            <div class="row g-2 mb-1 align-items-end">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <x-input-with-icon label="Cari Nama Produk" value="{{ Request('nama_produk') }}" name="nama_produk"
                        icon="ti ti-search" hideLabel="true" />
                </div>
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                            selected="{{ Request('kode_cabang') }}" hideLabel="true" />
                    </div>
                @endhasanyrole
                <div class="col">
                    <x-select label="Kategori" name="kode_kategori_salesman" :data="$kategorisalesman" key="kode_kategori_salesman"
                        textShow="nama_kategori_salesman" selected="{{ Request('kode_kategori_salesman') }}" hideLabel="true" />
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="ti ti-search me-1"></i>Cari</button>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-currency-dollar me-2"></i>Data Harga</h6>
                    @can('harga.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateHarga"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr>
                            <th class="text-white freeze-1">NO.</th>
                            <th class="text-white freeze-2">KODE</th>
                            <th class="text-white freeze-3">NAMA PRODUK</th>
                            <th class="text-white">SATUAN</th>
                            <th class="text-white text-end">HARGA/DUS</th>
                            <th class="text-white text-end">HARGA/PACK</th>
                            <th class="text-white text-end">HARGA/PCS</th>
                            <th class="text-white text-center">KATEGORI</th>
                            <th class="text-white text-center">PROMO</th>
                            <th class="text-white text-center">PPN</th>
                            <th class="text-white text-center">STATUS</th>
                            <th class="text-white text-center">CABANG</th>
                            <th class="text-white text-center freeze-last">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($harga as $d)
                            <tr>
                                <td class="freeze-1">{{ $loop->iteration + $harga->firstItem() - 1 }}</td>
                                <td class="freeze-2"><span class="fw-semibold">{{ $d->kode_harga }}</span></td>
                                <td class="freeze-3">{{ $d->nama_produk }}</td>
                                <td>{{ $d->satuan }}</td>
                                <td class="text-end fw-bold text-primary">{{ formatRupiah($d->harga_dus) }}</td>
                                <td class="text-end fw-bold text-info">{{ formatRupiah($d->harga_pack) }}</td>
                                <td class="text-end fw-bold text-success">{{ formatRupiah($d->harga_pcs) }}</td>
                                <td class="text-center">{{ $d->kode_kategori_salesman }}</td>
                                <td class="text-center">
                                    @if ($d->status_promo == 1)
                                        <i class="ti ti-circle-check text-success"></i>
                                    @else
                                        <i class="ti ti-circle-x text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($d->status_ppn == 'IN')
                                        <span class="badge bg-success" style="font-size: 0.7rem;">INCLUDE</span>
                                    @elseif($d->status_ppn == 'EX')
                                        <span class="badge bg-danger" style="font-size: 0.7rem;">EXCLUDE</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($d->status_aktif_harga == 1)
                                        <span class="badge bg-success" style="font-size: 0.7rem;">Aktif</span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 0.7rem;">Non Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center"><span class="badge bg-secondary">{{ $d->kode_cabang }}</span></td>
                                <td class="freeze-last">
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('harga.edit')
                                            <a href="#" class="editHarga text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_harga="{{ Crypt::encrypt($d->kode_harga) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('harga.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('harga.delete', Crypt::encrypt($d->kode_harga)) }}">
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
                    {{ $harga->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateHarga" size="modal-lg" show="loadcreateHarga" title="Tambah Harga" />
<x-modal-form id="mdleditHarga" size="" show="loadeditHarga" title="Edit Harga" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btncreateHarga").click(function(e) {
            $('#mdlcreateHarga').modal("show");
            $("#loadcreateHarga").load('/harga/create');
        });

        $(".editHarga").click(function(e) {
            var kode_harga = $(this).attr("kode_harga");
            e.preventDefault();
            $('#mdleditHarga').modal("show");
            $("#loadeditHarga").load('/harga/' + kode_harga + '/edit');
        });
    });
</script>
@endpush
