@extends('layouts.app')
@section('titlepage', 'Kas Kecil')

@section('content')
@section('navigasi')
    <span>Klaim Kas Kecil</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_kaskecil')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('klaimkaskecil.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Buat Klaim
                        </a>
                    @endcan

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('klaimkaskecil.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                </div>

                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                                textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                                select2="select2Kodecabangsearch" />
                                        </div>
                                    </div>
                                @endrole
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
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th style="width:35% ">Keterangan</th>
                                            <th>Status</th>
                                            <th>No.Bukti</th>
                                            <th>Diproses</th>
                                            <th>Validasi</th>
                                            <th>Jumlah</th>
                                            <th>#</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach ($klaimkaskecil as $d)
                                            <tr>
                                                <td>{{ $d->kode_klaim }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->keterangan }}</td>
                                                <td class="text-center">
                                                    @if ($d->status == 0)
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @else
                                                        <i class="ti ti-checkbox text-success"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!empty($d->no_bukti))
                                                        <span class="badge bg-primary">
                                                            {{ $d->no_bukti }}
                                                        </span>
                                                    @else
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>{{ formatIndo($d->tgl_proses) }}</td>
                                                <td class="text-center">
                                                    @if (!@empty($d->cekvalidasi))
                                                        <i class="ti ti-checks text-success"></i>
                                                    @else
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @endif
                                                </td>
                                                <td class="{{ !empty($d->no_bukti) ? 'text-end' : 'text-center' }}">
                                                    @if (!empty($d->no_bukti))
                                                        {{ formatAngka($d->jumlah) }}
                                                    @else
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('klaimkaskecil.show')
                                                            <a href="{{ route('klaimkaskecil.cetak', ['kode_klaim' => Crypt::encrypt($d->kode_klaim), 'export' => 0]) }}"
                                                                target="_blank" class="me-1">
                                                                <i class="ti ti-printer text-primary"></i>
                                                            </a>
                                                        @endcan
                                                        @can('klaimkaskecil.show')
                                                            <a href="{{ route('klaimkaskecil.cetak', ['kode_klaim' => Crypt::encrypt($d->kode_klaim), 'export' => true]) }}"
                                                                class="me-1" target="_blank">
                                                                <i class="ti ti-download text-success"></i>
                                                            </a>
                                                        @endcan
                                                        @can('klaimkaskecil.proses')
                                                            @if (empty($d->no_bukti))
                                                                <a href="#" class="btnProses me-1"
                                                                    kode_klaim="{{ Crypt::encrypt($d->kode_klaim) }}">
                                                                    <i class="ti ti-external-link text-info"></i>
                                                                </a>
                                                            @else
                                                                @if (empty($d->cekvalidasi))
                                                                    <form method="POST" name="deleteform" class="deleteform"
                                                                        action="{{ route('klaimkaskecil.cancelproses', Crypt::encrypt($d->no_bukti)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="cancel-confirm me-1">
                                                                            <i class="ti ti-square-rounded-x text-danger"></i>
                                                                        </a>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        @endcan
                                                        @can('klaimkaskecil.approve')
                                                            @if (!empty($d->no_bukti) && empty($d->cekvalidasi))
                                                                <a href="{{ route('klaimkaskecil.approve', Crypt::encrypt($d->no_bukti)) }}">
                                                                    <i class="ti ti-square-rounded-check text-success"></i>
                                                                </a>
                                                            @else
                                                                @if (!empty($d->cekvalidasi))
                                                                    <form method="POST" name="deleteform" class="deleteform"
                                                                        action="{{ route('klaimkaskecil.cancelapprove', Crypt::encrypt($d->no_bukti)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="cancel-confirm me-1">
                                                                            <i class="ti ti-square-x text-danger"></i>
                                                                        </a>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        @endcan
                                                        @can('klaimkaskecil.delete')
                                                            @if (empty($d->no_bukti))
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('klaimkaskecil.delete', Crypt::encrypt($d->kode_klaim)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm me-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            @endif
                                                        @endcan

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{ $klaimkaskecil->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xxxl" show="loadmodal" title="" />
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
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Buat Klaim');
            $("#loadmodal").load('/klaimkaskecil/create');
        });

        $(".btnProses").click(function(e) {
            e.preventDefault();
            loading();
            const kode_klaim = $(this).attr('kode_klaim');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Proses Kas Kecil');
            $("#modal").find("#loadmodal").load(`/klaimkaskecil/${kode_klaim}/proses`);
        });

    });
</script>
@endpush
