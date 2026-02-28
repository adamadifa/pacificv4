@extends('layouts.app')
@section('titlepage', 'Transit IN')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Transit IN</h4>
            <small class="text-muted">Penerimaan barang transit (Transit IN) di gudang cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Cabang</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-transfer-in me-1"></i>Transit IN</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasigudangcabang')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('transitin.index') }}" id="formSearch">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2">
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" />
                            </div>
                        @endrole
                        <div class="{{ auth()->user()->hasAnyRole($roles_show_cabang) ? 'col-lg-2 col-md-2' : 'col-lg-6 col-md-4' }} col-sm-12">
                            {{-- Spacer --}}
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 text-end">
                            <div class="form-group mb-1">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-transfer-in me-2"></i>Data Transit IN</h6>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. SURAT JALAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">CABANG</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TRANSIT OUT</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TRANSIT IN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($transit_in as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->no_surat_jalan }}</span></td>
                                <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                <td>{{ DateToIndo($d->tgl_transit_out) }}</td>
                                <td>
                                    @if (!empty($d->tgl_transit_in))
                                        <span class="badge bg-label-success">{{ DateToIndo($d->tgl_transit_in) }}</span>
                                    @else
                                        <i class="ti ti-refresh text-warning" data-bs-toggle="tooltip" title="Menunggu Penerimaan"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @if (!empty($d->tgl_transit_in))
                                            @can('transitin.delete')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('transitin.delete', Crypt::encrypt($d->no_surat_jalan)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="cancel-confirm bg-transparent border-0 text-warning p-0"
                                                        data-bs-toggle="tooltip" title="Batalkan Transit IN">
                                                        <i class="ti ti-square-rounded-minus fs-5"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @else
                                            @can('transitin.create')
                                                <a href="#" class="btnCreate text-primary" data-bs-toggle="tooltip" title="Terima"
                                                    no_surat_jalan="{{ Crypt::encrypt($d->no_surat_jalan) }}">
                                                    <i class="ti ti-external-link fs-5"></i>
                                                </a>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $transit_in->links() }}
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

      function loading() {
         $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
      };
      $(".btnCreate").click(function(e) {
         e.preventDefault();
         const no_surat_jalan = $(this).attr("no_surat_jalan");
         loading();
         $("#modal").modal("show");
         $(".modal-title").text("Approve Transit IN");
         $("#loadmodal").load(`/transitin/${no_surat_jalan}/create`);
      });
   });
</script>
@endpush
