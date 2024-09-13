@extends('layouts.app')
@section('titlepage', 'Service Kendaraan')

@section('content')
@section('navigasi')
    <span>Service Kendaraan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('servicekendaraan.create')
                    <a href="{{ route('servicekendaraan.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Input Service
                        Kendaraan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('servicekendaraan.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <select name="kode_kendaraan_search" id="kode_kendaraan_search"
                                            class="form-select select2Kodekendaraansearch">
                                            <option value=""> Pilih Kendaraan</option>
                                            @foreach ($kendaraan as $d)
                                                <option {{ Request('kode_kendaraan_search') == $d->kode_kendaraan ? 'selected' : '' }}
                                                    value="{{ $d->kode_kendaraan }}">
                                                    {{ $d->no_polisi }} {{ $d->merek }}
                                                    {{ $d->tipe_kendaraan }} {{ $d->tipe }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search me-2"></i> Cari Data</button>
                                    </div>
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
                                        <th>No. Invoice</th>
                                        <th>Tanggal</th>
                                        <th>No. Polisi</th>
                                        <th>Kendaraan</th>
                                        <th>Bengkel</th>
                                        <th>Cabang</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($servicekendaraan as $d)
                                        <tr>
                                            <td>{{ $d->no_invoice }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->no_polisi }}</td>
                                            <td>{{ $d->merek }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                            <td>{{ $d->nama_bengkel }}</td>
                                            <td>{{ textupperCase($d->nama_cabang) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('servicekendaraan.show')
                                                        <a href="#" class="btnShow" kode_service="{{ Crypt::encrypt($d->kode_service) }}"
                                                            title="Detail">
                                                            <i class="ti ti-file-description text-info"></i>
                                                        </a>
                                                    @endcan
                                                    @can('servicekendaraan.delete')
                                                        <form action="{{ route('servicekendaraan.delete', Crypt::encrypt($d->kode_service)) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
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
                        <div style="float: right;">
                            {{ $servicekendaraan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        const select2Kodekendaraansearch = $('.select2Kodekendaraansearch');
        if (select2Kodekendaraansearch.length) {
            select2Kodekendaraansearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Kendaraan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }



        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Mutasi Kendaraan");
            $("#loadmodal").load(`/mutasikendaraan/create`);
        });


        $(".btnShow").click(function(e) {
            var kode_service = $(this).attr("kode_service");
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Detail Service Kendaraan");
            $("#loadmodal").load(`/servicekendaraan/${kode_service}/show`);
            $("#modal").find(".modal-dialog").addClass("modal-xl");
        });

    });
</script>
@endpush
