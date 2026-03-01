@extends('layouts.app')
@section('titlepage', 'Ganti Logam Ke Kertas')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Ganti Logam ke Kertas</h4>
            <small class="text-muted">Manajemen penukaran uang logam ke uang kertas.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-arrows-exchange-2 me-1"></i>Ganti Logam ke Kertas</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12">
        {{-- Navigation --}}
        <div class="mb-3">
            @include('layouts.navigation_kasbesar')
        </div>

        {{-- Filter Section --}}
        <div class="card shadow-none border-0 bg-transparent mb-4">
            <div class="card-body p-0">
                <form action="{{ route('logamtokertas.index') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-2 col-md-6">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-7 col-md-9">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" />
                            </div>
                        @endrole
                        <div class="col-lg-1 col-md-3">
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Data Table Section --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-table me-2"></i>Data Ganti Logam ke Kertas</h6>
                @can('logamtokertas.create')
                    <a href="#" class="btn btn-primary btn-sm" id="btnCreate">
                        <i class="ti ti-plus me-1"></i>Input Ganti Logam
                    </a>
                @endcan
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">CABANG</th>
                            <th class="text-white" style="background-color: #002e65 !important;">JUMLAH</th>
                            <th class="text-white" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($logamtokertas as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->kode_logamtokertas }}</span></td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                <td class="text-end fw-bold">{{ formatAngka($d->jumlah) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('logamtokertas.edit')
                                            <a href="#" class="btnEdit text-success"
                                                kode_logamtokertas="{{ Crypt::encrypt($d->kode_logamtokertas) }}"
                                                data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit fs-4"></i>
                                            </a>
                                        @endcan
                                        @can('logamtokertas.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('logamtokertas.delete', Crypt::encrypt($d->kode_logamtokertas)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-4"></i>
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
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />

@endsection
@push('myscript')
<script>
    $(function() {
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
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Input Ganti Logam Ke Kertas");
            $("#loadmodal").load(`/logamtokertas/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_logamtokertas = $(this).attr('kode_logamtokertas');
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Edit Ganti Logam Ke Kertas");
            $("#loadmodal").load(`/logamtokertas/${kode_logamtokertas}/edit`);
        });




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
    });
</script>
@endpush
