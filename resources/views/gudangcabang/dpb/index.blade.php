@extends('layouts.app')
@section('titlepage', 'Data Pengambilan Barang')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Data Pengambilan Barang</h4>
            <small class="text-muted">Kelola data pengambilan barang (DPB) cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Cabang</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-box me-1"></i>DPB</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasigudangcabang')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('dpb.index') }}" id="formSearch">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <x-input-with-icon label="No. DPB" name="no_dpb_search" icon="ti ti-barcode"
                                value="{{ Request('no_dpb_search') }}" />
                        </div>
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" />
                            </div>
                        @endhasanyrole
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group mb-1">
                                <select name="kode_salesman_search" id="kode_salesman_search" class="form-select">
                                    <option value="">Salesman</option>
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-box me-2"></i>Data DPB</h6>
                    @can('dpb.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Buat DPB</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. DPB</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">SALESMAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">CABANG</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TUJUAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. KENDARAAN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">KEMBALI</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($dpb as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->no_dpb }}</span></td>
                                <td>{{ DateToIndo($d->tanggal_ambil) }}</td>
                                <td>{{ $d->nama_salesman }}</td>
                                <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                <td>{{ $d->tujuan }}</td>
                                <td>{{ $d->no_polisi }}</td>
                                <td class="text-center">
                                    @if (!empty($d->tanggal_kembali))
                                        <span class="badge bg-label-success">{{ DateToIndo($d->tanggal_kembali) }}</span>
                                    @else
                                        <i class="ti ti-refresh text-warning" data-bs-toggle="tooltip" title="Belum Kembali"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('dpb.edit')
                                            <a href="#" class="btnEdit text-success" data-bs-toggle="tooltip" title="Edit"
                                                no_dpb="{{ Crypt::encrypt($d->no_dpb) }}">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('dpb.show')
                                            <a href="#" class="btnShow text-info" data-bs-toggle="tooltip" title="Detail"
                                                no_dpb="{{ Crypt::encrypt($d->no_dpb) }}">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('dpb.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('dpb.delete', Crypt::encrypt($d->no_dpb)) }}">
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
                    {{ $dpb->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modalMutasi" size="modal-lg" show="loadmodalMutasi" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        const form = $("#formSearch");

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
            $(".modal-title").text("Buat DPB");
            $("#loadmodal").load(`/dpb/create`);
        });


        $(".btnShow").click(function(e) {
            e.preventDefault();
            const no_dpb = $(this).attr('no_dpb');
            $("#modal").modal("show");
            $(".modal-title").text("Detail DPB");
            $("#loadmodal").load(`/dpb/${no_dpb}/show`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const no_dpb = $(this).attr('no_dpb');
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit DPB");
            $("#loadmodal").load(`/dpb/${no_dpb}/edit`);
        });


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


        function getsalesmanbyCabang() {
            var kode_cabang = form.find("#kode_cabang_search").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    form.find("#kode_salesman_search").html(respond);
                }
            });
        }

        getsalesmanbyCabang();
        form.find("#kode_cabang_search").change(function(e) {
            getsalesmanbyCabang();
        });

    });
</script>
@endpush
