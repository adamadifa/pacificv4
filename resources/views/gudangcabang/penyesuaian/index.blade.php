@extends('layouts.app')
@section('titlepage', 'Penyesuaian')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Penyesuaian</h4>
            <small class="text-muted">Kelola data penyesuaian stok barang cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Cabang</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-adjustments me-1"></i>Penyesuaian</li>
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
        <form action="{{ route('penygudangcbg.index') }}" id="formSearch">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2">
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" hideLabel="true" />
                            </div>
                        @endhasanyrole
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group mb-1">
                                <select name="jenis_mutasi_search" id="jenis_mutasi_search" class="form-select">
                                    <option value="">Jenis Mutasi</option>
                                    <option value="PY" {{ Request('jenis_mutasi_search') == 'PY' ? 'selected' : '' }}>PENYESUAIAN GOOD STOK</option>
                                    <option value="PB" {{ Request('jenis_mutasi_search') == 'PB' ? 'selected' : '' }}>PENYESUAIAN BAD STOK</option>
                                </select>
                            </div>
                        </div>
                        <div class="{{ auth()->user()->hasAnyRole($roles_show_cabang) ? 'col-lg-1' : 'col-lg-4' }} col-md-12 col-sm-12 text-end">
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
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-adjustments me-2"></i>Data Penyesuaian</h6>
                    @can('penygudangcbg.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Tambah Data</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. MUTASI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">CABANG</th>
                            <th class="text-white" style="background-color: #002e65 !important;">KETERANGAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">JENIS</th>
                            <th class="text-white" style="background-color: #002e65 !important;">IN/OUT</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($penyesuaian as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->no_mutasi }}</span></td>
                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                <td>{{ textCamelCase($d->keterangan) }}</td>
                                <td>
                                    @if ($d->jenis_mutasi == 'PY')
                                        <span class="badge bg-label-success">GOOD</span>
                                    @else
                                        <span class="badge bg-label-danger">BAD</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $in_out = $d->jenis_mutasi == 'PY' ? $d->in_out_good : $d->in_out_bad;
                                    @endphp
                                    @if ($in_out == 'I')
                                        <span class="badge bg-label-success">IN</span>
                                    @else
                                        <span class="badge bg-label-danger">OUT</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('penygudangcbg.edit')
                                            <a href="#" class="btnEdit text-success" data-bs-toggle="tooltip" title="Edit"
                                                no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('penygudangcbg.show')
                                            <a href="#" class="btnShow text-info" data-bs-toggle="tooltip" title="Detail"
                                                no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('penygudangcbg.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('penygudangcbg.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
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
                    {{ $penyesuaian->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
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
      $("#btnCreate").click(function(e) {
         e.preventDefault();
         loading();
         $("#modal").modal("show");
         $(".modal-title").text("Tambah Data Penyesuaian");
         $("#loadmodal").load(`/penygudangcbg/create`);
      });

      $(".btnShow").click(function(e) {
         e.preventDefault();
         var no_mutasi = $(this).attr("no_mutasi");
         e.preventDefault();
         loading();
         $("#modal").modal("show");
         $(".modal-title").text("Detail Penyesuaian");
         $("#loadmodal").load(`/penygudangcbg/${no_mutasi}/show`);
      });

      $(".btnEdit").click(function(e) {
         e.preventDefault();
         var no_mutasi = $(this).attr("no_mutasi");
         e.preventDefault();
         loading();
         $("#modal").modal("show");
         $(".modal-title").text("Edit Penyesuaian");
         $("#loadmodal").load(`/penygudangcbg/${no_mutasi}/edit`);
      });
   });
</script>
@endpush
