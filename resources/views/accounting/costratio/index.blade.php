@extends('layouts.app')
@section('titlepage', 'Cost Ratio')

@section('content')
@section('navigasi')
    <span>Cost Ratio</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @can('costratio.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Input Cost Ratio</a>
                    @endcan
                    @can('costratio.index')
                        <form action="/costratio/cetak" method="GET" id="formCetak" target="_blank">
                            <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                            <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                            <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                            <button class="btn btn-primary"><i class="ti ti-printer me-1"></i>Cetak</button>
                            <button class="btn btn-success" name="exportButton"><i class="ti ti-download me-1"></i>Export Excel</button>
                        </form>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ url()->current() }}" id="formSearch">
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
                                        <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                            upperCase="true" selected="{{ Request('kode_cabang_search') }}" select2="select2Kodecabangsearch" />
                                    </div>
                                </div>
                            @endrole
                            <x-select label="Sumber Cost Ratio" name="kode_sumber_search" :data="$sumber" key="kode_sumber" textShow="sumber"
                                upperCase="true" selected="{{ Request('kode_sumber_search') }}" />
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
                            <table class="table  table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 10%">Kode CR</th>
                                        <th style="width: 10%">Tanggal</th>
                                        <th style="width: 20%">Akun</th>
                                        <th style="width: 25%">Keterangan</th>
                                        <th>Jumlah</th>
                                        <th>Sumber</th>
                                        <th>Cabang</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($costratio as $d)
                                        <tr>
                                            <td>{{ $d->kode_cr }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->kode_akun }}- {{ $d->nama_akun }}</td>
                                            <td>{{ textCamelCase($d->keterangan) }}</td>
                                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                                            <td>{{ $d->sumber }}</td>
                                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                            <td>
                                                @can('costratio.delete')
                                                    @if ($d->kode_sumber == '3')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('costratio.delete', Crypt::encrypt($d->kode_cr)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $costratio->links() }}
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
<script>
    $(function() {
        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Cabang',
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
            $("#modal").find(".modal-title").text("Input Costratio");
            $("#modal").find("#loadmodal").load(`/costratio/create`);
        });

        $("#formCetak").submit(function(e) {
            const dari = $("#dari_cetak").val();
            const sampai = $("#sampai_cetak").val();
            const kode_cabang = $("#kode_cabang_cetak").val();
            if (dari == "" && sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Lakukan Pencarian Data Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            }
        });

    });
</script>
@endpush
