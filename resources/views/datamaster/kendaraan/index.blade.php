@extends('layouts.app')
@section('titlepage', 'Kendaraan')

@section('content')
@section('navigasi')
    <span>Kendaraan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('kendaraan.create')
                    <a href="#" class="btn btn-primary" id="btncreateKendaraan"><i class="fa fa-plus me-2"></i> Tambah
                        Kendaraan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('kendaraan.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari No. Polisi" value="{{ Request('no_polisi') }}"
                                        name="no_polisi" icon="ti ti-barcode" />
                                </div>
                                @hasanyrole($roles_show_cabang)
                                    <div class="col-lg-4 col-sm-12 col-md-12">
                                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                                            textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" />
                                    </div>
                                @endhasanyrole
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i
                                            class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>No. Polisi</th>
                                        <th>Merk</th>
                                        <th>Type</th>
                                        <th>Tahun</th>
                                        <th>KIR</th>
                                        <th>Pajak 1 Th</th>
                                        <th>Pajak 5 Th</th>
                                        <th>Cabang</th>
                                        <th>Kapasitas</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kendaraan as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $kendaraan->firstItem() - 1 }}</td>
                                            <td>{{ $d->kode_kendaraan }}</td>
                                            <td>{{ $d->no_polisi }}</td>
                                            <td>{{ $d->merek }}</td>
                                            <td>{{ $d->tipe_kendaraan }}</td>

                                            <td>{{ $d->tahun_pembuatan }}</td>
                                            <td>{{ !empty($d->jatuhtempo_kir) ? date('d-m-Y', strtotime($d->jatuhtempo_kir)) : '' }}
                                            </td>
                                            <td>{{ !empty($d->jatuhtempo_pajak_satutahun) ? date('d-m-Y', strtotime($d->jatuhtempo_pajak_satutahun)) : '' }}
                                            </td>
                                            <td>{{ !empty($d->jatuhtempo_pajak_limatahun) ? date('d-m-Y', strtotime($d->jatuhtempo_pajak_limatahun)) : '' }}
                                            </td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>{{ formatRupiah($d->kapasitas) }}</td>
                                            <td>
                                                @if ($d->status_aktif_kendaraan == 1)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('kendaraan.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editKendaraan"
                                                                kode_kendaraan="{{ Crypt::encrypt($d->kode_kendaraan) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('kendaraan.show')
                                                        <div>
                                                            <a href="{{ route('kendaraan.show', Crypt::encrypt($d->kode_kendaraan)) }}"
                                                                class="me-2">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('kendaraan.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('kendaraan.delete', Crypt::encrypt($d->kode_kendaraan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
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
                        <div style="float: right;">
                            {{ $kendaraan->links() }}
                        </div>
                    </div>
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
