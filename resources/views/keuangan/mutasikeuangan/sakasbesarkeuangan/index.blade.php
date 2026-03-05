@extends('layouts.app')
@section('titlepage', 'Saldo Kas Besar')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Saldo Kas Besar</h4>
            <small class="text-muted">Manajemen saldo kas besar keuangan cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-wallet me-1"></i>Saldo Kas Besar</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasikeuangan')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('sakasbesarkeuangan.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-3 col-md-6">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        @if (!request()->is('sakasbesarkeuanganpusat'))
                            @hasanyrole($roles_show_cabang)
                                <div class="col-lg-4 col-md-8">
                                    <x-select label="Semua Cabang" name="kode_cabang" :data="$cabang"
                                        key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                        selected="{{ Request('kode_cabang') }}"
                                        select2="select2Kodecabangsearch" hideLabel="true" />
                                </div>
                            @endrole
                        @endif
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary"><i class="ti ti-search me-1"></i>Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-wallet me-2"></i>Data Saldo Kas Besar</h6>
                    @can('sakasbesarkeuangan.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Input Saldo Kas Besar
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 10%; white-space: nowrap;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 1%; white-space: nowrap;">CABANG</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KETERANGAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 1%; white-space: nowrap;">SALDO</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 1%; white-space: nowrap;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($saldokasbesar as $d)
                            <tr>
                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                <td><span class="badge bg-label-primary">{{ textUpperCase($d->nama_cabang) }}</span></td>
                                <td>{{ $d->keterangan }}</td>
                                <td class="text-end fw-bold text-primary">{{ formatAngkaDesimal($d->jumlah) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('sakasbesarkeuangan.delete', Crypt::encrypt($d->id)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash fs-4"></i>
                                            </a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
