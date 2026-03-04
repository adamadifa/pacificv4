@extends('layouts.app')
@section('titlepage', 'Kendaraan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Kendaraan</h4>
            <small class="text-muted">Mengelola armada kendaraan perusahaan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-truck me-1"></i>Kendaraan</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('kendaraan.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Cari No. Polisi" value="{{ Request('no_polisi') }}" name="no_polisi" icon="ti ti-barcode" hideLabel="true" />
                </div>
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                            selected="{{ Request('kode_cabang') }}" select2="select2Kodecabang" hideLabel="true" />
                    </div>
                @endhasanyrole
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-truck me-2"></i>Data Kendaraan</h6>
                    @can('kendaraan.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateKendaraan"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">No.</th>
                            <th class="text-white">Kode</th>
                            <th class="text-white">No. Polisi</th>
                            <th class="text-white">Merk</th>
                            <th class="text-white">Type</th>
                            <th class="text-white">Tahun</th>
                            <th class="text-white text-center">KIR</th>
                            <th class="text-white text-center">Pajak 1 Th</th>
                            <th class="text-white text-center">Pajak 5 Th</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white text-end">Kapasitas</th>
                            <th class="text-white text-center">Status</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($kendaraan as $d)
                            <tr>
                                <td>{{ $loop->iteration + $kendaraan->firstItem() - 1 }}</td>
                                <td><span class="fw-semibold">{{ $d->kode_kendaraan }}</span></td>
                                <td>{{ $d->no_polisi }}</td>
                                <td>{{ $d->merek }}</td>
                                <td>{{ $d->tipe_kendaraan }}</td>
                                <td>{{ $d->tahun_pembuatan }}</td>
                                <td class="text-center">{{ !empty($d->jatuhtempo_kir) ? date('d-m-Y', strtotime($d->jatuhtempo_kir)) : '' }}</td>
                                <td class="text-center">
                                    {{ !empty($d->jatuhtempo_pajak_satutahun) ? date('d-m-Y', strtotime($d->jatuhtempo_pajak_satutahun)) : '' }}
                                </td>
                                <td class="text-center">
                                    {{ !empty($d->jatuhtempo_pajak_limatahun) ? date('d-m-Y', strtotime($d->jatuhtempo_pajak_limatahun)) : '' }}
                                </td>
                                <td>{{ $d->kode_cabang }}</td>
                                <td class="text-end">{{ formatRupiah($d->kapasitas) }}</td>
                                <td class="text-center">
                                    @if ($d->status_aktif_kendaraan == 1)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('kendaraan.edit')
                                            <a href="#" class="editKendaraan text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_kendaraan="{{ Crypt::encrypt($d->kode_kendaraan) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('kendaraan.show')
                                            <a href="{{ route('kendaraan.show', Crypt::encrypt($d->kode_kendaraan)) }}"
                                                text-info" data-bs-toggle="tooltip" title="Detail">
                                                <i class="ti ti-file-description"></i>
                                            </a>
                                        @endcan
                                        @can('kendaraan.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('kendaraan.delete', Crypt::encrypt($d->kode_kendaraan)) }}">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $kendaraan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateKendaraan" size="" show="loadcreateKendaraan" title="Tambah Kendaraan" />
<x-modal-form id="mdleditKendaraan" size="" show="loadeditKendaraan" title="Edit Kendaraan" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateKendaraan").click(function(e) {
            $('#mdlcreateKendaraan').modal("show");
            $("#loadcreateKendaraan").load('/kendaraan/create');
        });

        $(".editKendaraan").click(function(e) {
            var kode_kendaraan = $(this).attr("kode_kendaraan");
            e.preventDefault();
            $('#mdleditKendaraan').modal("show");
            $("#loadeditKendaraan").load('/kendaraan/' + kode_kendaraan + '/edit');
        });
    });
</script>
@endpush
