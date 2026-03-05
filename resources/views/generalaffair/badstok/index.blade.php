@extends('layouts.app')
@section('titlepage', 'Bad Stok')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Bad Stok</h4>
            <small class="text-muted">Mengelola data produk bad stok.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>General Affair</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-package-off me-1"></i>Bad Stok</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-5 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ route('badstokga.index') }}">
            <div class="row g-2 mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
            </div>
            <div class="row g-2 mb-2 align-items-end">
                <div class="col-lg-9 col-md-9 col-sm-12">
                    @php
                        $asal_bad_stok_data = $asalbadstok->map(function ($cabang) {
                            return (object) [
                                'kode_cabang' => $cabang->kode_cabang,
                                'nama_cabang' => textUpperCase($cabang->nama_cabang),
                            ];
                        })->prepend((object) ['kode_cabang' => 'GDG', 'nama_cabang' => 'GUDANG']);
                    @endphp
                    <x-select label="Asal Bad Stok" name="kode_asal_bs_search" :data="$asal_bad_stok_data" key="kode_cabang" textShow="nama_cabang"
                        selected="{{ Request('kode_asal_bs_search') }}" select2="select2Kodeasalbssearch" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group mb-2">
                        <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-package-off me-2"></i>Data Bad Stok</h6>
                    @can('badstokga.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Input Bad Stok</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">KODE BS</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">ASAL BAD STOK</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($badstok as $d)
                            <tr>
                                <td><span class="fw-bold">{{ $d->kode_bs }}</span></td>
                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                <td>{{ $d->kode_asal_bs }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('badstokga.show')
                                            <a href="#" class="btnShow text-info" kode_bs="{{ Crypt::encrypt($d->kode_bs) }}" title="Detail">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('badstokga.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('badstokga.delete', Crypt::encrypt($d->kode_bs)) }}">
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
                    {{ $badstok->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
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

        setupSelect2(".select2Kodeasalbssearch", 'Asal Bad Stok');

        const loading = () => {
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
            $(".modal-title").text("Input Bad Stok");
            $("#loadmodal").load(`/badstokga/create`);
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            const kode_bs = $(this).attr("kode_bs");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Detail Bad Stok");
            $("#loadmodal").load(`/badstokga/${kode_bs}/show`);
        });
    });
</script>
@endpush
