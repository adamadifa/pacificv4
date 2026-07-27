@extends('layouts.app')
@section('titlepage', 'Kategori Diskon')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Kategori Diskon</h4>
            <small class="text-muted">Mengelola kategori & aturan diskon produk.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-percentage me-1"></i>Kategori Diskon</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-12">
        {{-- Filter Section --}}
        <form action="{{ route('kategoridiskon.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari Kategori Diskon" value="{{ Request('nama_kategori') }}" name="nama_kategori"
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-percentage me-2"></i>Data Kategori Diskon</h6>
                    @can('kategoridiskon.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateKategoridiskon"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white" style="width: 80px;">No.</th>
                            <th class="text-white" style="width: 120px;">Kode</th>
                            <th class="text-white">Nama Kategori</th>
                            <th class="text-white text-center" style="width: 150px;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($kategoridiskon as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-semibold text-primary">{{ $d->kode_kategori_diskon }}</span></td>
                                <td>{{ $d->nama_kategori }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        @can('kategoridiskon.show')
                                            <a href="{{ route('kategoridiskon.show', Crypt::encrypt($d->kode_kategori_diskon)) }}" 
                                                class="text-info" data-bs-toggle="tooltip" title="Detail Aturan Diskon">
                                                <i class="ti ti-eye"></i> Detail
                                            </a>
                                        @endcan
                                        @can('kategoridiskon.edit')
                                            <a href="#" class="editKategoridiskon text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_kategori_diskon="{{ Crypt::encrypt($d->kode_kategori_diskon) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('kategoridiskon.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('kategoridiskon.delete', Crypt::encrypt($d->kode_kategori_diskon)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Data Kategori Diskon belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlcreateKategoridiskon" size="" show="loadcreateKategoridiskon" title="Tambah Kategori Diskon" />
<x-modal-form id="mdleditKategoridiskon" size="" show="loadeditKategoridiskon" title="Edit Kategori Diskon" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btncreateKategoridiskon").click(function(e) {
            e.preventDefault();
            $('#mdlcreateKategoridiskon').modal("show");
            $("#loadcreateKategoridiskon").load('/kategoridiskon/create');
        });

        $(".editKategoridiskon").click(function(e) {
            var kode_kategori_diskon = $(this).attr("kode_kategori_diskon");
            e.preventDefault();
            $('#mdleditKategoridiskon').modal("show");
            $("#loadeditKategoridiskon").load('/kategoridiskon/' + kode_kategori_diskon + '/edit');
        });

        $('.delete-confirm').click(function(event) {
            var form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
