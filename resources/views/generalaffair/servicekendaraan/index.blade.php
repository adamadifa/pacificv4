@extends('layouts.app')
@section('titlepage', 'Service Kendaraan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Service Kendaraan</h4>
            <small class="text-muted">Mengelola riwayat service dan perawatan kendaraan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>General Affair</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-settings me-1"></i>Service Kendaraan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- Filter Section --}}
        <form action="{{ route('servicekendaraan.index') }}">
            <div class="row g-2 mb-2 align-items-end">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-4 col-md-10 col-sm-12">
                    @php
                        $kendaraan_data = $kendaraan->map(function ($d) {
                            return (object) [
                                'kode_kendaraan' => $d->kode_kendaraan,
                                'nama_kendaraan' => $d->no_polisi . ' ' . $d->merek . ' ' . $d->tipe_kendaraan . ' ' . $d->tipe,
                            ];
                        });
                    @endphp
                    <x-select label="Pilih Kendaraan" name="kode_kendaraan_search" :data="$kendaraan_data" key="kode_kendaraan" textShow="nama_kendaraan"
                        selected="{{ Request('kode_kendaraan_search') }}" select2="select2Kodekendaraansearch" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="form-group mb-2">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-settings me-2"></i>Data Service Kendaraan</h6>
                    @can('servicekendaraan.create')
                        <a href="{{ route('servicekendaraan.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Input Service</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. INVOICE</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. POLISI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">KENDARAAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">BENGKEL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">CABANG</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($servicekendaraan as $d)
                            <tr>
                                <td><span class="fw-bold">{{ $d->no_invoice }}</span></td>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td>{{ $d->no_polisi }}</td>
                                <td>{{ $d->merek }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                <td>{{ $d->nama_bengkel }}</td>
                                <td>{{ textupperCase($d->nama_cabang) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('servicekendaraan.show')
                                            <a href="#" class="btnShow text-info" kode_service="{{ Crypt::encrypt($d->kode_service) }}" title="Detail">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('servicekendaraan.delete')
                                            <form action="{{ route('servicekendaraan.delete', Crypt::encrypt($d->kode_service)) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger">
                                                    <i class="ti ti-trash fs-5"></i>
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
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $servicekendaraan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
@endsection

@push('myscript')
<script>
    $(function() {
        const setupSelect2 = (selector, placeholder) => {
            const $el = $(selector);
            if ($el.length) {
                $el.each(function() {
                    const $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: placeholder,
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }
        };

        setupSelect2(".select2Kodekendaraansearch", 'Pilih Kendaraan');

        const loading = () => {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
            </div>`);
        };

        $(".btnShow").click(function(e) {
            e.preventDefault();
            const kode_service = $(this).attr("kode_service");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Detail Service Kendaraan");
            $("#loadmodal").load(`/servicekendaraan/${kode_service}/show`);
            $("#modal").find(".modal-dialog").addClass("modal-xl");
        });
    });
</script>
@endpush
