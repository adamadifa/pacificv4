@extends('layouts.app')
@section('titlepage', 'Saldo Kas Besar')

@section('content')
@section('navigasi')
    <span>Saldo Kas Besar</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasikeuangan')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('sakasbesarkeuangan.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Input Saldo Kas Besar
                        </a>
                    @endcan

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('sakasbesarkeuangan.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                @if (!request()->is('sakasbesarkeuanganpusat'))
                                    @hasanyrole($roles_show_cabang)
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <x-select label="Semua Cabang" name="kode_cabang" :data="$cabang"
                                                    key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                                    selected="{{ Request('kode_cabang') }}"
                                                    select2="select2Kodecabangsearch" />
                                            </div>
                                        </div>
                                    @endrole
                                @endif

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-2"></i>Cari
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table  table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Cabang</th>
                                            <th>Keterangan</th>
                                            <th>Saldo</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saldokasbesar as $d)
                                            <tr>
                                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                                <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                                <td>{{ $d->keterangan }}</td>
                                                <td class="text-end">
                                                    {{ formatAngkaDesimal($d->jumlah) }}
                                                </td>
                                                <td>
                                                    <form method="POST" name="deleteform" class="deleteform"
                                                        action="{{ route('sakasbesarkeuangan.delete', Crypt::encrypt($d->id)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm ml-1">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </a>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
<x-modal-form id="modalEdit" show="loadmodalEdit" title="" />

@endsection
@push('myscript')
<script>
    $(function() {

        function loading() {
            $("#loadmodal,#loadmodalEdit").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua  Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }


        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Input Saldo Kas Besar');
            $("#loadmodal").load('/sakasbesarkeuangan/create');
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const id = $(this).attr('id');
            $("#modalEdit").modal("show");
            $("#modalEdit").find(".modal-title").text('Edit Mutasi Keuangan');
            $("#modalEdit").find("#loadmodalEdit").load(`/mutasikeuangan/${id}/edit`);
        });

    });
</script>
@endpush
