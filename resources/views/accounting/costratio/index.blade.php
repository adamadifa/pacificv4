@extends('layouts.app')
@section('titlepage', 'Cost Ratio')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Cost Ratio</h4>
            <small class="text-muted">Manajemen biaya dan rasio biaya operasional.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-settings me-1"></i>Accounting</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-chart-bar me-1"></i>Cost Ratio</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ url()->current() }}" id="formSearch">
            <div class="row g-2 mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
            </div>
            @hasanyrole($roles_show_cabang)
                <div class="row g-2 mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                            textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                            select2="select2Kodecabangsearch" hideLabel="true" />
                    </div>
                </div>
            @endrole

            <div class="row g-2 mb-3 align-items-end">
                <div class="col">
                    <x-select label="Semua Sumber Cost Ratio" name="kode_sumber_search" :data="$sumber" key="kode_sumber" textShow="sumber"
                        upperCase="true" selected="{{ Request('kode_sumber_search') }}" hideLabel="true" />
                </div>
                <div class="col-auto">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary btn-sm"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Card Data --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-chart-bar me-2"></i>Data Cost Ratio</h6>
                    <div class="d-flex gap-2">
                        @can('costratio.index')
                            <form action="/costratio/cetak" method="GET" id="formCetak" target="_blank" class="d-flex gap-2">
                                <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                                <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                                <input type="hidden" name="kode_cabang_search" id="kode_cabang_cetak" value="{{ Request('kode_cabang_search') }}" />
                                <button class="btn btn-primary btn-sm"><i class="ti ti-printer me-1"></i>Cetak</button>
                                <button class="btn btn-success btn-sm" name="exportButton"><i class="ti ti-download me-1"></i>Export</button>
                            </form>
                        @endcan
                        @can('costratio.create')
                            <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Input Cost Ratio</a>
                        @endcan
                    </div>
                </div>
            </div>
            
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white py-3">KODE CR</th>
                            <th class="text-white py-3">TANGGAL</th>
                            <th class="text-white py-3">AKUN</th>
                            <th class="text-white py-3">KETERANGAN</th>
                            <th class="text-white py-3">JUMLAH</th>
                            <th class="text-white py-3">SUMBER</th>
                            <th class="text-white py-3">CABANG</th>
                            <th class="text-white text-center py-3" style="width: 10%">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($costratio as $d)
                            <tr>
                                <td class="py-2"><span class="fw-bold">{{ $d->kode_cr }}</span></td>
                                <td class="py-2">{{ formatIndo($d->tanggal) }}</td>
                                <td class="py-2">{{ $d->kode_akun }}- {{ $d->nama_akun }}</td>
                                <td class="py-2">{{ textCamelCase($d->keterangan) }}</td>
                                <td class="py-2 text-end fw-bold">{{ formatAngka($d->jumlah) }}</td>
                                <td class="py-2">{{ $d->sumber }}</td>
                                <td class="py-2">{{ textUpperCase($d->nama_cabang) }}</td>
                                <td class="py-2">
                                    <div class="d-flex justify-content-center">
                                        @can('costratio.delete')
                                            @if ($d->kode_sumber == '3')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('costratio.delete', Crypt::encrypt($d->kode_cr)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="ti ti-database-off d-block mb-1 fs-2"></i>
                                    Tidak ada data ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $costratio->links() }}
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
            if (dari == "" || sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih Rentang Tanggal Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true
                });
                return false;
            }
        });
    });
</script>
@endpush
