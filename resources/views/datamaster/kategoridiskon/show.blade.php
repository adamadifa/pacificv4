@extends('layouts.app')
@section('titlepage', 'Detail Aturan Diskon')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Detail Aturan Diskon</h4>
            <small class="text-muted">Mengatur batas minimal/maksimal kuantitas produk dan potongan harga terkait.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('kategoridiskon.index') }}">Kategori Diskon</a>
                </li>
                <li class="breadcrumb-item active">Detail Aturan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-12 mb-4">
        <a href="{{ route('kategoridiskon.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Kategori Info Card --}}
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-info-circle me-2"></i>Informasi Kategori</h6>
            </div>
            <div class="card-body py-4">
                <table class="table table-borderless">
                    <tr>
                        <th class="ps-0 py-2" style="width: 140px;">Kode Kategori</th>
                        <td class="py-2">: <span class="fw-semibold text-primary ms-2">{{ $kategoridiskon->kode_kategori_diskon }}</span></td>
                    </tr>
                    <tr>
                        <th class="ps-0 py-2">Nama Kategori</th>
                        <td class="py-2">: <span class="ms-2">{{ $kategoridiskon->nama_kategori }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Detail Rules Card --}}
    <div class="col-lg-8 col-md-12">
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-percentage me-2"></i>Aturan Kuantitas & Potongan</h6>
                    <a href="#" class="btn btn-primary btn-sm" id="btncreateDetail"
                        kode_kategori_diskon="{{ Crypt::encrypt($kategoridiskon->kode_kategori_diskon) }}">
                        <i class="ti ti-plus me-1"></i> Tambah Aturan
                    </a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white" style="width: 80px;">No.</th>
                            <th class="text-white">Min. Qty (Dus/Pcs)</th>
                            <th class="text-white">Max. Qty (Dus/Pcs)</th>
                            <th class="text-white">Diskon Kredit (Rp)</th>
                            <th class="text-white">Diskon Tunai (Rp)</th>
                            <th class="text-white text-center" style="width: 120px;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($diskons as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge bg-label-primary px-3">{{ number_format($d->min_qty) }}</span></td>
                                <td><span class="badge bg-label-primary px-3">{{ number_format($d->max_qty) }}</span></td>
                                <td class="fw-semibold">Rp {{ number_format($d->diskon) }}</td>
                                <td class="fw-semibold text-success">Rp {{ number_format($d->diskon_tunai) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        <a href="#" class="editDetail text-primary" data-bs-toggle="tooltip" title="Edit"
                                            diskon_id="{{ Crypt::encrypt($d->id) }}">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('kategoridiskon.destroydetail', Crypt::encrypt($d->id)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada aturan diskon untuk kategori ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlcreateDetail" size="" show="loadcreateDetail" title="Tambah Aturan Diskon" />
<x-modal-form id="mdleditDetail" size="" show="loadeditDetail" title="Edit Aturan Diskon" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btncreateDetail").click(function(e) {
            var kode_kategori_diskon = $(this).attr("kode_kategori_diskon");
            e.preventDefault();
            $('#mdlcreateDetail').modal("show");
            $("#loadcreateDetail").load('/kategoridiskon/' + kode_kategori_diskon + '/createdetail');
        });

        $(".editDetail").click(function(e) {
            var diskon_id = $(this).attr("diskon_id");
            e.preventDefault();
            $('#mdleditDetail').modal("show");
            $("#loadeditDetail").load('/kategoridiskon/' + diskon_id + '/editdetail');
        });

        $('.delete-confirm').click(function(event) {
            var form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Aturan diskon ini akan dihapus permanen!",
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
