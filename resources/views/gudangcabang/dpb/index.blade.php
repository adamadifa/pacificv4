@extends('layouts.app')
@section('titlepage', 'Data Pengambilan Barang')

@section('content')
@section('navigasi')
    <span>Data Pengambilan Barang (DPB)</span>
@endsection
<div class="row">
    <div class="col-lg-11 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('dpb.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat DPB</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('dpb.index') }}" id="formSearch">
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
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <x-input-with-icon label="No. DPB" name="no_dpb_search" icon="ti ti-barcode"
                                        value="{{ Request('no_dpb_search') }}" />
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
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group mb-3">
                                    <select name="kode_salesman_search" id="kode_salesman_search" class="form-select">
                                        <option value="">Salesman</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
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
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. DPB</th>
                                        <th>Tanggal</th>
                                        <th>Salesman</th>
                                        <th>Cabang</th>
                                        <th>Tujuan</th>
                                        <th>No. Kendaraan</th>
                                        <th>Kembali</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dpb as $d)
                                        <tr>
                                            <td>{{ $d->no_dpb }}</td>
                                            <td>{{ DateToIndo($d->tanggal_ambil) }}</td>
                                            <td>{{ $d->nama_salesman }}</td>
                                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                            <td>{{ $d->tujuan }}</td>
                                            <td>{{ $d->no_polisi }}</td>
                                            <td class="text-center">
                                                @if (!empty($d->tanggal_kembali))
                                                    {{ DateToIndo($d->tanggal_kembali) }}
                                                @else
                                                    <i class="ti ti-hourglass-empty text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('dpb.edit')
                                                        <div>
                                                            <a href="#" class="me-2 btnEdit" no_dpb="{{ Crypt::encrypt($d->no_dpb) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('dpb.show')
                                                        <div>
                                                            <a href="#" class="me-2 btnShow" no_dpb="{{ Crypt::encrypt($d->no_dpb) }}">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('dpb.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('dpb.delete', Crypt::encrypt($d->no_dpb)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $dpb->links() }}
                        </div>
                    </div>
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
